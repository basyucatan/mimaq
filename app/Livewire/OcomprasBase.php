<?php
namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Traits\OCompras\{Consultas, Acciones};
use Illuminate\Support\Facades\DB;
use App\Models\{User, Ocompra, Util};

abstract class OcomprasBase extends Component
{
    use WithPagination, Consultas, Acciones;

    public $verModalOcompra = false, $verModalDestino = false, $verNuevoMat = false, $verNuevaObra = false;
    public $selected_id, $oCompra, $IdCompraPen, $IdBodega=1, $IdDepto=1;
    public $keyWord, $keyWordMat, $keyWordProv, $keyWordCte;
    public $IdDivision, $IdObra, $IdProveedor, $IdCuentaProv;
    public $IdCliente = 1, $IdCondPago = 1, $IdCondFlete = 1, $factorIva=1.16;
    public $concepto, $fechaHSol, $porDescuento = 0, $estatus = 'edicion';
    public $divisions = [], $provs = [], $obras = [], $clientes = [], $clases = [], $marcas = [],
        $lineas = [], $colors = [], $unidads = [], $monedas = [], $bodegas = [], $deptos = [], 
        $cuentas = [], $condsPago = [], $condsFlete = [], $cantidadMat = 1;
    public $detalles = [], $nuevoMat = [], $nuevaEmpresa = [], $adicionales = [];

    public function mount()
    {
        $this->cargarArrays();
        if(!$this->IdDivision){
            $user = User::find(auth()->id());
            $this->IdDivision = $user->Division->id ?? 1;
        }
        $this->factorIva = 1 + (float)Util::getArrayJS('datosFacturacion')[1]['factorIva'];
    }
    public function cargarArrays()
    {
        $this->clases = Util::getArray('clases');
        $this->marcas = Util::getArray('marcas');
        $this->lineas = Util::getArray('lineas');
        $this->colors = Util::getArray('colors');
        $this->unidads = Util::getArray('unidads');
        $this->monedas = Util::getArray('monedas', 'abreviatura');
        $this->divisions = Util::getArray('divisions');
        $this->bodegas = Util::getArray('divsbodegas', 'bodega');
        $this->deptos = Util::getArray('deptos');        
        $this->cuentas = Util::getArray('empresascuentas', 'cuenta');
        $this->clientes = DB::table('empresas')->where('tipo', 'cliente')->pluck('empresa', 'id');
        $this->condsPago = Util::getArrayJS('condicionesPago', 'condicion');
        $this->condsFlete = Util::getArrayJS('condicionesFlete', 'condicion');
        $this->elegirCliente($this->IdCliente, 'gas');        
    }

    protected function aplicarRender($estatusVisibles, $nombreVista)
    {
        $key = '%' . $this->keyWord . '%';
        $buscarId = ltrim($this->keyWord, '0');
        $ocompras = Ocompra::with(['Solicito', 'Proveedor', 'Obra.Cliente'])
            ->whereIn('estatus', $estatusVisibles)
            ->where(function ($query) use ($key, $buscarId) {
                $query->where('concepto', 'LIKE', $key)
                    ->when(is_numeric($buscarId) && $buscarId !== '', function ($q) use ($buscarId) {
                        $q->orWhere('id', $buscarId);
                    })
                    ->orWhereHas('Proveedor', function ($q) use ($key) {
                        $q->where('empresa', 'LIKE', $key);
                    })
                    ->orWhereHas('Obra', function ($q) use ($key) {
                        $q->where('obra', 'LIKE', $key)
                        ->orWhereHas('Cliente', function ($q2) use ($key) {
                            $q2->where('empresa', 'LIKE', $key);
                        });
                    });
            })
            ->orderBy('id', 'desc')
            ->paginate(15);
        return view($nombreVista, [
            'ocompras' => $ocompras,
            'mats' => $this->filtroMats()
        ]);
    }

    public function updatedKeyWord() { $this->resetPage(); }
    public function updatedKeyWordProv() { $this->IdProveedor = null; $this->provs = $this->filtroProvs(); }
    public function updatedKeyWordCte($valor) { $this->IdCliente = null; $this->clientes = $this->filtroClientes(); }

    public function updatedDetalles($valor, $nombre)
    {
        $partes = explode('.', $nombre);
        $id = $partes[0];
        $campo = $partes[1];
        if ($campo === 'costoU' || $campo === 'costoURec') {
            $this->detalles[$id]['costoN'] = (float)$valor * $this->factorIva;
        } elseif ($campo === 'costoN') {
            $this->detalles[$id]['costoU'] = (float)$valor / $this->factorIva;
            $this->detalles[$id]['costoURec'] = (float)$valor / $this->factorIva;
        }
    }
    public function create()
    {
        $this->resetInput();
        $this->detalles = [];
        $user = User::find(auth()->id());
        $this->IdDivision = $user?->Division->id ?? 1;
        if (empty($this->fechaHSol)) {
            $this->fechaHSol = now()->tz('America/Mexico_City')->format('Y-m-d\TH:i');
        }
        $this->verModalOcompra = true;
    }

