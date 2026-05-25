<?php
namespace App\Traits;
use App\Models\{Facimportsdet, Existencia, Referenciasmov, Bandeja, Bandejasmov, Foliosmat, Estilosdet, Folio};
use Illuminate\Support\Facades\DB; 
trait FolioManager
{
public function procesar()
{
    if (!$this->IdFolio) return;
    $folio = Folio::with(['foliosmats', 'bandejas'])->find($this->IdFolio);
    $yaProcesado = $folio->foliosmats()->where('integrado', true)->exists() && $folio->bandejas()->exists();
    if ($yaProcesado && !$this->confirmadoExceso) {
        $this->alerta("⚠️ Este folio ya ha sido procesado anteriormente.", 'error', 3000);
        return;
    }
    $piezasTotales = $folio->cantidad;
    $config = json_decode(file_get_contents(base_path('settings.json')), true);
    $cantMaxBandeja = $config['Parametros'][0]['cantBandeja'] ?? 10;
    $nBandejasActual = $folio->totalBandejas > 0 ? $folio->totalBandejas : 1;
    $bandejasNecesarias = ceil($piezasTotales / $cantMaxBandeja);
    if ($nBandejasActual < $bandejasNecesarias && !$this->confirmadoExceso) {
        $folio->update(['totalBandejas' => $bandejasNecesarias]);
        $this->confirmadoExceso = true;
        $this->alerta("⚠️ Capacidad excedida (máx {$cantMaxBandeja} por bandeja). Se ajustó a {$bandejasNecesarias} bandejas. Presione procesar de nuevo.", 'warning', 5000);
        return;
    }
    $materialesSurtir = $folio->foliosmats()->whereNotNull('IdFacImportsDet')->where('integrado', false)->get();
    if ($materialesSurtir->isEmpty() && $folio->bandejas->isEmpty()) {
        $this->alerta('⚠️ No hay materiales con referencia pendientes', 'warning', 2500);
        return;
    }
    $idDeptoBoveda = DB::table('deptos')->where('depto', '0 BOVEDA')->value('id');
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
                'IdDoc' => $this->IdFolio,
                'glosa' => "Salida a Folio #{$this->IdFolio}",
                'estatus' => 'cerrado'
            ]);
            $item->update(['integrado' => true]);
        }
    }
    $salidasEfectivas = Referenciasmov::with('Material.Clase.Tipo')
        ->where('tipoDoc', 'folio')
        ->where('IdDoc', $this->IdFolio)
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
    if (($pesoMetal + $pesoPiedras + $pesoDiamantes + $pesoMisc) > 0) {
        $piezasRestantes = $piezasTotales;
        $nBandejasFinal = $folio->totalBandejas;
        for ($i = 1; $i <= $nBandejasFinal; $i++) {
            $piezasBandeja = ($i == $nBandejasFinal) ? $piezasRestantes : min($piezasRestantes, $cantMaxBandeja);
            $factor = $piezasBandeja / $piezasTotales;
            $bandeja = Bandeja::updateOrCreate(
                ['IdFolio' => $this->IdFolio, 'id' => $folio->bandejas[$i - 1]->id ?? null],
                [
                    'cantidad' => $piezasBandeja,
                    'castingIni' => round($pesoMetal * $factor, 4),
                    'castingFin' => round($pesoMetal * $factor, 4),
                    'piedrasG' => round($pesoPiedras * $factor, 4),
                    'diamantesG' => round($pesoDiamantes * $factor, 4),
                    'miscG' => round($pesoMisc * $factor, 4),
                    'IdProcesoActual' => 1,
                    'enBoveda' => false,
                    'habilitada' => true,
                    'estatus' => 'proceso'
                ]
            );
            Bandejasmov::updateOrCreate(
                ['IdBandeja' => $bandeja->id, 'IdProceso' => 1],
                [
                    'pesoEntrada' => round(($pesoMetal + $pesoPiedras + $pesoDiamantes + $pesoMisc) * $factor, 4),
                    'fechaHEntrada' => now(),
                    'fechaHSalida' => now()
                ]
            );
            $piezasRestantes -= $piezasBandeja;
        }
    }
    $this->confirmadoExceso = false;
    $this->alerta("✅ Bandejas generadas con pesos distribuidos.", 'success', 2500);
    $this->dispatch('refreshFolios');
}
    public function generarMateriales()
    {
        if (!$this->folio) return;
        Foliosmat::where('IdFolio', $this->IdFolio)->delete();
        $idDeptoBoveda = DB::table('deptos')->where('depto', '0 BOVEDA')->value('id');
        if (!$idDeptoBoveda) {
            $this->alerta('❌ Error: El departamento "0 BOVEDA" no existe', 'error', 3000);
            return;
        }
        $reservados = Facimportsdet::with(['material', 'existencias' => function($query) use ($idDeptoBoveda) {
            $query->where('IdDepto', $idDeptoBoveda);
        }])->where('IdFolio', $this->IdFolio)->get();
        if ($reservados->isNotEmpty()) {
            $conteoCreados = 0;
            foreach ($reservados as $row) {
                $existenciaEnBoveda = $row->existencias->first();
                $cantidadStock = $existenciaEnBoveda->cantidad ?? 0;
                if ($cantidadStock > 0) {
                    $cantidadAsignada = min($row->cantidad, $cantidadStock);
                    $pesoFinal = ($row->cantidad > 0) ? min(round(($row->pesoG / $row->cantidad) * $cantidadAsignada, 4), $existenciaEnBoveda->pesoG ?? 0) : 0;
                    Foliosmat::create([
                        'IdFolio' => $this->IdFolio,
                        'IdFacImportsDet' => $row->id,
                        'IdMaterial' => $row->IdMaterial,
                        'IdTipo' => $row->Material->Clase->IdTipo,
                        'cantidad' => $cantidadAsignada,
                        'pesoG' => $pesoFinal,
                        'integrado' => false,
                    ]);
                    $conteoCreados++;
                }
            }
            if ($conteoCreados > 0) $this->alerta("✅ Se vincularon $conteoCreados materiales", 'success', 2000);
        } else {
            $estilosdets = Estilosdet::where('IdEstilo', $this->folio->IdEstilo)->get();
            foreach ($estilosdets as $row) {
                Foliosmat::create([
                    'IdFolio' => $this->IdFolio,
                    'IdMaterial' => $row->IdMaterial,
                    'cantidad' => $row->cantidad * $this->folio->cantidad,
                    'pesoG' => 0,
                    'integrado' => false,
                ]);
            }
            $this->alerta("ℹ️ Materiales proyectados según estilo", 'info', 2000);
        }
        $this->dispatch('refreshFolios');
    }
    public function limpiar()
    {
        if (!$this->IdFolio) return;
        Foliosmat::where('IdFolio', $this->IdFolio)->delete();
        $this->alerta('🗑️ Registros eliminados', 'success', 1000);
        $this->dispatch('refreshFolios');
    }
}