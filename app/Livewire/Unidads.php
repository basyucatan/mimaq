<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Unidad;
use Livewire\Attributes\Computed;
use App\Models\Util;
use Illuminate\Support\Facades\DB;

class Unidads extends Component
{
    use WithPagination;

	protected $paginationTheme = 'bootstrap';
    public $verModalUnidad=false, $selected_id, $keyWord, $unidad, $unidadI, $factorC, $grupo;
	
	public $adicionales = [];
    public function updatedKeyWord()
	{
		$this->resetPage();
	}
    #[Computed]
	public function filteredUnidads()
	{
		$keyWord = '%' . $this->keyWord . '%';
		return Unidad::Where('id','>',0)
			->where(function ($query) use ($keyWord) {
				$query
						->orWhere('unidad', 'LIKE', $keyWord)
						->orWhere('unidadI', 'LIKE', $keyWord)
						->orWhere('factorC', 'LIKE', $keyWord)
						->orWhere('grupo', 'LIKE', $keyWord);
			})
			->paginate(12);
	}

	public function render()
	{
		return view('livewire.unidads.view', [
			'unidads' => $this->filteredUnidads,
		]);
	}
	
    public function cancel()
    {
        $this->resetInput();
        $this->verModalUnidad = false;
    }

    public function resetInput()
    {
        $this->reset();
    }

    public function edit($id)
    {
        $this->selected_id = $id;
		$this->fill(Unidad::findOrFail($id)->toArray());
        $this->verModalUnidad = true;
    }
    public function create()
    {
        $this->resetInput();
        $this->verModalUnidad = true;
    }    
    public function save()
    {
        $this->validate([
		'unidad' => 'required',
		'unidadI' => 'required',
		'factorC' => 'required',
		'grupo' => 'required',
        ]);

        Unidad::updateOrCreate(
			['id' => $this->selected_id],
			[
				'unidad' => $this-> unidad,
				'unidadI' => $this-> unidadI,
				'factorC' => $this-> factorC,
				'grupo' => $this-> grupo
			]
		);
        $this->resetInput();
        $this->verModalUnidad = false;
    }
    public function paginationView()
    {
        return 'livewire.paginacionBase';
    }
    public function destroy($id)
    {
        if ($id) {
            Unidad::where('id', $id)->delete();
        }
    }
}