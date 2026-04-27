<?php
namespace App\Traits;
use Illuminate\Support\Facades\Storage;
use Luecano\NumeroALetras\NumeroALetras;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\{Facimportsdet, Factura, Material, Util};
trait GestionFacImports
{
    public $verModalFacimportsdet = false, $verModalEstilos = false, $verModalImpresiones = false;
    public $selected_id, $keyWord, $IdFactura, $factura;
    public $IdEntradaMex, $IdOrigen, $IdMaterial, $cantidad, $precioU, $pesoEnUMat;
    public $IdEstilo, $estiloY, $orden, $lote, $cantidadEstilo, $pesoG, $IdSize, $IdForma, $kt, $color;
    public $adicionales = [], $origens = [], $materials = [], 
        $kts = [], $colors = [], $sizes = [], $formas = [], $estilos = [];
    public function impresiones()
    {
        $this->verModalImpresiones = true;
    }

    public function getArrays()
    {
        $this->factura = Factura::find($this->IdFactura);
        $this->origens = Util::getArray('origens');
        $this->materials = Util::getArray('materials','materialI');
        $this->sizes = Util::getArray('sizes');
        $this->formas = Util::getArray('formas');
        $this->estilos = Util::getArray('estilos');
        $this->kts = ['10K','14K','18K','24K'];
        $this->colors = ['Y','W','P'];
    }
    public function edit($id)
    {
        $this->selected_id = $id;
        $this->fill(Facimportsdet::findOrFail($id)->toArray());
        $this->kt = $this->adicionales['kt'] ?? null;
        $this->color = $this->adicionales['color'] ?? null;
        $this->orden = $this->adicionales['orden'] ?? null;
        $this->lote = $this->adicionales['lote'] ?? null;
        $this->verModalFacimportsdet = true;
    }
    public function create()
    {
        $this->resetInput();
        $this->verModalFacimportsdet = true;
    }
    public function save()
    {
        $this->validate([
            'IdOrigen' => 'required', 'IdMaterial' => 'required',
            'cantidad' => 'required', 'precioU' => 'required', 'pesoEnUMat' => 'required',
        ]);        
        $material = Material::find($this->IdMaterial);
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
                'color' => $this->color,
                'orden' => $this->orden,
                'lote' => $this->lote,
            ]
        );
        Facimportsdet::updateOrCreate(['id' => $this->selected_id], [
            'IdFactura' => $this->IdFactura,
            'IdEntradaMex' => $this->IdEntradaMex,
            'IdOrigen' => $this->IdOrigen,
            'IdMaterial' => $this->IdMaterial,
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
        'facimportsdets.Forma'
    ])->findOrFail($this->IdFactura);
    $itemsAgrupados = $factura->facimportsdets
        ->sortBy(fn($item) => $item->material?->clase?->IdAccess ?? '')
        ->groupBy(function ($item) {
            $nombreMaterial = $item->material->material ?? 'N/A';
            $propiedades = strip_tags($item->propiedades);
            return $nombreMaterial . ($propiedades ? ' ' . $propiedades : '');
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
        $this->resetexcept('keyWord', 'IdFactura', 'factura', 
            'kts','colors','origens', 'materials', 'sizes', 'formas', 'estilos');
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