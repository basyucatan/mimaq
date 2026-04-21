<?php
namespace App\Livewire;
use Livewire\{Component, WithPagination};
use App\Models\{Facimportsdet, Estilosdet};
use Livewire\Attributes\Computed;
use App\Traits\GestionFacImports;
class Facimportsdets extends Component
{
    use WithPagination, GestionFacImports;
    protected $paginationTheme = 'bootstrap';
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
    public function save()
    {
        $this->validate([
            'IdOrigen' => 'required', 'IdMaterial' => 'required',
            'cantidad' => 'required', 'precioU' => 'required', 'pesoEnUMat' => 'required',
        ]);
        $this->guardar();
        $this->cancel();
    }
    public function generarConEstilo()
    {
        $this->validate(['IdEstilo' => 'required', 'cantidadEstilo' => 'required|numeric']);
        $detalles = \App\Models\Estilosdet::where('IdEstilo', $this->IdEstilo)->get();
        $this->selected_id = null;
        foreach ($detalles as $det) {
            $this->IdMaterial = $det->IdMaterial;
            $this->IdOrigen = 2;
            $this->cantidad = $this->cantidadEstilo * $det->cantidad;
            $this->pesoEnUMat = 0;
            $this->precioU = 0;
            $this->adicionales = ['orden' => $this->orden ?? '','lote' => $this->lote ?? ''];
            $this->guardar();
        }
        $this->cancel();
    }
    public function edit($id)
    {
        $this->selected_id = $id;
        $this->fill(Facimportsdet::findOrFail($id)->toArray());
        $this->orden = $this->adicionales['orden'] ?? null;
        $this->lote = $this->adicionales['lote'] ?? null;
        $this->verModalFacimportsdet = true;
    }
    public function create()
    {
        $this->resetInput();
        $this->verModalFacimportsdet = true;
    }
    public function crearEstilo()
    {
        $this->resetInput();
        $this->verModalEstilos = true;
    }
    public function cancel()
    {
        $this->resetInput();
        $this->verModalFacimportsdet = false;
        $this->verModalEstilos = false;
        $this->verModalImpresiones = false;
    }
    public function paginationView()
    {
        return 'livewire.paginacionBase';
    }
    public function destroy($id)
    {
        if ($id) Facimportsdet::where('id', $id)->delete();
    }
}