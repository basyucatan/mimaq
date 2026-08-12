<?php
namespace App\Livewire;
use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\{Factura, Facimportsdet, Existencia, Referenciasmov,
    Orden, Lote, Folio, Foliosmat, Bandeja, Bandejasmov, Empleado};
use App\Traits\Utilfun;
use Illuminate\Support\Facades\DB;
class Recibirimports extends Component
{
    use Utilfun;
    public $selected_id, $objFactura, $difs;
    public $edicionFisica = [];
    #[On('IdFacturaElecta')]
    public function elegirFactura($id)
    {
        $this->selected_id = $id;
        $this->objFactura = Factura::find($id);
    }
public function generarFolios()
{
    if (!$this->objFactura) return;
    $detalles = Facimportsdet::where('IdFactura', $this->objFactura->id)->get();
    if ($detalles->isEmpty()) return;
    $detallesProduccion = $detalles->filter(function ($item) {
        $info = $item->adicionales['ordenInfo'] ?? [];
        return !empty($info['orden']) && !empty($info['lote']);
    });
    if ($detallesProduccion->isEmpty()) {
        $this->alerta('ℹ️ No hay registros destinados a producción', 'info', 2000);
        return;
    }
    $pendientesProcesar = $detallesProduccion->filter(fn($item) => empty($item->IdFolio));
    if ($pendientesProcesar->isEmpty()) {
        $this->alerta('⚠️ Ya se han generados los folios!', 'warning', 2500);
        return;
    }
    $idDeptoBoveda = DB::table('deptos')->where('depto', '0 BOVEDA')->value('id');
    if (!$idDeptoBoveda) {
        $this->alerta('❌ Error: El departamento "0 BOVEDA" no existe', 'error', 2000);
        return;
    }
    $idsDetalles = $pendientesProcesar->pluck('id');
    $existenciasMap = Existencia::whereIn('IdFacImportsDet', $idsDetalles)
        ->where('IdDepto', $idDeptoBoveda)
        ->where('cantidad', '>', 0)
        ->pluck('cantidad', 'IdFacImportsDet');
    if ($existenciasMap->isEmpty()) {
        $this->alerta('⛔ Aún no se ha confirmado el ingreso a Bóveda', 'warning', 3000);
        return;
    }
    $controlEmpleado = Empleado::where('numero', 999)->first();
    $controlEmpleadoId = $controlEmpleado->id ?? null;
    $config = json_decode(file_get_contents(base_path('settings.json')), true);
    $cantMaxBandeja = $config['Parametros'][0]['cantBandeja'] ?? 10;
    $gruposLote = $pendientesProcesar->groupBy(function ($item) {
        $info = $item->adicionales['ordenInfo'];
        return ($info['IdCliente'] ?? '0') . '_' . $info['orden'] . '_' . $info['lote'];
    });
    $foliosGenerados = 0;
    foreach ($gruposLote as $grupo) {
        $pivotes = $grupo->filter(fn($item) => !empty($item->adicionales['ordenInfo']['esProduccion']));
        if ($pivotes->isEmpty()) continue;
        $primerPivote = $pivotes->first();
        $infoLote = $primerPivote->adicionales['ordenInfo'];
        $ordenModel = null;
        $loteModel = null;
        foreach ($pivotes as $pivote) {
            $insumosAcompanantes = $grupo->filter(function ($item) use ($pivote) {
                return empty($item->adicionales['ordenInfo']['esProduccion']) && $item->id != $pivote->id;
            });
            $materialesIniciales = $insumosAcompanantes->push($pivote);
            $materialesConStock = $materialesIniciales->filter(function ($row) use ($existenciasMap) {
                return isset($existenciasMap[$row->id]) && $existenciasMap[$row->id] > 0;
            });
            if ($materialesConStock->isEmpty()) {
                continue;
            }
            if (!$ordenModel) {
                $ordenModel = Orden::firstOrCreate(
                    ['orden' => $infoLote['orden']],
                    [
                        'IdCliente' => $infoLote['IdCliente'] ?? null,
                        'estatus' => 'abierto',
                        'fechaVen' => now()->addDays(15)->format('Y-m-d')
                    ]
                );
                $loteModel = Lote::firstOrCreate(
                    [
                        'IdOrden' => $ordenModel->id,
                        'lote' => $infoLote['lote']
                    ]
                );
            }
            $infoPivote = $pivote->adicionales['ordenInfo'] ?? [];
            $adicPivote = $pivote->adicionales ?? [];
            $idEstilo = $infoPivote['IdEstilo'] ?? $pivote->IdEstilo;
            $totalPiezasOrden = $infoPivote['cantidadEstilo'] ?? $pivote->cantidad;
            $kt = $adicPivote['kt'] ?? ($infoPivote['kt'] ?? '');
            $color = $adicPivote['color'] ?? ($infoPivote['color'] ?? '');
            $vKt = is_array($kt) ? ($kt['valor'] ?? '') : $kt;
            $vCol = is_array($color) ? ($color['valor'] ?? '') : $color;
            $folioModel = new Folio();
            $folioModel->IdLote = $loteModel->id;
            $folioModel->precioU = $pivote->precioU ?? 0;
            $folioModel->fechaVen = $ordenModel->fechaVen;
            $folioModel->estatus = 'abierto';
            $folioModel->adicionales = [
                'IdFactura' => $this->objFactura->id,
                'kt' => $vKt,
                'color' => $vCol
            ];
            $folioModel->definirProducto($idEstilo, $totalPiezasOrden, $vKt, $vCol);
            $folioModel->save();
            $pivote->update(['IdFolio' => $folioModel->id]);
            if ($insumosAcompanantes->isNotEmpty()) {
                Facimportsdet::whereIn('id', $insumosAcompanantes->pluck('id'))->update(['IdFolio' => $folioModel->id]);
            }
            Foliosmat::where('IdFolio', $folioModel->id)->delete();
            foreach ($materialesConStock as $row) {
                $existenciaEnBoveda = Existencia::where('IdFacImportsDet', $row->id)
                    ->where('IdDepto', $idDeptoBoveda)
                    ->first();
                $cantidadStock = $existenciaEnBoveda->cantidad ?? 0;
                $cantidadAsignada = min($row->cantidad, $cantidadStock);
                $pesoFinal = ($row->cantidad > 0) ? min(round(($row->pesoG / $row->cantidad) * $cantidadAsignada, 4), $existenciaEnBoveda->pesoG ?? 0) : 0;
                Foliosmat::create([
                    'IdFolio' => $folioModel->id,
                    'IdFacImportsDet' => $row->id,
                    'IdMaterial' => $row->IdMaterial,
                    'IdTipo' => $row->material->clase->IdTipo ?? null,
                    'cantidad' => $cantidadAsignada,
                    'pesoG' => $pesoFinal,
                    'integrado' => false,
                ]);
            }
            $this->generaMov1($folioModel, $idDeptoBoveda, $controlEmpleadoId, $cantMaxBandeja);
            $foliosGenerados++;
        }
    }
    if ($foliosGenerados === 0) {
        $this->alerta('⛔ Sin existencias confirmadas en Bóveda', 'warning', 2500);
        return;
    }
    $this->alerta('✅ Estructura de folios y bandejas registrada con éxito', 'success', 1500);
    $this->dispatch('refreshRefsMovs');
    $this->dispatch('refreshFolios');
}
private function generaMov1($folio, $idDeptoBoveda, $controlEmpleadoId, $cantMaxBandeja)
{
    $idProcesoDistribucion = DB::table('procesos')->where('proceso', '00 DISTRIBUCION')->value('id');
    $idProcesoValidacion = DB::table('procesos')->where('proceso', '05 VALIDACION')->value('id');
    $piezasTotales = $folio->cantidad;
    if ($folio->totalBandejas <= 0) {
        $nBandejasActual = (int)ceil($piezasTotales / $cantMaxBandeja);
        $folio->update(['totalBandejas' => $nBandejasActual]);
    } else {
        $nBandejasActual = (int)$folio->totalBandejas;
    }
    $materialesSurtir = $folio->foliosmats()->whereNotNull('IdFacImportsDet')->where('integrado', false)->get();
    foreach ($materialesSurtir as $item) {
        $existencia = Existencia::where('IdFacImportsDet', $item->IdFacImportsDet)->where('IdDepto', $idDeptoBoveda)->first();
        if ($existencia && $existencia->cantidad >= $item->cantidad) {
            $existencia->decrement('cantidad', $item->cantidad);
            $existencia->decrement('pesoG', $item->pesoG);
            Referenciasmov::create([
                'IdFacImportsDet' => $item->IdFacImportsDet,
                'IdMaterial' => $item->IdMaterial,
                'IdDeptoOri' => $idDeptoBoveda,
                'IdDeptoDes' => 2,
                'tipo' => 'salida',
                'cantidad' => $item->cantidad,
                'pesoG' => $item->pesoG,
                'tipoDoc' => 'folio',
                'IdDoc' => $folio->id,
                'glosa' => "Salida a Folio #{$folio->id}",
                'estatus' => 'cerrado'
            ]);
            $item->update(['integrado' => true]);
        }
    }
    $salidasEfectivas = Referenciasmov::with('Material.Clase.Tipo')
        ->where('tipoDoc', 'folio')
        ->where('IdDoc', $folio->id)
        ->where('tipo', 'salida')
        ->get();
    $pesoMetal = 0; $pesoPiedras = 0; $pesoDiamantes = 0; $pesoMisc = 0;
    foreach ($salidasEfectivas as $mov) {
        $tipo = $mov->Material->Clase->IdTipo ?? null;
        if ($tipo == 1) {
            $pesoMetal += $mov->pesoG;
        } elseif ($tipo == 2) {
            $pesoDiamantes += $mov->pesoG;
        } elseif ($tipo == 7) {
            $pesoPiedras += $mov->pesoG;
        } elseif ($tipo == 6) {
            $pesoMisc += $mov->pesoG;
        }
    }
    $pesoTotalMateriales = $pesoMetal + $pesoPiedras + $pesoDiamantes + $pesoMisc;
    if ($pesoTotalMateriales > 0 && $nBandejasActual > 0) {
        $piezasRestantes = $piezasTotales;
        $piezasPorBandejaBase = (int)intdiv($piezasTotales, $nBandejasActual);
        $bandejasExistentes = $folio->bandejas;
        $ahora = now()->tz('America/Mexico_City');
        for ($i = 1; $i <= $nBandejasActual; $i++) {
            $piezasBandeja = ($i === $nBandejasActual) ? $piezasRestantes : $piezasPorBandejaBase;
            $factor = $piezasBandeja / $piezasTotales;
            $bandeja = Bandeja::updateOrCreate(
                ['IdFolio' => $folio->id, 'id' => $bandejasExistentes[$i - 1]->id ?? null],
                [
                    'cantidad' => $piezasBandeja,
                    'castingIni' => round($pesoMetal * $factor, 4),
                    'castingFin' => round($pesoMetal * $factor, 4),
                    'piedrasG' => round($pesoPiedras * $factor, 4),
                    'diamantesG' => round($pesoDiamantes * $factor, 4),
                    'miscG' => round($pesoMisc * $factor, 4),
                    'IdProcesoActual' => $idProcesoDistribucion,
                    'enBoveda' => false,
                    'habilitada' => true,
                    'estatus' => 'proceso'
                ]
            );
            $pesoCalculado = round($pesoTotalMateriales * $factor, 4);
            Bandejasmov::create([
                'IdBandeja' => $bandeja->id,
                'IdProceso' => $idProcesoDistribucion,
                'IdProcesoSig' => $idProcesoValidacion,
                'IdUser' => auth()->user()->id,
                'IdEmpleado' => $controlEmpleadoId,
                'IdRegistrador' => $controlEmpleadoId,
                'pesoEntrada' => $pesoCalculado,
                'pesoSalida' => $pesoCalculado,
                'fechaHEntrada' => $ahora,
                'fechaHSalida' => $ahora
            ]);
            $piezasRestantes -= $piezasBandeja;
        }
    }
}

public function confirmarIngreso() 
{
    if (!$this->objFactura) return;
    $movimientosAbiertos = Referenciasmov::where('IdDoc', $this->objFactura->id)
        ->where('tipoDoc', 'import')
        ->where('estatus', 'abierto')
        ->get();
    if ($movimientosAbiertos->isEmpty()) {
        $this->alerta('⚠️ No hay movimientos abiertos para confirmar', 'warning', 1500);
        return;
    }
    $idDeptoBoveda = DB::table('deptos')->where('depto', '0 BOVEDA')->value('id');
    if (!$idDeptoBoveda) {
        $this->alerta('❌ Error: El departamento "0 BOVEDA" no existe en el sistema', 'error', 3000);
        return;
    }
    foreach ($movimientosAbiertos as $movimiento) {
        $existencia = Existencia::where('IdFacImportsDet', $movimiento->IdFacImportsDet)
            ->where('IdDepto', $idDeptoBoveda)
            ->first();
        if ($existencia) {
            $existencia->update([
                'cantidad' => DB::raw("cantidad + $movimiento->cantidad"),
                'pesoG' => DB::raw("pesoG + $movimiento->pesoG")
            ]);
        } else {
            Existencia::create([
                'IdFacImportsDet' => $movimiento->IdFacImportsDet,
                'IdDepto' => $idDeptoBoveda,
                'cantidad' => $movimiento->cantidad,
                'pesoG' => $movimiento->pesoG
            ]);
        }
        $movimiento->update([
            'IdDeptoDes' => $idDeptoBoveda,
            'estatus' => 'cerrado',
            'glosa' => 'Ingreso a bóveda ' . now()->tz('America/Mexico_City')->format('d/M H:i')
        ]);
    }
    $this->objFactura->update(['estatus' => 'recibido']);
    $this->alerta("🔐 Inventario actualizado y movimientos cerrados", 'success', 2000);
    $this->dispatch('refreshRefsMovs');
}
public function recibirFactura()
{
    if (!$this->objFactura) return;
    if ($this->objFactura->estatus == 'abierto') {
        return $this->alerta('⚠️ Esta factura aún no se ha cerrado', 'warning', 1500);
    }
    $facturaConDetalles = Factura::with('facimportsdets')->find($this->objFactura->id);
    if (!$facturaConDetalles || $facturaConDetalles->facimportsdets->isEmpty()) {
        $this->alerta('⚠️ No hay registros que operar', 'warning', 1500);
        return;
    }
    $idsExistentes = Referenciasmov::where('IdDoc', $this->objFactura->id)
        ->where('tipoDoc', 'import')
        ->pluck('IdFacImportsDet')
        ->toArray();
    $detallesFaltantes = $facturaConDetalles->facimportsdets->whereNotIn('id', $idsExistentes);
    if ($detallesFaltantes->isEmpty()) {
        $this->alerta('ℹ️ Todo está al día', 'info', 1000);
        return;
    }
    foreach ($detallesFaltantes as $detalle) {
        Referenciasmov::create([
            'IdFacImportsDet' => $detalle->id,
            'IdMaterial' => $detalle->IdMaterial,
            'IdDeptoOri' => null,
            'IdDeptoDes' => null,
            'tipo' => 'entrada',
            'cantidad' => $detalle->cantidad,
            'pesoG' => $detalle->pesoG,
            'tipoDoc' => 'import',
            'IdDoc' => $this->objFactura->id,
            'glosa' => 'Carga inicial por importación',
            'estatus' => 'abierto'
        ]);
    }
    $this->alerta("✅ {$detallesFaltantes->count()} registros descargados", 'success', 1000);
    $this->dispatch('refreshRefsMovs');
}
    public function limpiar()
    {
        if (!$this->objFactura) return;
        if ($this->objFactura->estatus == 'recibido') {
            return $this->alerta('⚠️ Esta factura ya se ha recibido', 'warning', 1500);
        }
        DB::transaction(function () {
            $idsDetalles = Facimportsdet::where('IdFactura', $this->objFactura->id)->pluck('id');
            Existencia::whereIn('IdFacImportsDet', $idsDetalles)->delete();
            Referenciasmov::where('IdDoc', $this->objFactura->id)
                ->where('tipoDoc', 'import')
                ->delete();
        });
        $this->alerta('🗑️ Movimientos y existencias eliminados', 'success', 1000);
        $this->dispatch('refreshRefsMovs');
    }    
    public function render()
    {
        return view('livewire.recibirimports.view');
    }
}