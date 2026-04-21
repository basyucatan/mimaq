<?php
namespace App\Traits;
use App\Models\{Facimportsdet, Factura, Material, Util};
trait GestionFacImports
{
    public $verModalFacimportsdet = false, $verModalEstilos = false, $verModalImpresiones = false;
    public $selected_id, $keyWord, $IdFactura, $Factura;
    public $IdEntradaMex, $IdOrigen, $IdMaterial, $cantidad, $precioU, $pesoEnUMat;
    public $IdEstilo, $estiloY, $orden, $lote, $cantidadEstilo, $pesoG, $IdSize, $IdForma;
    public $adicionales = [], $origens = [], $materials = [], $sizes = [], $formas = [], $estilos = [];
    public function impresiones()
    {
        $this->verModalImpresiones = true;
    }    
    public function getArrays()
    {
        $this->Factura = Factura::find($this->IdFactura);
        $this->origens = Util::getArray('origens');
        $this->materials = Util::getArray('materials');
        $this->sizes = Util::getArray('sizes');
        $this->formas = Util::getArray('formas');
        $this->estilos = Util::getArray('estilos');
    }

    public function guardar()
    {
        $material = Material::find($this->IdMaterial);
        $this->pesoG = $material ? $material->getPesoG($this->pesoEnUMat) : 0;
        if (!$this->selected_id) {
            $this->IdEntradaMex = $this->Factura->getNextIdEntradaMex();
        }
        $facDet = $this->selected_id ? Facimportsdet::find($this->selected_id) : null;
        $adActual = $facDet?->adicionales ?? [];
        $this->adicionales = array_merge($adActual, [
            'orden' => $this->orden,
            'lote' => $this->lote,
        ], (array)$this->adicionales);
        $resultado = Facimportsdet::updateOrCreate(['id' => $this->selected_id], [
            'IdFactura' => $this->IdFactura,
            'IdEntradaMex' => $this->IdEntradaMex,
            'IdOrigen' => $this->IdOrigen,
            'IdMaterial' => $this->IdMaterial,
            'cantidad' => $this->cantidad,
            'precioU' => $this->precioU,
            'pesoEnUMat' => $this->pesoEnUMat,
            'pesoG' => $this->pesoG,
            'IdSize' => $this->IdSize,
            'IdForma' => $this->IdForma,
            'IdEstilo' => $material->Clase->Tipo->tipo == 'CASTING' ? $this->IdEstilo : null,
            'estiloY' => $material->Clase->Tipo->tipo == 'METAL AUX' ? $this->estiloY : null,
            'adicionales' => $this->adicionales
        ]);
        if (!$this->selected_id) {
            $this->IdEntradaMex = null;
        }
        return $resultado;
    }
    public function resetInput()
    {
        $this->resetexcept('keyWord', 'IdFactura', 'Factura', 'origens', 'materials', 'sizes', 'formas', 'estilos');
    }
}