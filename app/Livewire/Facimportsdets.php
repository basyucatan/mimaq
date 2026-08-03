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
    public $verModalFacimportsdet = false, $verPrecaptura = false, 
        $verModalProduccion = false, $verModalImpresiones = false,
        $infoProduccion = false;
    public $selected_id, $keyWord, $IdFactura, $factura, $arancel;
    public $IdEntradaMex, $IdOrigen, $IdMaterial, $cantidad, $precioU, $pesoEnUMat,
        $IdEstilo, $IdFolio, $IdTipo, $unidadP, $forzarGuardado = false, $estiloY, $IdCliente, 
        $ultimaOrden, $ultimoLote, $consecutivoAuto,
        $orden, $lote, $cantidadEstilo, $pesoG, $IdSize, $IdForma, $kt, $aro, $color;
    public $adicionales = [], $origens = [], $materials = [], $clientes = [], $folios = [],
        $precaptura = [],
        $kts = [], $aros = [], $colors = [], $sizes = [], $formas = [], $estilos = [], $tipos = [];
    protected $messages  = [
        'IdEstilo.required' => 'The style is required.',
        'cantidadEstilo.required' => 'The quantity is required.',
        'cantidadEstilo.numeric' => 'The quantity must be a number.',
        'IdCliente.required' => 'The customer is required.',
        'orden.required' => 'The order is required.',
        'lote.required' => 'The lot is required.',
        'lote.numeric' => 'The lot must be a number.',
    ];
    public function migrar()
    {
        $detalles = Facimportsdet::with(['folio.lote.orden.cliente', 'material.clase.tipo'])
            ->whereNotNull('IdFolio')
            ->get();
        $gruposFolio = $detalles->groupBy('IdFolio');
        $registrosProcesados = 0;
        foreach ($gruposFolio as $idFolio => $items) {
            $primerItem = $items->first();
            $folio = $primerItem->folio;

            if (!$folio) {
                continue;
            }
            $lote = $folio->lote;
            $orden = $lote?->orden;
            $cliente = $orden?->cliente;

            $infoBase = array_filter([
                'orden' => $orden?->orden ? (string)$orden->orden : null,
                'lote' => $lote?->lote ? (string)$lote->lote : null,
                'IdCliente' => $cliente?->id ? (string)$cliente->id : null,
                'cliente' => $cliente?->cliente ?? null,
            ], fn($val) => !is_null($val));
            $itemCasting = $items->first(function ($item) {
                return data_get($item, 'material.clase.tipo.id') == 1;
            });
            $idItemProduccion = $itemCasting ? $itemCasting->id : $primerItem->id;
            foreach ($items as $det) {
                $adicionales = $det->adicionales ?? [];
                $ordenInfo = $infoBase;
                if ($det->id === $idItemProduccion) {
                    if ($folio->IdEstilo) {
                        $ordenInfo['IdEstilo'] = (string)$folio->IdEstilo;
                    }
                    if ($folio->cantidad) {
                        $ordenInfo['cantidadEstilo'] = (string)$folio->cantidad;
                    }
                    $ordenInfo['esProduccion'] = true;
                }
                $adicionales['ordenInfo'] = $ordenInfo;
                $det->update([
                    'adicionales' => $adicionales
                ]);
                $registrosProcesados++;
            }
        }
        return "Migración completada. Registros actualizados: {$registrosProcesados}";
    }
    protected $listeners = [
        'IdFacturaElecta' => 'IdFacturaElecta',
    ];
    public function IdFacturaElecta()
    {
        $this->factura = Factura::find($this->IdFactura);
        $this->resetPage();
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
    $detalles = Facimportsdet::where('IdFactura', $this->IdFactura)
        ->where(function ($query) use ($keyWord) {
            $query->where('IdEntradaMex', 'LIKE', $keyWord)
                ->orWhereHas('material', fn($q) =>
                    $q->where('material', 'LIKE', $keyWord)
                    ->orWhere('materialI', 'LIKE', $keyWord))
                ->orWhereHas('Estilo', fn($q) => $q->where('estilo', 'LIKE', $keyWord))
                ->orWhereHas('forma', fn($q) => $q->where('forma', 'LIKE', $keyWord))
                ->orWhereHas('origen', fn($q) => $q->where('origen', 'LIKE', $keyWord))
                ->orWhereHas('size', fn($q) => $q->where('size', 'LIKE', $keyWord))
                ->orWhere('adicionales->ordenInfo->orden', 'LIKE', $keyWord)
                ->orWhere('adicionales->ordenInfo->lote', 'LIKE', $keyWord)
                ->orWhere('adicionales->ordenInfo->cliente', 'LIKE', $keyWord);
        })->orderBy('id', 'desc')->paginate(10);
    return $detalles;
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
    $idEntradaMex = $this->factura->getNextIdEntradaMex();
    $clienteNombre = $this->IdCliente ? (Cliente::find($this->IdCliente)?->cliente ?? '') : '';
    $indiceConTipoUno = null;
    foreach ($this->precaptura as $index => $linea) {
        $material = Material::with('Clase.Tipo')->find($linea['IdMaterial']);
        if ($material?->Clase?->Tipo?->id == 1) {
            $indiceConTipoUno = $index;
            break;
        }
    }
    $indiceProduccion = $indiceConTipoUno !== null ? $indiceConTipoUno : 0;
    foreach ($this->precaptura as $index => $linea) {
        $material = Material::with('Clase.Arancel')->find($linea['IdMaterial']);
        $adicionales = [
            'kt' => $linea['kt'] ?? null,
            'aro' => $linea['aro'] ?? null,
            'color' => $linea['color'] ?? null,
            'ordenInfo' => [
                'orden' => strtoupper($this->orden),
                'lote' => strtoupper($this->lote),
                'IdCliente' => $this->IdCliente,
                'cliente' => $clienteNombre
            ]
        ];
        if ($index === $indiceProduccion) {
            $adicionales['ordenInfo']['IdEstilo'] = $this->IdEstilo;
            $adicionales['ordenInfo']['cantidadEstilo'] = $this->cantidadEstilo;
            $adicionales['ordenInfo']['esProduccion'] = true;
        }
        Facimportsdet::create([
            'IdFactura' => $this->IdFactura,
            'IdEntradaMex' => $idEntradaMex++,
            'IdOrigen' => $linea['IdOrigen'],
            'IdMaterial' => $linea['IdMaterial'],
            'IdFolio' => null,
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
    $this->alerta('Detalles agregados correctamente', 'success');
    $this->cancel();
}
public function editProduccion($id)
{
    $registro = Facimportsdet::findOrFail($id);
    $adicionales = $registro->adicionales ?? [];
    if (!isset($adicionales['ordenInfo'])) return;
    $this->selected_id = $id;
    $this->fill($adicionales['ordenInfo']);
    $this->verModalProduccion = true;
}
public function saveProduccion()
{
    if (!$this->selected_id) return;
    $registro = Facimportsdet::findOrFail($this->selected_id);
    $adicionales = $registro->adicionales ?? [];
    $ordenAnterior = data_get($adicionales, 'ordenInfo.orden');
    $loteAnterior = data_get($adicionales, 'ordenInfo.lote');
    $clienteAnterior = data_get($adicionales, 'ordenInfo.cliente');
    $datosProduccion = [
        'IdCliente' => $this->IdCliente,
        'cliente' => $clienteAnterior,
        'orden' => strtoupper($this->orden),
        'lote' => strtoupper($this->lote),
        'IdEstilo' => $this->IdEstilo,
        'cantidadEstilo' => $this->cantidadEstilo,
        'esProduccion' => true
    ];
    $adicionales['ordenInfo'] = array_filter($datosProduccion, fn($v) => !is_null($v));
    $registro->update(['adicionales' => $adicionales]);
    if ($ordenAnterior && $loteAnterior) {
        $detallesRelacionados = Facimportsdet::where('IdFactura', $registro->IdFactura)
            ->where('id', '!=', $this->selected_id)
            ->get();
        foreach ($detallesRelacionados as $det) {
            $adics = $det->adicionales ?? [];
            if (data_get($adics, 'ordenInfo.orden') === $ordenAnterior && data_get($adics, 'ordenInfo.lote') === $loteAnterior) {
                $adics['ordenInfo']['orden'] = strtoupper($this->orden);
                $adics['ordenInfo']['lote'] = strtoupper($this->lote);
                $adics['ordenInfo']['IdCliente'] = $this->IdCliente;
                $det->update(['adicionales' => $adics]);
            }
        }
    }
    $this->alerta('Datos de producción actualizados correctamente', 'success');
    $this->cancel();
}
public function ultimoConsecutivo()
{
    if (!$this->IdFactura || !$this->IdCliente) {
        return;
    }
    $cliente = Cliente::find($this->IdCliente);
    $this->consecutivoAuto = true;
    if (!$cliente?->consecutivoOrden) {
        $this->consecutivoAuto = false;
        return;
    }
    $ultimoDetalle = Facimportsdet::where('IdFactura', $this->IdFactura)
        ->where('adicionales->ordenInfo->IdCliente', $this->IdCliente)
        ->latest('id')
        ->first();
    if ($ultimoDetalle) {
        $ordenActual = data_get($ultimoDetalle->adicionales, 'ordenInfo.orden', 500);
        $loteActual = data_get($ultimoDetalle->adicionales, 'ordenInfo.lote', 100);
        $this->ultimaOrden = is_numeric($ordenActual) ? (int)$ordenActual + 1 : 500;
        $this->ultimoLote = is_numeric($loteActual) ? (int)$loteActual + 1 : 100;
    } else {
        $this->ultimaOrden = 500;
        $this->ultimoLote = 100;
    }
    $this->orden = $this->ultimaOrden;
    $this->lote = $this->ultimoLote;
}
public function crearEstilo()
{
    if ($this->montoExcedido()) return;
    $this->resetInput();
    $this->verPrecaptura = true;
}
    private function montoExcedido(){
        $factura = Factura::find($this->IdFactura);
        if ($factura->excedeLimite) {
            $this->alerta(
                'La factura excede el total permitido de ' .
                number_format($factura->limiteFactura, 2),
                'warning', 2000
            );
            return true;
        }
        return false;
    }    
    public function paginationView()
    {
        return 'livewire.paginacionBase';
    }

}