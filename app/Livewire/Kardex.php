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
                'DeptoOri', // Nueva relación
                'DeptoDes'  // Nueva relación
            ])
            ->where(function ($query) use ($keyWord) {
                $query->where('tipoDoc', 'LIKE', $keyWord)
                    ->orWhere('glosa', 'LIKE', $keyWord)
                    ->orWhereHas('Referencia', function ($q) use ($keyWord) {
                        $q->where('IdEntradaMex', 'LIKE', $keyWord)
                            ->orWhereHas('material', fn($m) => $m->where('material', 'LIKE', $keyWord));
                    });
            })
            ->when($this->filtroTipoDoc, fn($q) => $q->where('tipoDoc', $this->filtroTipoDoc))
            ->when($this->filtroDepto, function($q) {
                $q->where(function($sq) {
                    $sq->where('IdDeptoOri', $this->filtroDepto)
                       ->orWhere('IdDeptoDes', $this->filtroDepto);
                });
            })
            ->orderBy('created_at', 'desc')
            ->paginate(15);
    }

    public function render()
    {
        // Sugerencia: Cargar los departamentos para el filtro del header
        $deptos = \DB::table('deptos')->orderBy('orden')->get();
        return view('livewire.kardex.view', compact('deptos'));
    }
}