<?php
namespace App\Livewire;
use Livewire\{Component, WithPagination};
use App\Models\{Factura, Facimportsdet, Estilosdet};
use Livewire\Attributes\Computed;
use App\Traits\GestionFacImports;
class Facimportsdets extends Component
{
    use WithPagination, GestionFacImports;
    protected $paginationTheme = 'bootstrap';
    protected $listeners = [
        'IdFacturaElecta' => 'IdFacturaElecta',
    ];
    public function IdFacturaElecta()
    {
        $this->factura = Factura::find($this->IdFactura);
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
                    ->orWhereHas('material', fn($q) => $q->where('material', 'LIKE', $keyWord))
                    ->orWhereHas('estilo', fn($q) => $q->where('estilo', 'LIKE', $keyWord))
                    ->orWhereHas('forma', fn($q) => $q->where('forma', 'LIKE', $keyWord))
                    ->orWhereHas('origen', fn($q) => $q->where('origen', 'LIKE', $keyWord))
                    ->orWhereHas('size', fn($q) => $q->where('size', 'LIKE', $keyWord))
                    ->orWhere('adicionales->orden', 'LIKE', $keyWord)
                    ->orWhere('adicionales->lote', 'LIKE', $keyWord);
            })->orderBy('id', 'desc')->paginate(20);
    }
    public function render()
    {
        return view('livewire.facimportsdets.view', ['facimportsdets' => $this->filteredFacimportsdets]);
    }
    public function generarConEstilo()
    {
        $this->validate(['IdEstilo' => 'required', 'cantidadEstilo' => 'required|numeric']);
        $detalles = Estilosdet::where('IdEstilo', $this->IdEstilo)->get();
        $cantidadEstilo = $this->cantidadEstilo;
        $orden = $this->orden;
        $lote = $this->lote;
        $adicionales = ['orden' => $this->orden ?? '','lote' => $this->lote ?? ''];
        $this->selected_id = null;
        foreach ($detalles as $det) {
            $this->IdMaterial = $det->IdMaterial;
            $this->IdEstilo = $det->Estilo->id;
            $this->estiloY = $det->estiloY;
            $this->orden = $orden;
            $this->lote = $lote;
            $this->IdOrigen = 2;
            $this->cantidad = $cantidadEstilo * $det->cantidad;
            $this->pesoEnUMat = 0;
            $this->precioU = 0;
            $this->adicionales = $adicionales;
            $this->save();
        }
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