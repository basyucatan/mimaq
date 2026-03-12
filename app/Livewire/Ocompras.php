<?php
namespace App\Livewire;
use Livewire\Component;
use Livewire\WithPagination;
use App\Traits\OComprasGestion;
use Illuminate\Support\Facades\DB;
use App\Models\{User, Util, Ocompra, Materialscosto, Negocio, Empresa, Empresascuenta};
class Ocompras extends Component
{
    use WithPagination, OComprasGestion;
    protected $paginationTheme = 'bootstrap';
    public $verModalOcompra = false, $verNuevoMat = false, $verNuevaObra = false;
    public $IdDivision, $IdProveedor, $IdCuentaProv, $IdUser, $IdAprobo, $IdObra,
        $IdCliente = 1, $IdCondPago = 1, $IdCondFlete = 1, $selected_id, 
        $keyWord, $keyWordMat, $keyWordProv, $keyWordCte,
        $cantidadMat = 1, $IdMarca, $IdLinea, $IdColor, 
        $fechaHSol, $porDescuento = 0, $concepto, $estatus = 'edicion', $adicionales;
    public $divisions = [], $provs = [], $obras = [], $clientes = [], $clases = [], 
        $marcas = [], $lineas = [], $colors = [], $unidads = [], $monedas = [], 
        $cuentas=[],$condsPago=[], $condsFlete=[];
    public $detalles = [], $nuevoMat = [], $nuevaEmpresa = [], $nuevaObra = [];

    public function mount()
    {
        $this->clases = Util::getArray('clases');
        $this->marcas = Util::getArray('marcas');
        $this->lineas = Util::getArray('lineas');
        $this->colors = Util::getArray('colors');
        $this->unidads = Util::getArray('unidads');
        $this->monedas = Util::getArray('monedas', 'abreviatura');
        $this->divisions = Util::getArray('divisions');
        $this->cuentas = Util::getArray('empresascuentas', 'cuenta');
        $this->clientes = DB::table('empresas')->where('tipo', 'cliente')
				->pluck('empresa', 'id');
        $this->condsPago = Util::getArrayJS('condicionesPago','condicion');
        $this->condsFlete = Util::getArrayJS('condicionesFlete','condicion');                
        $this->elegirCliente($this->IdCliente, 'gas');
    }	

    public function elegirCliente($id, $cliente)
    {
        $this->IdCliente = $id;
        $this->keyWordCte = $cliente;
        $this->clientes = [];
        $this->IdObra = null;
        $this->obras = DB::table('obras')->where('IdEmpresa', $this->IdCliente)
            ->where('estatus', 'vigente')->pluck('obra', 'id'); 
    }    
	public function elegirProv($id, $empresa)
	{
		$this->IdProveedor = $id;
		$this->keyWordProv = $empresa;
		$this->provs = [];
		$this->IdCuentaProv = null;
        $this->cuentas = DB::table('empresascuentas')->where('IdEmpresa', $id)
            ->select(DB::raw("CONCAT(banco, ' - ', cuenta) as cuenta"), 'id')
            ->pluck('cuenta', 'id');           
	}    
    public function elegirMarca(){
        $this->IdLinea = null;
        $this->lineas = [];
        if(! $this->nuevoMat['IdMarca']) return;
        $this->lineas = DB::table('lineas')->where('IdMarca', $this->nuevoMat['IdMarca'])
            ->pluck('linea', 'id');
    }
    public function elegirLinea(){
        $IdColorable = DB::table('lineas')->where('id',  $this->nuevoMat['IdLinea'])->value('IdColorable');
        $this->colors = DB::table('colors')->where('IdColorable', $IdColorable)
            ->pluck('color', 'id');
        $this->IdColor = null;
    }    

    public function toggleAprobar($id){
        $oc = Ocompra::find($id);
        $nuevoEstatus = $oc->estatus === 'aprobado' ? 'edicion' : 'aprobado';
        $oc->update(['estatus' => $nuevoEstatus]);
        $this->dispatch('sweetalert', \App\Helpers\SweetAlert::mensaje('✅ Ok', 400, 'success'));
    }    
    
