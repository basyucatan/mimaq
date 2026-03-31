<?php
namespace App\Traits\OCompras;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Services\{MovInvService, ReporteService};
use App\Models\{Ocompra, Material, Materialscosto};
trait Acciones
{

    public function cambiarEstatus($id, $nuevo)
    {
        $oCompra = Ocompra::findOrFail($id);
        $this->authorize('cambiarEstatus', [$oCompra, $nuevo]);
        if ($nuevo === Ocompra::EST_ORDENADO) {
            $this->IdCompraPen = $id;
            $this->verModalDestino = true;
            return;
        }
        $oCompra->cambiarEstatus($nuevo);
    }

    public function confirmarDestino()
    {
        if(!$this->IdBodega || !$this->IdDepto) return;
        $oCompra = Ocompra::findOrFail($this->IdCompraPen);
        $datosAdic = is_array($oCompra->adicionales) ? $oCompra->adicionales : json_decode($oCompra->adicionales ?? '[]', true);
        $datosAdic['IdBodega'] = $this->IdBodega;
        $datosAdic['IdDepto'] = $this->IdDepto;
        $oCompra->adicionales = $datosAdic;
        $oCompra->cambiarEstatus(Ocompra::EST_ORDENADO);
        $this->verModalDestino = false;
        $this->reset(['IdCompraPen', 'IdBodega', 'IdDepto']);
        $this->dispatch('sweetalert', \App\Helpers\SweetAlert::mensaje('Esperando Recepción', 1000, 'success'));
    }

    public function elegirMaterial($id)
    {
        $mat = Materialscosto::with(['material.Unidad', 'color', 'Moneda', 'barra'])->find($id);
        if ($mat) {
            $idExiste = collect($this->detalles)->search(fn($det) => $det['IdMatCosto'] == $id);
            if ($idExiste !== false) {
                $this->detalles[$idExiste]['cantidad'] += (float)$this->cantidadMat;
                $this->detalles[$idExiste]['cantidadRec'] += (float)$this->cantidadMat;
            } else {
                $vals = $mat->valores;
                $costoU = (float)$vals['valorURealMXN'];
                $factor = $this->factorIva ?? 1.16;
                $this->detalles[] = [
                    'IdMatCosto' => $mat->id, 
                    'cantidad' => (float)$this->cantidadMat, 
                    'cantidadRec' => (float)$this->cantidadMat, 
                    'costoU' => round($costoU, 4), 
                    'costoURec' => round($costoU, 4),
                    'costoN' => round($costoU * $factor, 4),
                    'nombre' => $mat->material->referencia . " " . $mat->material->material . " " . ($mat->unidad ? $mat->unidad : "pz"),
                    'colorRgba' => $mat->color->colorRgba ?? null, 
                    'unidad' => $mat->unidad, 
                    'simbolo' => $mat->Moneda->simbolo ?? '$', 
                    'abr' => $mat->Moneda->abreviatura ?? 'MXN'
                ];
            }
        }
        $this->keyWordMat = '';
        $this->mats = [];
        $this->cantidadMat = 1;
    }

    public function cerrarRecepcion($id)
    {
        $oCompra = Ocompra::with('ocomprasdets')->findOrFail($id);
        $userRec = auth()->user();
        $this->authorize('gestionar', $oCompra);
        $diferencias = [];
        $adicionales = $oCompra->adicionales;
        foreach ($oCompra->ocomprasdets as $d) {
            $detUsuario = collect($this->detalles)->firstWhere('IdMatCosto', $d->IdMatCosto);
            $cantRec = $detUsuario && isset($detUsuario['cantidadRec']) ? (float)$detUsuario['cantidadRec'] : (float)$d->cantidad;
            $costoRec = $detUsuario && isset($detUsuario['costoURec']) ? (float)$detUsuario['costoURec'] : (float)$d->costoU;
            MovInvService::registrar([
                'tipo' => 'Compra',
                'IdUserOri' => $oCompra->IdUser,
                'IdUserDes' => $userRec->id,
                'IdBodega' => $adicionales['IdBodega'] ?? 1,
                'IdDepto' => $adicionales['IdDepto'] ?? 1,
                'IdMatCosto' => $d->IdMatCosto,
                'cantidad' => $cantRec,
                'valorU' => $costoRec,
                'adicionales' => ['IdOCompra' => $id]
            ]);
            $d->update(['cantidadRec' => $cantRec, 'costoURec' => $costoRec]);
            if (abs((float)$d->cantidad - $cantRec) > 0.00001) {
                $diferencias[] = ['IdMatCosto' => $d->IdMatCosto, 'pedido' => $d->cantidad, 'recibido' => $cantRec];
            }
        }
        $datosAdic = is_array($oCompra->adicionales) ? $oCompra->adicionales : json_decode($oCompra->adicionales ?? '[]', true);
        $datosAdic['diferencias'] = $diferencias;
        $oCompra->update([
            'adicionales' => $datosAdic,
            'estatus' => Ocompra::EST_RECIBIDO,
            'fechaRec' => now(),
            'IdRecibio' => $userRec->id
        ]);
        $this->dispatch('sweetalert', \App\Helpers\SweetAlert::mensaje('Recepción realizada', 1500, 'success'));
    }

