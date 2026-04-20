<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Estilo;
use Livewire\Attributes\Computed;
use App\Models\Util;
use Illuminate\Support\Facades\DB;

class Estilos extends Component
{
    use WithPagination;

	protected $paginationTheme = 'bootstrap';
    public $verModalEstilo=false, $selected_id, $keyWord, $estilo, $IdClase, $foto;
	
    public function updatedKeyWord()
	{
		$this->resetPage();
	}
    #[Computed]
	public function filteredEstilos()
	{
		$keyWord = '%' . $this->keyWord . '%';
		return Estilo::Where('id','>',0)
			->where(function ($query) use ($keyWord) {
				$query
						->orWhere('estilo', 'LIKE', $keyWord)
						->orWhere('IdClase', 'LIKE', $keyWord)
						->orWhere('foto', 'LIKE', $keyWord);
			})
			->paginate(12);
	}

	public function render()
	{
		return view('livewire.estilos.view', [
			'estilos' => $this->filteredEstilos,
		]);
	}
	
    public function cancel()
    {
        $this->resetInput();
        $this->verModalEstilo = false;
    }

    public function resetInput()
    {
        $this->reset();
    }

    public function edit($id)
    {
        $this->selected_id = $id;
		$this->fill(Estilo::findOrFail($id)->toArray());
        $this->verModalEstilo = true;
    }
    public function create()
    {
        $this->resetInput();
        $this->verModalEstilo = true;
    }    
    public function save()
    {
        $this->validate([
		'estilo' => 'required',
		'IdClase' => 'required',
		'foto' => 'required',
        ]);

        Estilo::updateOrCreate(
			['id' => $this->selected_id],
			[
				'estilo' => $this-> estilo,
				'IdClase' => $this-> IdClase,
				'foto' => $this-> foto
			]
		);
        $this->resetInput();
        $this->verModalEstilo = false;
    }
    public function paginationView()
    {
        return 'livewire.paginacionBase';
    }
    public function destroy($id)
    {
        if ($id) {
            Estilo::where('id', $id)->delete();
        }
    }
}