    public function getSubtotalProperty()
    {
        return collect($this->detalles)->sum(fn($d) => (float)$d['cantidad'] * (float)$d['costoU']);
    }
    public function getTotalProperty()
    {
        $descuento = $this->subtotal * ($this->porDescuento / 100);
        return round($this->subtotal - $descuento, 2);
    }
	public function updatedKeyWord() { $this->resetPage(); }
	public function updatedKeyWordProv(){
		$this->IdProveedor = null;
		$this->provs = $this->filtroProvs();
	}	
    public function updatedKeyWordCte($valor)
    {
        $this->IdCliente = null;
        $this->clientes = $this->filtroClientes();
    }

    public function toggleNuevoMaterial()
    {
        $this->verNuevoMat = !$this->verNuevoMat;

        if ($this->verNuevoMat) {
            $this->nuevoMat = [
                'material' => $this->keyWordMat,
                'referencia' => '',
                'IdUnidad' => 1,
                'IdMoneda' => 1,
                'IdClase' => 7,
                'IdLinea' => 20,
                'costo' => '',
                'IdColor' => null
            ];
        }

        $this->keyWordMat = null;
    }
    public function elegirMaterial($id)
    {
        $mat = Materialscosto::with(['material.Unidad', 'color', 'Moneda', 'barra'])->find($id);
        if ($mat) {
            $vals = $mat->valores;
            $this->detalles[] = [
                'IdMatCosto' => $mat->id,
                'cantidad' => $this->cantidadMat,
                'costoU' => $vals['valorURealMXN'],
                'nombre' => $mat->material->referencia ." ". $mat->material->material ." ". ($mat->unidad ? $mat->unidad : "pz"),
                'colorRgba' => $mat->color->colorRgba ?? null,
                'unidad' => $mat->unidad,
                'precioOrig' => $vals['valorUReal'],
                'simbolo' => $mat->Moneda->simbolo ?? '$',
                'abr' => $mat->Moneda->abreviatura ?? 'MXN'
            ];
        }
        $this->keyWordMat = '';
        $this->cantidadMat = 1;
    }    
    public function removeDetalle($index)
    {
        unset($this->detalles[$index]);
        $this->detalles = array_values($this->detalles);
    }
    public function save()
    {
        $this->validate([
            'IdDivision' => 'required', 
            'IdObra' => 'required', 
            'concepto' => 'required', 
            'IdProveedor' => 'required', 
            'detalles' => 'required|array|min:1']);
        $oc = Ocompra::updateOrCreate(
            ['id' => $this->selected_id],
            [
                'IdDivision' => $this->IdDivision,
                'IdProveedor' => $this->IdProveedor,
                'IdCuentaProv' => $this->IdCuentaProv,
                'IdUser' => auth()->id(),
                'IdObra' => $this->IdObra,
                'IdCondPago' => $this->IdCondPago,
                'IdCondFlete' => $this->IdCondFlete,
                'fechaHSol' => $this->fechaHSol,
                'porDescuento' => $this->porDescuento,
                'subtotal' => round($this->subtotal, 2),
                'concepto' => mb_convert_case(mb_strtolower($this->concepto), MB_CASE_TITLE, "UTF-8"),
                'estatus' => $this->estatus,
                'adicionales' => $this->adicionales
            ]
        );
        $oc->ocomprasdets()->delete();
        foreach ($this->detalles as $det) {
            $oc->ocomprasdets()->create([
                'IdMatCosto' => $det['IdMatCosto'],
                'cantidad' => $det['cantidad'],
                'costoU' => $det['costoU']
            ]);
        }
        $this->cancel();
    }
    public function edit($id)
    {
        $this->resetInput();
        $this->selected_id = $id;
        $oc = Ocompra::with(['ocomprasdets.materialscosto.material.Unidad', 
            'ocomprasdets.materialscosto.color', 
            'ocomprasdets.materialscosto.Moneda', 
            'Proveedor', 'obra.cliente'])->findOrFail($id);
        $this->fill($oc->toArray());
        $this->keyWordProv = $oc->Proveedor->empresa ?? '';
        $this->IdCliente = $oc->obra->IdEmpresa ?? null;
        $this->keyWordCte = $oc->obra->empresa->empresa ?? '';
        foreach ($oc->ocomprasdets as $d) {
            $m = $d->materialscosto;
            $this->detalles[] = [
                'IdMatCosto' => $d->IdMatCosto,
                'cantidad' => $d->cantidad,
                'costoU' => $d->costoU,
                'nombre' => $m->material->referencia ." ".$m->material->material ." ". ($m->unidad ? $m->unidad : "pz"),
                'colorRgba' => $m->color->colorRgba ?? null,
                'unidad' => $m->unidad,
                'precioOrig' => $m->valores['valorUReal'] ?? 0,
                'simbolo' => $m->Moneda->simbolo ?? '$',
                'abr' => $m->Moneda->abreviatura ?? 'MXN'
            ];
        }
        $this->verModalOcompra = true;
    }
    public function create()
    {
        $this->resetInput();
        if (empty($this->fechaHSol)) {
            $this->fechaHSol = now()->tz('America/Mexico_City');
        }        
        $this->verModalOcompra = true;
    }
    public function cancel() { $this->verModalOcompra = false; $this->resetInput(); }
    private function resetInput() { $this->reset(['selected_id', 'IdDivision', 'IdProveedor', 'IdUser', 'IdObra', 'IdCliente', 'fechaHSol', 'porDescuento', 'concepto', 'estatus', 'adicionales', 'detalles', 'keyWordMat', 'keyWordProv', 'keyWordCte', 'cantidadMat', 'verNuevoMat']); $this->resetNuevoMat(); $this->resetNuevaEmpresa(); }
    private function resetNuevoMat() {$this->nuevoMat = [];}
    private function resetNuevaEmpresa() { $this->nuevaEmpresa =[];}
    public function render() 
    { 
        if(!$this->IdDivision){
			$user = User::findorfail(auth()->id());
			$this->IdDivision = $user->Division->id ?? 1;
		} 
        $key = '%'.$this->keyWord.'%';
        $ocompras = Ocompra::where(function ($query) use ($key) {
                $query->where('concepto', 'LIKE', $key)
                ->orWhereHas('Solicito', function ($q) use ($key) {
                    $q->where('name', 'LIKE', $key);
                })
                ->orWhereHas('Proveedor', function ($q) use ($key) {
                    $q->where('empresa', 'LIKE', $key)
                    ->orWhere('razonSocial', 'LIKE', $key);
                })
                ->orWhereHas('Obra', function ($q) use ($key) {
                    $q->where('obra', 'LIKE', $key)
                    ->orWhereHas('Cliente', function ($q2) use ($key) {
                        $q2->where('empresa', 'LIKE', $key);
                    });
                });

            })
            ->orderBy('fechaHSol','desc')
            ->paginate(10);
        return view('livewire.ocompras.view', [
            'ocompras' => $ocompras,
            'mats' => $this->filtroMats(),
        ]); 
    }
    public function imprimir($id)
    {
        if (!$id) return;
        $orden = \App\Models\Ocompra::with(['Proveedor', 'Obra', 'Solicito', 
            'ocomprasdets.materialscosto.material', 'division'])->find($id);
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('livewire.ocompras.ordenCompraPDF', [
            'orden' => $orden,
            'negocio' =>  Negocio::find(1),
            'condsPago' => $this->condsPago,
            'condsFlete' => $this->condsFlete,
            'datosFac' => Util::getArrayJS('datosFacturacion'),
            'cajero' => Util::getArrayJS('cajero'),
            'proveedorInfo' => Empresa::find($orden->IdProveedor),
            'cuentaProv' => Empresascuenta::find($orden->IdCuentaProv)
        ])->setPaper('letter', 'portrait');
        $nombreArchivo = 'oCompra' . $id . '.pdf';
        $directorioPath = public_path('oc');
        if (!\Illuminate\Support\Facades\File::exists($directorioPath)) {
            \Illuminate\Support\Facades\File::makeDirectory($directorioPath, 0755, true);
        }
        $pdfPath = $directorioPath . '/' . $nombreArchivo;
        $pdf->save($pdfPath);
        return response()->file($pdfPath, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . $nombreArchivo . '"'
        ]);
    }   
    public function destroy($id) { if ($id) Ocompra::where('id', $id)->delete(); }
}