    public function crearMaterial()
    {
        $this->validate([
            'nuevoMat.referencia' => 'required|unique:materials,referencia',
            'nuevoMat.material' => 'required|unique:materials,material',
            'nuevoMat.costo' => 'required_without:nuevoMat.neto|nullable|numeric|gt:0',
            'nuevoMat.neto' => 'required_without:nuevoMat.costo|nullable|numeric|gt:0',            
            'nuevoMat.IdMoneda' => 'required',
            'nuevoMat.IdClase' => 'required'
        ]);
        $adicionales = ['autor'=> Auth::user()->name];
        $material = Material::firstOrCreate(
            ['referencia' => mb_strtoupper($this->nuevoMat['referencia'])],
            ['material' => $this->nuevoMat['material'], 
            'IdUnidad' => $this->nuevoMat['IdUnidad'], 
            'IdClase' => $this->nuevoMat['IdClase'], 
            'adicionales' => $adicionales, 
            'IdLinea' => $this->nuevoMat['IdLinea'] ?: 20
            ]
        );
        $costo = Materialscosto::create([
            'IdMaterial' => $material->id,
            'referencia' => mb_strtoupper($this->nuevoMat['referencia']),
            'costo' => $this->nuevoMat['costo'],
            'IdMoneda' => $this->nuevoMat['IdMoneda'],
            'IdColor' => !empty($this->nuevoMat['IdColor']) ? $this->nuevoMat['IdColor'] : null
        ]);
        $this->elegirMaterial($costo->id);
        $this->verNuevoMat = false;
        $this->nuevoMat = [];
    }

    public function toggleAprobar($id)
    {
        $oc = Ocompra::find($id);
        $nuevoEstatus = $oc->estatus === 'aprobado' ? 'edicion' : 'aprobado';
        $oc->update(['estatus' => $nuevoEstatus]);
        $this->dispatch('sweetalert', \App\Helpers\SweetAlert::mensaje('✅ Ok', 400, 'success'));
    } 
    public function toggleNuevoMaterial()
    {
        $this->verNuevoMat = !$this->verNuevoMat;
        if ($this->verNuevoMat) {
            $this->nuevoMat = ['material' => $this->keyWordMat, 'referencia' => '', 'IdUnidad' => 1, 'IdMoneda' => 1, 'IdClase' => 7, 'IdLinea' => 20, 'costo' => '', 'neto' => '', 'IdColor' => null];
        }
        $this->keyWordMat = null;
    }
    public function imprimir($id)
    {
        if (!$id) return;
        return app(ReporteService::class)->reporteOCompra($id);        
    //     if (!$id) return;
    //     $orden = Ocompra::with(['Proveedor', 'Obra', 'Solicito', 
    //         'ocomprasdets.materialscosto.material', 'division'])->find($id);
    //     $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('livewire.ocompras.ordenCompraPDF', [
    //         'orden' => $orden,
    //         'negocio' => Negocio::find(1),
    //         'condsPago' => $this->condsPago,
    //         'condsFlete' => $this->condsFlete,
    //         'datosFac' => Util::getArrayJS('datosFacturacion'),
    //         'cajero' => Util::getArrayJS('cajero'),
    //         'proveedorInfo' => Empresa::find($orden->IdProveedor),
    //         'cuentaProv' => Empresascuenta::find($orden->IdCuentaProv)
    //     ])->setPaper('letter', 'portrait');
    //     $nombreArchivo = 'oCompra' . $id . '.pdf';
    //     $directorioPath = public_path('oc');
    //     if (!\Illuminate\Support\Facades\File::exists($directorioPath)) {\Illuminate\Support\Facades\File::makeDirectory($directorioPath, 0755, true);}
    //     $pdfPath = $directorioPath . '/' . $nombreArchivo;
    //     $pdf->save($pdfPath);
    //     return response()->file($pdfPath, ['Content-Type' => 'application/pdf', 
    //         'Content-Disposition' => 'inline; filename="' . $nombreArchivo . '"']);
    // 
    }
}