    public function edit($id)
    {
        $this->selected_id = $id;
        $oc = Ocompra::with([
            'ocomprasdets.materialscosto.material.Unidad', 
            'ocomprasdets.materialscosto.color', 
            'ocomprasdets.materialscosto.Moneda', 
            'Proveedor', 
            'obra.cliente'
        ])->findOrFail($id);

        $this->oCompra = $oc;
        $this->fill($oc->toArray());
        $this->keyWordProv = $oc->Proveedor->empresa ?? '';
        $this->IdCliente = $oc->obra->IdEmpresa ?? null;
        $this->obras = DB::table('obras')
            ->where('IdEmpresa', $this->IdCliente)
            ->where('estatus', 'vigente')
            ->pluck('obra', 'id');
        $this->keyWordCte = $oc->obra->Cliente->empresa ?? '';
        $this->detalles = [];
        foreach ($oc->ocomprasdets as $d) {
            $m = $d->materialscosto;
            $color = $m->Color;
            $this->detalles[] = [
                'IdMatCosto' => $d->IdMatCosto,
                'cantidad' => (float) $d->cantidad,
                'cantidadRec' => (float) $d->cantidadRec ?? $d->cantidad,
                'costoU' => round($d->costoU,4),
                'costoURec' => (float) $d->costoURec ?? $d->costoU,
                'costoN' => round($d->costoU * (1 + $oc->tasaIva), 4),
                'nombre' => ($m->material->referencia ?? '') . " " . ($m->material->material ?? ''),
                'colorRgba' => $color->colorRgba ?? '#fff',
                'simbolo' => $m->Moneda->simbolo ?? '$',
                'unidad' => $m->unidad ?? 'PZ'
            ];
        }
        $this->verModalOcompra = true;
    }

    public function save()
    {
        $this->validate([
            'IdDivision' => 'required',
            'IdObra' => 'required',
            'IdProveedor' => 'required',
            'detalles' => 'required|array|min:1'
        ]);
        DB::transaction(function () {
            $oc = Ocompra::updateOrCreate(
                ['id' => $this->selected_id],
                [
                    'IdDivision' => $this->IdDivision,
                    'IdProveedor' => $this->IdProveedor,
                    'IdCuentaProv' => $this->IdCuentaProv,
                    'IdUser' => $this->IdUser ?? auth()->id(),
                    'IdObra' => $this->IdObra,
                    'IdCondPago' => $this->IdCondPago,
                    'IdCondFlete' => $this->IdCondFlete,
                    'fechaHSol' => $this->fechaHSol,
                    'porDescuento' => $this->porDescuento,
                    'subtotal' => (float) $this->subtotal,
                    'concepto' => mb_convert_case($this->concepto, MB_CASE_TITLE, "UTF-8"),
                    'estatus' => $this->estatus,
                ]
            );
            $idsEnFormulario = collect($this->detalles)->pluck('IdMatCosto')->toArray();
            $oc->ocomprasdets()->whereNotIn('IdMatCosto', $idsEnFormulario)->delete();
            foreach ($this->detalles as $det) {
                $datos = [
                    'IdMatCosto' => $det['IdMatCosto']
                ];
                if ($oc->estatus === 'ordenado' || $oc->estatus === 'recibido') {
                    $registroExistente = $oc->ocomprasdets()->where('IdMatCosto', $det['IdMatCosto'])->first();
                    $updateData = [
                        'cantidadRec' => $det['cantidadRec'] ?? $det['cantidad'],
                        'costoURec'   => $det['costoURec'] ?? $det['costoU']
                    ];
                    if (!$registroExistente) {
                        $updateData['cantidad'] = 0;
                        $updateData['costoU'] = 0;
                    }
                    $oc->ocomprasdets()->updateOrCreate(['IdMatCosto' => $det['IdMatCosto']], $updateData);
                } else {
                    $oc->ocomprasdets()->updateOrCreate(
                        ['IdMatCosto' => $det['IdMatCosto']],
                        [
                            'cantidad' => $det['cantidad'],
                            'costoU'   => $det['costoU'],
                            'costoN'   => $det['costoN']
                        ]
                    );
                }
            }
        });
        $this->cancel();
    }

    public function removeDetalle($index)
    {
        unset($this->detalles[$index]);
        $this->detalles = array_values($this->detalles);
    }

    public function getSubtotalProperty()
    {
        return collect($this->detalles)->sum(function($d) {
            $c = ($this->oCompra?->estatus === 'ordenado') ? ($d['cantidadRec'] ?? 0) : $d['cantidad'];
            return (float)$c * (float)($d['costoU'] ?? 0);
        });
    }

    private function resetInput() 
    { 
        $this->resetExcept([
            'selected_id', 'factorIva', 'IdDivision', 'unidads', 'monedas', 'detalles',
            'obras', 'marcas', 'lineas', 'clases', 'colors', 'divisions', 'condsPago', 'condsFlete', 'bodegas', 'deptos'
        ]);
        $this->nuevoMat = [];
        $this->nuevaEmpresa = [];
    }    
    public function cancel() { $this->verModalOcompra = false; $this->resetInput();}
    public function destroy($id)
    {
        $oc = Ocompra::find($id);
        if ($oc && $oc->estatus === 'edicion') { $oc->delete(); }
    }     
}