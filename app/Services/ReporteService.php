<?php
namespace App\Services;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\File;
use App\Models\{Obra, Ocompra, Negocio, Empresa, Empresascuenta, Util};
class ReporteService
{
    public $negocio, $datosFacturacion, $cajero;
    public function __construct()
    {
        $this->negocio = Negocio::find(1);
        $this->datosFacturacion = Util::getArrayJS('datosFacturacion');
        $this->cajero = Util::getArrayJS('cajero');
    }
    public function reporteOCompra($IdOCompra)
    {
        $orden = Ocompra::with([
            'Proveedor', 
            'Obra', 
            'Solicito', 
            'ocomprasdets.materialscosto.material', 
            'division'
        ])->findOrFail($IdOCompra);
        $datos = [
            'orden' => $orden,
            'negocio' => $this->negocio,
            'datosFac' => $this->datosFacturacion,
            'cajero' => $this->cajero,
            'proveedorInfo' => Empresa::find($orden->IdProveedor),
            'cuentaProv' => Empresascuenta::find($orden->IdCuentaProv)
        ];
        $pdf = Pdf::loadView('livewire.ocompras.ordenCompraPDF', $datos)
            ->setPaper('letter', 'portrait');
        return $this->guardarServirPdf($pdf, 'oc', 'oCompra' . $IdOCompra . '.pdf');
    }
    private function guardarServirPdf($instanciaPdf, $carpeta, $nombreArchivo)
    {
        $rutaDirectorio = public_path($carpeta);
        if (!File::exists($rutaDirectorio)) {
            File::makeDirectory($rutaDirectorio, 0755, true);
        }
        $rutaCompleta = $rutaDirectorio . '/' . $nombreArchivo;
        $instanciaPdf->save($rutaCompleta);
        return response()->file($rutaCompleta, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . $nombreArchivo . '"'
        ]);
    }

    public function obrasVigentes()
    {
        return Obra::count();
    }    
    public function ocMes()
    {
        return Ocompra::where('estatus', '!=', 'cancelado')
            ->where('fechaHSol', '>=', now()->startOfMonth())
            ->count();
    }
    public function gastoMes()
    {
        return Ocompra::where('estatus','!=','cancelado')
            ->where('fechaHSol','>=',now()->startOfMonth())
            ->select('subtotal','porDescuento')
            ->get()
            ->sum(function($oc){
                $descuento=$oc->subtotal*($oc->porDescuento??0)/100;
                $base=$oc->subtotal-$descuento;
                $iva=$base*$oc->tasaIva;
                return $base+$iva;
            });
    }   
  
    public function obtenerGastos($IdObra = null, $IdDivision = null, $mesAnio = null)
    {
        $query = Ocompra::where('estatus', '!=', 'cancelado')->with(['Obra.Cliente', 'division']);
        if ($IdObra) {
            $query->where('IdObra', $IdObra);
        }
        if ($IdDivision) {
            $query->where('IdDivision', $IdDivision);
        }
        if ($mesAnio) {
            $fecha = \Carbon\Carbon::parse($mesAnio . '-01');
            $query->whereBetween('fechaHSol', [$fecha->copy()->startOfMonth(), $fecha->copy()->endOfMonth()]);
        }
        return $query->get()->map(function($oc) {
            return [
                'fecha' => $oc->fechaHSol,
                'folio' => $oc->id,
                'concepto' => ($oc->Obra->obra ?? '') . ' /' . ($oc->Obra->Cliente->empresa ?? '') . ' -' . ($oc->Solicito->name ?? ''),
                'division' => $oc->division->division ?? 'S/D',
                'color' => $oc->division->adicionales['colorHex'] ?? '#6c757d',
                'monto' => (float)$oc->total
            ];
        })->sortByDesc('fecha');
    }
}