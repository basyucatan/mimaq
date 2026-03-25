<?php
namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Traits\OCompras\{Consultas, Acciones};
use Illuminate\Support\Facades\DB;
use App\Models\{User, Util, Ocompra};

abstract class OcomprasBase extends Component
{
    use WithPagination, Consultas, Acciones;

    protected $paginationTheme = 'bootstrap';

    public $verModalOcompra = false, $verModalDestino = false, $verNuevoMat = false, $verNuevaObra = false;
    public $IdDivision, $IdProveedor, $IdCuentaProv, $IdUser, $IdAprobo, $IdObra;
    public $IdCliente = 1, $IdCondPago = 1, $IdCondFlete = 1;
    public $keyWord, $keyWordMat, $keyWordProv, $keyWordCte, $oCompra;
    public $cantidadMat = 1, $IdLinea, $IdColor, $estatus = 'edicion';
    public $IdCompraPen = 1, $IdBodega = 1, $IdDepto = 1;
    public $fechaHSol, $porDescuento = 0, $concepto, $selected_id;

    public $divisions = [], $provs = [], $obras = [], $clientes = [], $clases = [], $marcas = [], $lineas = [], $colors = [], $unidads = [], $monedas = [], $bodegas = [], $deptos = [], $cuentas = [], $condsPago = [], $condsFlete = [];
    public $detalles = [], $nuevoMat = [], $nuevaEmpresa = [], $adicionales = [];

