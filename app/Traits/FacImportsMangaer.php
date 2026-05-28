<?php
namespace App\Traits;
use Illuminate\Support\Facades\Storage;
use Luecano\NumeroALetras\NumeroALetras;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\{Facimportsdet, Factura, Material, Util, Folio};
use Illuminate\Support\Facades\DB;
trait FacImportsMangaer
{
    public $verModalFacimportsdet = false, $verModalEstilos = false, $verModalImpresiones = false;
    public $selected_id, $keyWord, $IdFactura, $factura, $arancel;
    public $IdEntradaMex, $IdOrigen, $IdMaterial, $cantidad, $precioU, $pesoEnUMat,
        $IdEstilo, $IdFolio, $IdTipo,
        $estiloY, $cliente, $orden, $lote, $cantidadEstilo, $pesoG, $IdSize, $IdForma, $kt, $color;
    public $adicionales = [], $origens = [], $materials = [], $clientes = [], $folios = [],
        $kts = [], $colors = [], $sizes = [], $formas = [], $estilos = [], $tipos = [];
    public function impresiones()
    {
        $this->verModalImpresiones = true;
    }
    public function updatedIdTipo()
    {
        if (!$this->IdTipo) {
            $this->materials = Util::getArray('materials', 'materialI');
            return;
        }
        $this->cargarMateriales();
    }
    private function cargarMateriales()
    {
        $this->materials = DB::table('materials')
            ->join('clases', 'clases.id', '=', 'materials.IdClase')
            ->where('clases.IdTipo', $this->IdTipo)
            ->select('materials.*')
            ->orderBy('materials.material')
            ->pluck('materialI', 'id')
            ->toArray();
    }
    public function cambiarTipo(){$this->IdMaterial = null;}
    public function getArrays()
    {
        $this->factura = Factura::find($this->IdFactura);
        $this->origens = Util::getArray('origens');
        $this->materials = Util::getArray('materials','materialI');
        $this->sizes = Util::getArray('sizes');
        $this->tipos = Util::getArray('tipos','tipoI');
        $this->formas = Util::getArray('formas');
        $this->estilos = Util::getArray('estilos');
        $this->kts = ['10K','14K','18K','24K'];
        $this->colors = ['Y','W','P'];
        $this->clientes = Util::getArray('clientes');
        $this->folios = Folio::with(['lote.orden.cliente'])
        ->where('estatus', 'abierto')
        ->orderBy('id', 'desc')
        ->get();
    }
public function limpiarFolio()
{
    $this->IdFolio = null;
    $this->orden = null;
    $this->lote = null;
}
public function actualizarDatosFolio()
{
    if ($this->IdFolio) {
        $folio = Folio::with(['lote.orden'])->find($this->IdFolio);
        if ($folio) {
            $this->orden = $folio->lote->orden->orden;
            $this->lote = $folio->lote->lote;
        }
    }
}
    public function edit($id)
    {
        $this->selected_id = $id;
        $importDet = Facimportsdet::with('Material.Clase')->findOrFail($id);
        $this->fill($importDet->toArray());
        $this->IdTipo = $importDet->Material?->Clase?->IdTipo;
        $this->cargarMateriales();
        $this->kt = $this->adicionales['kt'] ?? null;
        $this->color = $this->adicionales['color'] ?? null;
        $this->actualizarDatosFolio();
        $this->verModalFacimportsdet = true;
    }
    public function create()
    {
        $this->resetInput();
        $this->selected_id = null;
        $this->verModalFacimportsdet = true;
    }
    public function save()
    {
        $this->validate([
            'IdMaterial' => 'required',
            'cantidad' => 'required', 
            'precioU' => 'required', 
            'pesoEnUMat' => 'required'
        ]);        
        $material = Material::with('Clase.Arancel')->find($this->IdMaterial);
        $arancel = $material->Clase->Arancel->arancel;
        $this->pesoG = $material ? $material->getPesoG($this->pesoEnUMat) : 0;
        if (!$this->selected_id) {
            $this->IdEntradaMex = $this->factura->getNextIdEntradaMex();
        }
        $facDet = $this->selected_id ? Facimportsdet::find($this->selected_id) : null;
        $adActual = $facDet?->adicionales ?? [];
        $this->adicionales = array_merge(
            $adActual,
            (array)$this->adicionales,
            [
                'kt' => $this->kt,
                'color' => $this->color
            ]
        );
    Facimportsdet::updateOrCreate(['id' => $this->selected_id], [
        'IdFactura' => $this->IdFactura,
        'IdEntradaMex' => $this->IdEntradaMex,
        'IdOrigen' => $this->IdOrigen ?? 2,
        'IdMaterial' => $this->IdMaterial,
        'IdFolio' => $this->IdFolio, 
        'arancel' => $arancel,
        'cantidad' => $this->cantidad,
        'precioU' => $this->precioU,
        'pesoEnUMat' => $this->pesoEnUMat,
        'pesoG' => $this->pesoG,
        'IdSize' => $this->IdSize ?: null,
        'IdForma' => $this->IdForma ?: null,
        'IdEstilo' => $material->Clase->Tipo->tipo == 'CASTING' ? $this->IdEstilo : null,
        'estiloY' => $material->Clase->Tipo->tipo == 'METAL AUX' ? $this->estiloY : null,
        'adicionales' => $this->adicionales
    ]);
    if (!$this->selected_id) {
        $this->IdEntradaMex = null;
    }
    $this->cancel();
}
private function getFactura()
{
    $factura = Factura::with([
        'facimportsdets.material.clase.arancel',
        'facimportsdets.material.unidad',
        'facimportsdets.origen',
        'facimportsdets.Estilo',
        'facimportsdets.Size',
        'facimportsdets.Forma',
        'facimportsdets.folio.lote.orden'
    ])->findOrFail($this->IdFactura);
    $itemsAgrupados = $factura->facimportsdets
        ->groupBy(function ($item) {
            $nombreMaterial = $item->material->material ?? 'N/A';
            $propiedades = strip_tags($item->propiedades);
            return $nombreMaterial . ($propiedades ? ' ' . $propiedades : '');
        })
        ->map(function ($grupo) {
            return $grupo->sortBy([
                ['folio.lote.lote', 'asc'],
                ['folio.id', 'asc']
            ]);
        })
        ->sortBy(function ($grupo) {
            $primerItem = $grupo->first();
            return $primerItem->material?->clase?->IdAccess ?? '';
        });
    return [$factura, $itemsAgrupados];
}
public function imprimirFactura()
{
    [$factura, $itemsAgrupados] = $this->getFactura();
    $montoTotal = $factura->facimportsdets->sum(fn($i) => $i->cantidad * $i->precioU);
    $formateador = new NumeroALetras();
    $totalEnLetras = $formateador->toMoney($montoTotal, 2, 'DÓLARES', 'CENTAVOS');
    $htmlFactura = view('livewire.facimportsdets.facturaPDF', compact('itemsAgrupados', 'factura', 'totalEnLetras'))->render();
    $instanciaDompdf = PDF::loadHTML($htmlFactura);
    $instanciaDompdf->setPaper('letter', 'portrait');
    $contenidoPdf = $instanciaDompdf->output();
    $rutaArchivo = 'imports/factura_' . $factura->factura . '.pdf';
    Storage::disk('public')->put($rutaArchivo, $contenidoPdf);
    $rutaFisica = storage_path('app/public/' . $rutaArchivo);
    return response()->file($rutaFisica, [
        'Content-Type' => 'application/pdf',
        'Content-Disposition' => 'inline; filename="factura.pdf"'
    ]);
}
public function imprimirPL()
{
    [$factura, $itemsAgrupados] = $this->getFactura();
    $htmlPL = view('livewire.facimportsdets.packingLPDF', compact('itemsAgrupados', 'factura'))->render();
    $instanciaDompdf = PDF::loadHTML($htmlPL);
    $instanciaDompdf->setPaper('letter', 'portrait');
    $contenidoPdf = $instanciaDompdf->output();
    $rutaArchivo = 'imports/packingList_' . $factura->factura . '.pdf';
    Storage::disk('public')->put($rutaArchivo, $contenidoPdf);
    $rutaFisica = storage_path('app/public/' . $rutaArchivo);
    return response()->file($rutaFisica, [
        'Content-Type' => 'application/pdf',
        'Content-Disposition' => 'inline; filename="PackingList.pdf"'
    ]);
}

    public function resetInput()
    {
        $this->resetexcept('keyWord', 'selected_id', 'IdFactura', 'factura', 'folios', 'tipos',
            'kts','colors','origens', 'clientes', 'materials', 'sizes', 'formas', 'estilos');
    }
    public function cancel()
    {
        $this->resetInput();
        $this->verModalFacimportsdet = false;
        $this->verModalEstilos = false;
        $this->verModalImpresiones = false;
    }
    public function destroy($id)
    {
        if ($id) Facimportsdet::where('id', $id)->delete();
    }    
}