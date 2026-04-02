<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Clase;
use Livewire\Attributes\Computed;
use App\Models\Util;
use Illuminate\Support\Facades\DB;

class Clases extends Component
{
    use WithPagination;

	protected $paginationTheme = 'bootstrap';
    public $verModalClase=false, $selected_id, $keyWord, $clase, $adicionales;
	
    public function updatedKeyWord()
	{
		$this->resetPage();
	}
    #[Computed]
	public function filteredClases()
	{
		$keyWord = '%' . $this->keyWord . '%';
		return Clase::Where('id','>',0)
			->where(function ($query) use ($keyWord) {
				$query
						->orWhere('clase', 'LIKE', $keyWord)
						->orWhere('adicionales', 'LIKE', $keyWord);
			})
			->paginate(12);
	}

	public function render()
	{
		return view('livewire.clases.view', [
			'clases' => $this->filteredClases,
		]);
	}
	
    public function cancel()
    {
        $this->resetInput();
        $this->verModalClase = false;
    }

    public function resetInput()
    {
        $this->reset();
    }

    public function edit($id)
    {
        $this->selected_id = $id;
		$this->fill(Clase::findOrFail($id)->toArray());
        $this->verModalClase = true;
    }
    public function create()
    {
        $this->resetInput();
        $this->verModalClase = true;
    }    
    public function save()
    {
        $this->validate([
		'clase' => 'required',
        ]);

        Clase::updateOrCreate(
			['id' => $this->selected_id],
			[
				'clase' => $this-> clase,
				'adicionales' => $this-> adicionales
			]
		);
        $this->resetInput();
        $this->verModalClase = false;
    }
    public function paginationView()
    {
        return 'livewire.paginacionBase';
    }
    public function destroy($id)
    {
        if ($id) {
            Clase::where('id', $id)->delete();
        }
    }
}