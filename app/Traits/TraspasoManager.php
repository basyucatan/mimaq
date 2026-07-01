<?php
namespace App\Traits;
use App\Models\{Bandeja,Bandejasmov, Facexportsdet, Facexportsmat, Empleado};
trait TraspasoManager
{
    public function exportar()
    {
        if (empty($this->IdFactura)) {
            return;
        }
        $bandejas = Bandeja::where('estatus', 'terminado')
            ->where('adicionales->IdFactura', $this->IdFactura)
            ->with([
                'folio.estilo.clase.arancel',
                'folio.foliosmats.material.clase',
                'folio.foliosmats.facImportsDet'
            ])
            ->get();
        if ($bandejas->isEmpty()) {
            return;
        }
        foreach ($bandejas as $bandeja) {
            $folio = $bandeja->folio;
            if (!$folio || $folio->cantidad <= 0) {
                continue;
            }
            $arancel = $folio->estilo?->clase?->arancel?->arancel ?? '';
            $pesoTotal = $bandeja->castingFin + $bandeja->piedrasG + $bandeja->diamantesG + $bandeja->miscG;
            $exportDet = FacExportsDet::create([
                'IdFactura' => $this->IdFactura,
                'IdBandeja' => $bandeja->id,
                'productoFinal' => $folio->productoFinal ?? '--',
                'arancel' => $arancel,
                'cantidad' => $bandeja->cantidad,
                'precioU' => $folio->precioU(),
                'pesoG' => $pesoTotal,
                'castingIni' => $bandeja->castingIni,
                'castingG' => $bandeja->castingFin,
                'piedrasG' => $bandeja->piedrasG,
                'diamantesG' => $bandeja->diamantesG,
                'miscG' => $bandeja->miscG,
                'adicionales' => null
            ]);
            $composicion = $folio->adicionales['composicion'] ?? [];
            foreach ($composicion as $indice => $datosComponente) {
                $cantidadPorPieza = $datosComponente['cantidad'] ?? 0;
                $idTipo = $datosComponente['idTipo'] ?? null;
                $cantidadRequeridaBandeja = $cantidadPorPieza * $bandeja->cantidad;
                if ($cantidadRequeridaBandeja <= 0 || !$idTipo) {
                    continue;
                }
                $materialesFolio = $folio->foliosmats->filter(function ($item) use ($idTipo) {
                    return $item->IdTipo == $idTipo;
                })->sortBy('id');
                foreach ($materialesFolio as $mat) {
                    if ($cantidadRequeridaBandeja <= 0) {
                        break;
                    }
                    $consumidoPreviamente = Facexportsmat::where('IdFacImportsDet', $mat->IdFacImportsDet)
                        ->whereHas('facExportsDet', function ($query) use ($folio) {
                            $query->where('IdBandeja', '!=', 0)
                                ->whereHas('bandeja', function ($q) use ($folio) {
                                    $q->where('IdFolio', $folio->id);
                                });
                        })->sum('cantidad');
                    $saldoDisponibleInm = $mat->cantidad - $consumidoPreviamente;
                    if ($saldoDisponibleInm <= 0) {
                        continue;
                    }
                    $pesoUnitarioImportacion = $mat->cantidad > 0 ? ($mat->pesoG / $mat->cantidad) : 0;
                    $cantidadADescargar = min($cantidadRequeridaBandeja, $saldoDisponibleInm);
                    $pesoADescargar = $cantidadADescargar * $pesoUnitarioImportacion;
                    Facexportsmat::create([
                        'IdFacExportsDet' => $exportDet->id,
                        'IdFacImportsDet' => $mat->IdFacImportsDet,
                        'cantidad' => $cantidadADescargar,
                        'pesoG' => $pesoADescargar
                    ]);
                    $cantidadRequeridaBandeja -= $cantidadADescargar;
                }
            }
            $bandeja->IdFacturaExport = $this->IdFactura;
            $bandeja->estatus = 'exportado';
            $bandeja->save();
        }
        $this->dispatch('render');
    }
    public function terminar($id)
    {
        $this->validate([
            'IdFactura' => 'required'
        ]);
        $bandeja = Bandeja::find($id);
        if ($bandeja) {
            $adicionales = $bandeja->adicionales ?? [];
            if ($bandeja->estatus === 'terminado') {
                $bandeja->estatus = 'proceso';
                unset($adicionales['IdFactura']);
            } else {
                $bandeja->estatus = 'terminado';
                $adicionales['IdFactura'] = $this->IdFactura;
            }
            $bandeja->adicionales = $adicionales;
            $bandeja->save();
        }
    }
    public function traspasar($id)
    {
        $bandeja = Bandeja::find($id);
        if ($bandeja) {
            $this->iniciarTraspaso($bandeja->id);
        } else {
            $this->dispatch('error', ['message' => 'La bandeja no existe.']);
        }
    }
    public function recibir($id)
    {
        $bandeja = Bandeja::find($id);
        if (!$bandeja) {
            $this->dispatch('error', ['message' => 'La bandeja no existe.']);
            return;
        }
        $ultimoMov = $bandeja->ultimoMovimiento;
        if ($ultimoMov) {
            $this->pesoEntrada = $ultimoMov->pesoEntrada;
            $this->empTraspaso = $ultimoMov->empTraspaso;
            $this->regTraspaso = $ultimoMov->regTraspaso;
            $this->pesoSalida = null;
        }
        $this->verModalTraspaso = true;
    }
    public function escanear()
    {
        if (empty($this->selected_id)) {
            return;
        }
        $this->traspasar($this->selected_id);
        $this->reset('selected_id');
    }
    public function iniciarTraspaso($id)
    {
        $this->idBandejaTraspaso = $id;
        $bandeja = Bandeja::findOrFail($id);
        $this->codigoBandeja = $bandeja->codigoBandeja;
        $this->idProcesoDestino = null;
        $ultimoMov = $bandeja->ultimoMovimiento;
        if ($ultimoMov) {
            $this->pesoEntrada = $ultimoMov->pesoSalida;
        } else {
            $this->pesoEntrada = null;
        }
        $this->empTraspaso = null;
        $this->regTraspaso = null;
        $this->pesoSalida = null;
        $this->verModalTraspaso = true;
    }

