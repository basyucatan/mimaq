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
            'DeptoOri',
            'DeptoDes'
        ])
        ->where(function ($query) use ($keyWord) {
            $query->where('tipoDoc', 'LIKE', $keyWord)
                ->orWhere('glosa', 'LIKE', $keyWord)
                ->orWhereHas('Referencia', function ($q) use ($keyWord) {
                    $q->where('IdEntradaMex', 'LIKE', $keyWord)
                        ->orWhere('estiloY', 'LIKE', $keyWord)
                        ->orWhere('adicionales->ordenInfo->orden', 'LIKE', $keyWord)
                        ->orWhere('adicionales->ordenInfo->lote', 'LIKE', $keyWord)
                        ->orWhere('adicionales->ordenInfo->cliente', 'LIKE', $keyWord)
                        ->orWhere('adicionales->kt', 'LIKE', $keyWord)
                        ->orWhere('adicionales->color', 'LIKE', $keyWord)
                        ->orWhereHas('material', fn($m) => $m->where('material', 'LIKE', $keyWord))
                        ->orWhereHas('forma', fn($m) => $m->where('forma', 'LIKE', $keyWord))
                        ->orWhereHas('size', fn($m) => $m->where('size', 'LIKE', $keyWord))
                        ->orWhereHas('origen', fn($m) => $m->where('origen', 'LIKE', $keyWord))
                        ->orWhereHas('Estilo', fn($e) => $e->where('estilo', 'LIKE', $keyWord));
                });
        })
        ->when($this->filtroTipoDoc, fn($q) => $q->where('tipoDoc', $this->filtroTipoDoc))
        ->when($this->filtroDepto, function ($q) {
            $q->where(function ($sq) {
                $sq->where('IdDeptoOri', $this->filtroDepto)
                    ->orWhere('IdDeptoDes', $this->filtroDepto);
            });
        })
        ->orderByDesc('created_at')
        ->paginate(15);
}
    public function paginationView()
    {
        return 'livewire.paginacionBase';
    }
    public function render()
    {
        $deptos = \DB::table('deptos')->orderBy('orden')->get();
        return view('livewire.kardex.view', compact('deptos'));
    }
}