<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Tipo;
use Livewire\Attributes\Computed;
use App\Models\Util;
use Illuminate\Support\Facades\DB;

class Tipos extends Component
{
    use WithPagination;

	protected $paginationTheme = 'bootstrap';
    public $verModalTipo=false, $selected_id, $keyWord, $tipo;
	
	public $adicionales = [];
    public function updatedKeyWord()
	{
		$this->resetPage();
	}
    #[Computed]
	public function filteredTipos()
	{
		$keyWord = '%' . $this->keyWord . '%';
		return Tipo::Where('id','>',0)
			->where(function ($query) use ($keyWord) {
				$query
						->orWhere('tipo', 'LIKE', $keyWord);
			})
			->paginate(12);
	}

	public function render()
	{
		return view('livewire.tipos.view', [
			'tipos' => $this->filteredTipos,
		]);
	}
	
    public function cancel()
    {
        $this->resetInput();
        $this->verModalTipo = false;
    }

    public function resetInput()
    {
        $this->reset();
    }

    public function edit($id)
    {
        $this->selected_id = $id;
		$this->fill(Tipo::findOrFail($id)->toArray());
        $this->verModalTipo = true;
    }
    public function create()
    {
        $this->resetInput();
        $this->verModalTipo = true;
    }    
    public function save()
    {
        $this->validate([
		'tipo' => 'required',
        ]);

        Tipo::updateOrCreate(
			['id' => $this->selected_id],
			[
				'tipo' => $this-> tipo
			]
		);
        $this->resetInput();
        $this->verModalTipo = false;
    }
    public function paginationView()
    {
        return 'livewire.paginacionBase';
    }
    public function destroy($id)
    {
        if ($id) {
            Tipo::where('id', $id)->delete();
        }
    }
}