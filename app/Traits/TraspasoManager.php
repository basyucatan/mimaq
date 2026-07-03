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
    public function escanear()
    {
        if (empty($this->selected_id)) {
            return;
        }
        $this->iniciarTraspaso($this->selected_id);
        $this->reset('selected_id');
    }
public function iniciarTraspaso($id)
{
    $this->resetInput();
    $this->idBandejaTraspaso = $id;
    $bandeja = Bandeja::findOrFail($id);
    if ($bandeja->IdFacturaExport) {
        $this->alerta('⛔ Bandeja ya integrada a Exportación.', 'warning');
        return;
    }
    $this->codigoBandeja = $bandeja->codigoBandeja;
    $ultimoMov = $bandeja->ultimoMovimiento;
    $this->esSalida = $ultimoMov && !$ultimoMov->fechaHSalida;
    if ($this->esSalida) {
        $this->pesoEntrada = $ultimoMov->pesoEntrada;
        $this->idProcesoDestino = $ultimoMov->IdProceso;
    } else {
        $this->pesoEntrada = $ultimoMov?->pesoSalida;
    }
    $this->verModalTraspaso = true;
}

public function guardarTraspaso()
{
    $ultimoMov = Bandejasmov::where('IdBandeja', $this->idBandejaTraspaso)
        ->latest('id')
        ->first();

    $esPrimerMovimiento = is_null($ultimoMov);
    $esNuevaEntrada = $esPrimerMovimiento || $ultimoMov->fechaHSalida;

    $reglas = [
        'regTraspaso' => 'required|exists:empleados,numero',
    ];

    if ($esNuevaEntrada) {
        $reglas['idProcesoDestino'] = 'required|exists:procesos,id';
        $reglas['pesoEntrada'] = 'required|numeric|gt:0';
    } else {
        $reglas['empTraspaso'] = 'required|exists:empleados,numero';
        $reglas['pesoSalida'] = 'required|numeric|gt:0';
    }

    $this->validate($reglas);

    $registrador = Empleado::where('numero', $this->regTraspaso)->first();

    if ($esNuevaEntrada) {
        Bandejasmov::create([
            'IdBandeja' => $this->idBandejaTraspaso,
            'IdProceso' => $this->idProcesoDestino,
            'IdEmpleado' => null,
            'IdRegistrador' => $registrador->id,
            'pesoEntrada' => $this->pesoEntrada,
            'pesoSalida' => null,
            'fechaHEntrada' => now()->tz('America/Mexico_City'),
            'fechaHSalida' => null,
        ]);
    } else {
        $empleado = Empleado::where('numero', $this->empTraspaso)->first();

        $ultimoMov->update([
            'IdEmpleado' => $empleado->id,
            'IdRegistrador' => $registrador->id,
            'pesoSalida' => $this->pesoSalida,
            'fechaHSalida' => now()->tz('America/Mexico_City'),
        ]);
    }

    $this->verModalTraspaso = false;
    $this->resetInput();
}
    public function resetInput(){
        $this->reset([
            'idProcesoDestino','empTraspaso','regTraspaso','pesoEntrada','pesoSalida',
            'fechaHEntrada','fechaHSalida'
        ]);
    }
    public function cerrarModalTraspaso()
    {
        $this->verModalTraspaso = false;
        $this->reset(['idBandejaTraspaso', 'idProcesoDestino', 'empTraspaso', 'pesoEntrada', 'pesoSalida']);
    }
}