<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Orden;
use Livewire\Attributes\Computed;
use App\Models\Util;
use Illuminate\Support\Facades\DB;

class Ordens extends Component
{
    use WithPagination;

	protected $paginationTheme = 'bootstrap';
    public $verModalOrden=false, $selected_id, $keyWord, $IdCliente, $orden, $estatus, $fechaVen;
	
	public $adicionales = [];
    public function updatedKeyWord()
	{
		$this->resetPage();
	}
    #[Computed]
	public function filteredOrdens()
	{
		$keyWord = '%' . $this->keyWord . '%';
		return Orden::Where('id','>',0)
			->where(function ($query) use ($keyWord) {
				$query
						->orWhere('IdCliente', 'LIKE', $keyWord)
						->orWhere('orden', 'LIKE', $keyWord)
						->orWhere('estatus', 'LIKE', $keyWord)
						->orWhere('fechaVen', 'LIKE', $keyWord);
			})
			->paginate(12);
	}

	public function render()
	{
		return view('livewire.ordens.view', [
			'ordens' => $this->filteredOrdens,
		]);
	}
	
    public function cancel()
    {
        $this->resetInput();
        $this->verModalOrden = false;
    }

    public function resetInput()
    {
        $this->reset();
    }

    public function edit($id)
    {
        $this->selected_id = $id;
		$this->fill(Orden::findOrFail($id)->toArray());
        $this->verModalOrden = true;
    }
    public function create()
    {
        $this->resetInput();
        $this->verModalOrden = true;
    }    
    public function save()
    {
        $this->validate([
		'orden' => 'required',
		'estatus' => 'required',
		'fechaVen' => 'required',
        ]);

        Orden::updateOrCreate(
			['id' => $this->selected_id],
			[
				'IdCliente' => $this-> IdCliente,
				'orden' => $this-> orden,
				'estatus' => $this-> estatus,
				'fechaVen' => $this-> fechaVen
			]
		);
        $this->resetInput();
        $this->verModalOrden = false;
    }
    public function paginationView()
    {
        return 'livewire.paginacionBase';
    }
    public function destroy($id)
    {
        if ($id) {
            Orden::where('id', $id)->delete();
        }
    }
}