    public function mount()
    {
        if (method_exists($this, 'cargarArrays')) { 
            $this->cargarArrays(); 
        }
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

    /* Hooks de Actualización */

    public function updatedKeyWord() { $this->resetPage(); }

    public function updatedKeyWordProv() 
    { 
        $this->IdProveedor = null; 
        $this->provs = $this->filtroProvs(); 
    }

    public function updatedKeyWordCte($valor) 
    { 
        $this->IdCliente = null; 
        $this->clientes = $this->filtroClientes(); 
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

    public function create()
    {
        $this->resetInput();
        $this->detalles = [];
        if (empty($this->fechaHSol)) {
            $this->fechaHSol = now()->tz('America/Mexico_City')->format('Y-m-d\TH:i');
        }
        $this->verModalOcompra = true;
    }

    /* Métodos de Propiedades y Acciones */

    public function getFactorIvaProperty() { return 1 + Util::getArrayJS('datosFacturacion')[1]['factorIva']; }

    public function getSubtotalProperty() 
    { 
        return collect($this->detalles)->sum(fn($d) => (float)$d['cantidad'] * (float)($d['costoN'] ?? 0));
    }

    public function getTotalProperty()
    {
        $descuento = $this->subtotal * ($this->porDescuento / 100);
        return round($this->subtotal - $descuento, 2);
    }

    public function updatedDetalles($valor, $nombre)
    {
        $partes = explode('.', $nombre);
        $conteo = count($partes);
        if ($conteo < 2) return;
        $idx = ($conteo === 3) ? $partes[1] : $partes[0];
        $campo = ($conteo === 3) ? $partes[2] : $partes[1];
        if ($campo === 'costoU' || $campo === 'costoURec') {
            $this->detalles[$idx]['costoN'] = (float)$valor * $this->factorIva;
        } elseif ($campo === 'costoN') {
            $this->detalles[$idx]['costoU'] = (float)$valor / $this->factorIva;
            $this->detalles[$idx]['costoURec'] = (float)$valor / $this->factorIva;
            $this->detalles[$idx]['costoN'] = (float)$valor;
        } elseif ($campo === 'cantidadRec') {
            $this->detalles[$idx]['cantidadRec'] = (float)$valor;
        }
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
        'detalles' => 'required|array|min:1'
    ]);
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
            'subtotal' => number_format($this->subtotal / $this->factorIva, 5, '.', ''),
            'concepto' => mb_convert_case(mb_strtolower($this->concepto), MB_CASE_TITLE, "UTF-8"),
            'estatus' => $this->estatus,
            'adicionales' => $this->adicionales
        ]
    );
    // 1. Obtener IDs de materiales que vienen del formulario para saber cuáles conservar
    $idsPresentes = collect($this->detalles)->pluck('IdMatCosto')->toArray();

    // 2. Eliminar de la BD los que ya no están en la lista del formulario
    $oc->ocomprasdets()->whereNotIn('IdMatCosto', $idsPresentes)->delete();

    // 3. Procesar cada detalle
    foreach ($this->detalles as $det) {
        // Buscamos si el registro ya existe en la BD para esta orden
        $detalleBd = DB::table('ocomprasdets')
            ->where('IdOCompra', $oc->id)
            ->where('IdMatCosto', $det['IdMatCosto'])
            ->first();

        if ($this->estatus === 'recibido' || $this->estatus === 'ordenado') {
            if ($detalleBd) {
                // Si ya existe, solo actualizamos lo recibido
                DB::table('ocomprasdets')
                    ->where('id', $detalleBd->id)
                    ->update([
                        'cantidadRec' => $det['cantidadRec'] ?? $det['cantidad'],
                        'costoURec' => $det['costoURec'] ?? $det['costoU']
                    ]);
            } else {
                // Si es NUEVO pero el estatus ya es recibido/ordenado
                // Insertamos con original en 0 según tu requerimiento
                $oc->ocomprasdets()->create([
                    'IdMatCosto' => $det['IdMatCosto'],
                    'cantidad' => 0,
                    'costoU' => 0,
                    'costoN' => 0,
                    'cantidadRec' => $det['cantidadRec'] ?? $det['cantidad'],
                    'costoURec' => $det['costoURec'] ?? $det['costoU']
                ]);
            }
        } else {
            // Estatus 'edicion' o similar: Sincronización normal de costos originales
            $oc->ocomprasdets()->updateOrCreate(
                ['IdMatCosto' => $det['IdMatCosto']],
                [
                    'cantidad' => $det['cantidad'],
                    'costoU' => $det['costoU'],
                    'costoN' => $det['costoN']
                ]
            );
        }
    }

    $this->cancel();
}
    public function edit($id)
    {
        $this->selected_id = $id;
        $this->detalles = [];
        $oc = Ocompra::with(['ocomprasdets.materialscosto.material.Unidad', 
            'ocomprasdets.materialscosto.color', 'ocomprasdets.materialscosto.Moneda', 
            'Proveedor', 'obra.cliente'])->findOrFail($id);
        $this->oCompra = $oc;
        $this->fill($oc->toArray());
        $this->keyWordProv = $oc->Proveedor->empresa ?? '';
        $this->IdCliente = $oc->obra->IdEmpresa ?? null;
        $this->IdBodega = $oc->adicionales['IdBodega'] ?? null;
        $this->IdDepto = $oc->adicionales['IdDepto'] ?? null;
        $this->elegirCliente($this->IdCliente, $oc->obra->Cliente->empresa ?? '');
        $this->IdObra = $oc->IdObra;

        foreach ($oc->ocomprasdets as $d) {
            $m = $d->materialscosto;
            $this->detalles[] = [
                'IdMatCosto' => $d->IdMatCosto, 
                'cantidad' => $d->cantidad, 
                'cantidadRec' => $d->cantidadRec ?? $d->cantidad,
                'costoU' => $d->costoU, 
                'costoURec' => $d->costoURec ?? $d->costoU, 
                'costoN' => $d->costoU * $this->factorIva,
                'nombre' => $m->material->referencia . " " . $m->material->material, 
                'precioOrig' => $m->valores['valorUReal'] ?? 0,
                'simbolo' => $m->Moneda->simbolo ?? '$', 
                'abr' => $m->Moneda->abreviatura ?? 'MXN', 
                'colorRgba' => $m->color->colorRgba ?? null, 
                'unidad' => $m->unidad
            ];
        }
        $this->verModalOcompra = true;
    }

    public function cancel() { $this->verModalOcompra = false; $this->selected_id = null; }

    protected function aplicarRender($estatusVisibles, $view)
    {
        if(!$this->IdDivision){
            $this->IdDivision = User::findOrFail(auth()->id())->Division->id ?? 1;
        } 
        $key = '%' . $this->keyWord . '%';
        $buscarId = ltrim($this->keyWord, '0');
        $ocompras = Ocompra::where(function ($query) use ($key, $buscarId) {
                $query->where('concepto', 'LIKE', $key)
                ->when(is_numeric($buscarId) && $buscarId !== '', function ($q) use ($buscarId) {
                    $q->orWhere('id', $buscarId);
                })
                ->orWhereHas('Solicito', function ($q) use ($key) { $q->where('name', 'LIKE', $key); })
                ->orWhereHas('Proveedor', function ($q) use ($key) { $q->where('empresa', 'LIKE', $key)->orWhere('razonSocial', 'LIKE', $key); })
                ->orWhereHas('Obra', function ($q) use ($key) {
                    $q->where('obra', 'LIKE', $key)->orWhereHas('Cliente', function ($q2) use ($key) { 
                        $q2->where('empresa', 'LIKE', $key); 
                    });
                });
            })
            ->whereIn('estatus', $estatusVisibles)
            ->orderBy('fechaHSol','desc')->orderBy('id','desc')->paginate(12);

        return view($view, [
            'ocompras' => $ocompras, 
            'mats' => $this->filtroMats()
        ]);
    }
}