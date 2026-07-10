<?php
namespace App\Livewire;
use Livewire\{Component, WithPagination};
use App\Models\{Cliente, Orden, Lote, Folio, Material, Factura, 
    Facimportsdet, Estilosdet, Util};
use Livewire\Attributes\Computed;
use App\Traits\FacImportsManager;
use App\Traits\Utilfun;
class Facimportsdets extends Component
{
    use WithPagination, Utilfun, FacImportsManager;
    protected $paginationTheme = 'bootstrap';
    public $verModalFacimportsdet = false, $verPrecaptura = false, $verModalImpresiones = false;
    public $selected_id, $keyWord, $IdFactura, $factura, $arancel;
    public $IdEntradaMex, $IdOrigen, $IdMaterial, $cantidad, $precioU, $pesoEnUMat,
        $IdEstilo, $IdFolio, $IdTipo, $unidadP, $forzarGuardado = false,
        $estiloY, $IdCliente, $orden, $lote, $cantidadEstilo, $pesoG, $IdSize, $IdForma, $kt, $color;
    public $adicionales = [], $origens = [], $materials = [], $clientes = [], $folios = [],
        $precaptura = [],
        $kts = [], $colors = [], $sizes = [], $formas = [], $estilos = [], $tipos = [];
    protected $listeners = [
        'IdFacturaElecta' => 'IdFacturaElecta',
    ];
    public function IdFacturaElecta()
    {
        $this->factura = Factura::find($this->IdFactura);
    }    
    public function elegirMaterial()
    {
        $material = Material::find($this->IdMaterial);
        $this->arancel = $material?->clase?->arancel?->arancel;
        $this->unidadP = $material?->UnidadP?->unidad;
    }     
    public function mount()
    {
        $this->getArrays();
    }
    public function updatedKeyWord()
    {
        $this->resetPage();
    }
    #[Computed]
    public function filteredFacimportsdets()
    {
        $keyWord = '%' . $this->keyWord . '%';
        return Facimportsdet::where('IdFactura', $this->IdFactura)
            ->where(function ($query) use ($keyWord) {
                $query->where('IdEntradaMex', 'LIKE', $keyWord)
                    ->orWhereHas('material', fn($q) =>
                        $q->where('material', 'LIKE', $keyWord)
                        ->orWhere('materialI', 'LIKE', $keyWord))
                    ->orWhereHas('Estilo', fn($q) => $q->where('estilo', 'LIKE', $keyWord))
                    ->orWhereHas('forma', fn($q) => $q->where('forma', 'LIKE', $keyWord))
                    ->orWhereHas('origen', fn($q) => $q->where('origen', 'LIKE', $keyWord))
                    ->orWhereHas('size', fn($q) => $q->where('size', 'LIKE', $keyWord))
                    ->orWhereHas('folio.lote.orden.cliente', fn($q) => $q->where('cliente', 'LIKE', $keyWord))
                    ->orWhereHas('folio.lote.orden', fn($q) => $q->where('orden', 'LIKE', $keyWord))
                    ->orWhereHas('folio.lote', fn($q) => $q->where('lote', 'LIKE', $keyWord))
                    ->orWhere('adicionales->orden', 'LIKE', $keyWord)
                    ->orWhere('adicionales->lote', 'LIKE', $keyWord);
            })->orderBy('id', 'desc')->paginate(10);
    }
    public function render()
    {
        return view('livewire.facimportsdets.view', ['facimportsdets' => $this->filteredFacimportsdets]);
    }
    public function generarConEstilo()
    {
        $this->validate([
            'IdEstilo' => 'required',
            'cantidadEstilo' => 'required|numeric',
            'IdCliente' => 'required',
            'orden' => 'required',
            'lote' => 'required|numeric'
        ]);
        $this->precaptura = [];
        $detalles = Estilosdet::with(['material.clase'])->where('IdEstilo', $this->IdEstilo)->get();
        foreach ($detalles as $det) {
            $idTipoDetectado = $det->material?->clase?->IdTipo;
            $esCasting = ($idTipoDetectado == 1);
            $esMetalAux = ($idTipoDetectado == 6);
            $this->precaptura[] = [
                'IdFactura' => $this->IdFactura,
                'IdEntradaMex' => '',
                'IdOrigen' => 2,
                'IdMaterial' => $det->IdMaterial,
                'arancel' => null,
                'cantidad' => $this->cantidadEstilo * $det->cantidad,
                'precioU' => 1,
                'pesoEnUMat' => 0,
                'pesoG' => 0,
                'IdSize' => $esCasting ? null : $det->IdSize,
                'IdForma' => $esCasting ? null : $det->IdForma,
                'IdFolio' => null,
                'IdEstilo' => $esCasting ? $this->IdEstilo : null,
                'estiloY' => $esMetalAux ? $det->estiloY : '',
                'diferencias' => null,
                'adicionales' => null,
                'IdTipo' => $idTipoDetectado,
                'kt' => $esCasting ? '' : null,
                'color' => $esCasting ? '' : null,
                'unidadP' => $det->material?->unidadP?->unidad ?? ''
            ];
        }
    }
public function nuevoComponente()
    {
        $this->precaptura[] = [
            'IdFactura' => $this->IdFactura,
            'IdEntradaMex' => '',
            'IdOrigen' => 2,
            'IdMaterial' => null,
            'arancel' => null,
            'cantidad' => 1,
            'precioU' => 1,
            'pesoEnUMat' => 0,
            'pesoG' => 0,
            'IdSize' => null,
            'IdForma' => null,
            'IdFolio' => null,
            'IdEstilo' => null,
            'estiloY' => '',
            'diferencias' => null,
            'adicionales' => null,
            'IdTipo' => null,
            'kt' => null,
            'color' => null,
            'unidadP' => ''
        ];
    }
public function eliminarComponente($index)
    {
        unset($this->precaptura[$index]);
        $this->precaptura = array_values($this->precaptura);
    }
public function cambiarMaterial($index, $value)
    {
        $this->precaptura[$index]['IdMaterial'] = $value;
        if (!empty($value)) {
            $material = Material::with(['unidadP'])->find($value);
            $this->precaptura[$index]['unidadP'] = $material?->unidadP?->unidad ?? '';
            $this->precaptura[$index]['IdEstilo'] = null;
        } else {
            $this->precaptura[$index]['unidadP'] = '';
        }
    }
    public function agregar()
    {
        if (empty($this->precaptura)) return;
        $objCliente = Cliente::find($this->IdCliente);
        if (!$this->forzarGuardado) {
            $objOrden = Orden::where('orden', strtoupper($this->orden))->first();
            if ($objOrden) {
                $objLote = Lote::where('IdOrden', $objOrden->id)->where('lote', strtoupper($this->lote))->first();
                if ($objLote) {
                    $existeEstilo = Folio::where('IdLote', $objLote->id)->where('IdEstilo', $this->IdEstilo)->exists();
                    if ($existeEstilo) {
                        $this->forzarGuardado = true;
                        $this->alerta('El estilo ya existe en este lote. Confirma intentándolo de nuevo.', 'warning',2500);
                        return;
                    }
                }
            }
        }
        $this->forzarGuardado = false;
        $objOrden = Orden::firstOrCreate(['orden' => strtoupper($this->orden)], [
            'IdCliente' => $objCliente->id,
            'fechaVen' => now()->addDays(7),
            'estatus' => 'abierto'
        ]);
        $objLote = Lote::firstOrCreate(['IdOrden' => $objOrden->id, 'lote' => strtoupper($this->lote)]);
        $objFolio = new Folio();
        $objFolio->IdLote = $objLote->id;
        $objFolio->precioU = 0;
        $objFolio->fechaVen = $objOrden->fechaVen;
        $objFolio->estatus = 'abierto';
        $objFolio->definirProducto($this->IdEstilo, $this->cantidadEstilo);
        $objFolio->save();
        $idEntradaMex = $this->factura->getNextIdEntradaMex();
        foreach ($this->precaptura as $linea) {
            $material = Material::with('Clase.Arancel')->find($linea['IdMaterial']);
            $adicionales = [
                'kt' => $linea['kt'] ?? null,
                'color' => $linea['color'] ?? null
            ];
            Facimportsdet::create([
                'IdFactura' => $this->IdFactura,
                'IdEntradaMex' => $idEntradaMex++,
                'IdOrigen' => $linea['IdOrigen'],
                'IdMaterial' => $linea['IdMaterial'],
                'IdFolio' => $objFolio->id,
                'arancel' => $material?->Clase?->Arancel?->arancel,
                'cantidad' => $linea['cantidad'],
                'precioU' => $linea['precioU'],
                'pesoEnUMat' => $linea['pesoEnUMat'],
                'pesoG' => $material?->getPesoG($linea['pesoEnUMat']) ?? 0,
                'IdSize' => $linea['IdSize'],
                'IdForma' => $linea['IdForma'],
                'IdEstilo' => $linea['IdEstilo'],
                'estiloY' => $linea['estiloY'],
                'adicionales' => $adicionales
            ]);
        }
        $this->clientes = Util::getArray('clientes');
        $this->alerta('Estilo agregado', 'success');
        $this->cancel();
    }
    public function crearEstilo()
    {
        $this->resetInput();
        $this->verPrecaptura = true;
    }    
    public function paginationView()
    {
        return 'livewire.paginacionBase';
    }

}