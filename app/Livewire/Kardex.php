<?php

namespace App\Livewire;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Referenciasmov;
use Livewire\Attributes\Computed;
class Kardex extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $buscar, $filtroDepto, $filtroTipoDoc;
    public function updatedBuscar()
    {
        $this->resetPage();
    }
    #[Computed]
    public function movimientos()
    {
        $keyWord = '%' . $this->buscar . '%';
        return Referenciasmov::with([
            'Referencia.material', 
            'Referencia.Estilo', 
            'Referencia.forma', 
            'Referencia.size'
        ])
        ->where(function ($query) use ($keyWord) {
            $query->where('tipoDoc', 'LIKE', $keyWord)
                ->orWhereHas('Referencia', function ($q) use ($keyWord) {
                    $q->where('IdEntradaMex', 'LIKE', $keyWord)
                        ->orWhere('estiloY', 'LIKE', $keyWord)
                        ->orWhereHas('material', fn($m) => $m->where('material', 'LIKE', $keyWord))
                        ->orWhereHas('Estilo', fn($e) => $e->where('estilo', 'LIKE', $keyWord))
                        ->orWhereHas('forma', fn($f) => $f->where('forma', 'LIKE', $keyWord));
                });
        })
        ->when($this->filtroTipoDoc, fn($q) => $q->where('tipoDoc', $this->filtroTipoDoc))
        ->orderBy('created_at', 'desc')
        ->paginate(15);
    }
    public function render()
    {
        return view('livewire.kardex.view');
    }
}