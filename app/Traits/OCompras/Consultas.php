<?php
namespace App\Traits\OCompras;
use Illuminate\Support\Facades\DB;
use App\Models\{OCompra, Materialscosto, Empresa};
trait Consultas
{
    public function filtroMats()
    {
        if (strlen($this->keyWordMat) < 2) return [];
        $key = '%' . $this->keyWordMat . '%';
        return Materialscosto::with(['material.Unidad', 'color', 'Moneda', 'barra'])
            ->join('materials', 'materialscostos.IdMaterial', '=', 'materials.id')
            ->where(function ($query) use ($key) {
                $query->whereHas('material', function ($q) use ($key) {
                    $q->where('material', 'LIKE', $key)
                    ->orWhere('referencia', 'LIKE', $key);
                })
                ->orWhere('materialscostos.referencia', 'LIKE', $key);
            })
            ->orderBy('materials.material')
            ->select('materialscostos.*')
            ->limit(100)
            ->get();
    }
    public function filtroProvs()
    {
        if ($this->IdProveedor || strlen($this->keyWordProv) < 2) return [];
        return Empresa::where('tipo', 'proveedor')
            ->where('empresa', 'LIKE', '%' . $this->keyWordProv . '%')
            ->limit(10)->get();
    }
    public function filtroClientes()
    {
        if ($this->IdCliente || strlen($this->keyWordCte) < 2) return [];
        return Empresa::where('tipo', 'cliente')
            ->where('empresa', 'LIKE', '%' . $this->keyWordCte . '%')
            ->limit(10)->get();
    } 
    
    public function elegirMarca()
    {
        $this->IdLinea = null;
        $this->lineas = [];
        if(! $this->nuevoMat['IdMarca']) return;
        $this->lineas = DB::table('lineas')->where('IdMarca', $this->nuevoMat['IdMarca'])->pluck('linea', 'id');
    }
    public function elegirLinea()
    {
        $IdColorable = DB::table('lineas')->where('id',  $this->nuevoMat['IdLinea'])->value('IdColorable');
        $this->colors = DB::table('colors')->where('IdColorable', $IdColorable)->pluck('color', 'id');
        $this->IdColor = null;
    }     
    public function elegirCliente($id, $cliente)
    {
        $this->IdCliente = $id;
        $this->keyWordCte = $cliente;
        $this->clientes = [];
        $this->IdObra = null;
        $this->obras = DB::table('obras')
            ->where('IdEmpresa', $this->IdCliente)
            ->where('estatus', 'vigente')
            ->pluck('obra', 'id');
    }
    public function elegirProv($id, $empresa)
    {
        $this->IdProveedor = $id;
        $this->keyWordProv = $empresa;
        $this->provs = [];
        $this->IdCuentaProv = null;
        $this->cuentas = DB::table('empresascuentas')->where('IdEmpresa', $id)->select(DB::raw("CONCAT(banco, ' - ', cuenta) as cuenta"), 'id')->pluck('cuenta', 'id');           
    } 
    public function calCostoDep($tipo)
    {
        if($tipo == 'costo'){ $this->nuevoMat['neto'] = $this->nuevoMat['costo']*$this->factorIva;}
        if($tipo == 'neto'){ $this->nuevoMat['costo'] = $this->nuevoMat['neto']/$this->factorIva;}
    }
public function mostrarDetalles($id)
{
    $oc = Ocompra::with([
        'ocomprasdets.materialscosto.material.Unidad',
        'ocomprasdets.materialscosto.color',
        'ocomprasdets.materialscosto.Moneda',
        'Proveedor',
        'obra.cliente'
    ])->findOrFail($id);
    $this->oCompra = $oc;
    $this->detalles = [];
    foreach ($oc->ocomprasdets as $d) {
        $m = $d->materialscosto;
        $color = $m->color;
        $this->detalles[] = [
            'IdMatCosto' => $d->IdMatCosto,
            'cantidad' => (float) $d->cantidad,
            'cantidadRec' => (float) ($d->cantidadRec ?? $d->cantidad),
            'costoU' => round($d->costoU, 4),
            'costoURec' => (float) ($d->costoURec ?? $d->costoU),
            'costoN' => round($d->costoU * (1 + $oc->tasaIva), 4),
            'nombre' => ($m->material->referencia ?? '') . " " . ($m->material->material ?? ''),
            'colorRgba' => $color->colorRgba ?? '#fff',
            'simbolo' => $m->Moneda->simbolo ?? '$',
            'unidad' => $m->unidad ?? 'PZ'
        ];
    }
    $this->verDetalles = true;
}

public function cerrarDetalles()
{
    $this->verDetalles = false;
    $this->detalles = [];
}
   
}