    public function guardarTraspaso()
    {
        $this->validate([
            'idProcesoDestino' => 'required|exists:procesos,id',
            'regTraspaso' => 'required|exists:empleados,numero',
            'pesoSalida' => 'required|numeric',
        ]);
        $empleado = Empleado::where('numero', $this->empTraspaso)->first();
        $registrador = Empleado::where('numero', $this->regTraspaso)->first();
        $movimientoActivo = Bandejasmov::where('IdBandeja', $this->idBandejaTraspaso)
            ->whereNull('fechaHSalida')
            ->latest('id')
            ->first();
        if ($movimientoActivo) {
            $movimientoActivo->update([
                'pesoSalida' => $this->pesoSalida,
                'fechaHSalida' => now()->tz('America/Mexico_City')
            ]);
        }
        Bandejasmov::create([
            'IdBandeja' => $this->idBandejaTraspaso,
            'IdProceso' => $this->idProcesoDestino,
            'IdEmpleado' => $empleado->id,
            'IdRegistrador' => $registrador->id,
            'pesoEntrada' => $this->pesoEntrada,
            'pesoSalida' => $this->pesoSalida,
            'fechaHEntrada' => now()->tz('America/Mexico_City'),
            'fechaHSalida' => now()->tz('America/Mexico_City'),
        ]);
        $this->verModalTraspaso = false;
        $this->reset([
            'idBandejaTraspaso',
            'idProcesoDestino',
            'empTraspaso',
            'pesoEntrada',
            'pesoSalida'
        ]);
    }
    public function cerrarModalTraspaso()
    {
        $this->verModalTraspaso = false;
        $this->reset(['idBandejaTraspaso', 'idProcesoDestino', 'empTraspaso', 'pesoEntrada', 'pesoSalida']);
    }
}