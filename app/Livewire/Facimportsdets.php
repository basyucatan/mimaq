<?php
namespace App\Livewire;
use Livewire\{Component, WithPagination};
use App\Models\{Util, Material, Factura, Facimportsdet, Cliente, Orden, 
    Lote, Foliosmat, Folio, Estilosdet};
use Livewire\Attributes\Computed;
use App\Traits\FacImportsMangaer;
class Facimportsdets extends Component
{
    use WithPagination, FacImportsMangaer;
    protected $paginationTheme = 'bootstrap';
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
        $this->arancel = $material->clase->arancel->arancel;
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
        'cliente' => 'required',
        'orden' => 'required',
        'lote' => 'required|numeric'
    ]);

    if (!empty($this->cliente) && !empty($this->orden) && !empty($this->lote)) {
        $objCliente = Cliente::firstOrCreate(['cliente' => strtoupper($this->cliente)]);
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
        
        // Llamada con los dos parámetros solicitados
        $objFolio->definirProducto($this->IdEstilo, $this->cantidadEstilo);
        
        $objFolio->save();

        $this->IdFolio = $objFolio->id;
        $detalles = Estilosdet::where('IdEstilo', $this->IdEstilo)->get();

        foreach ($detalles as $det) {
            // Aquí asumo que llamas a una función interna o save del trait/componente para los materiales
            $this->IdMaterial = $det->IdMaterial;
            $this->IdOrigen = 2;
            $this->cantidad = $objFolio->cantidad * $det->cantidad;
            $this->IdFolio = $objFolio->id;
            $this->IdSize = $det->IdSize;
            $this->IdForma = $det->IdForma;
            $this->estiloY = $det->estiloY;
            $this->pesoEnUMat = 0;
            $this->precioU = 1;
            $this->save(); 
        }
    }
    
    $this->clientes = Util::getArray('clientes');
}
    public function crearEstilo()
    {
        $this->resetInput();
        $this->verModalEstilos = true;
    }    
    public function paginationView()
    {
        return 'livewire.paginacionBase';
    }

}