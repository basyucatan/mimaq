<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Clase;
use Livewire\Attributes\Computed;
use App\Models\Util;
class Clases extends Component
{
    use WithPagination;

	protected $paginationTheme = 'bootstrap';
    public $verModalClase=false, $selected_id, $keyWord, $IdAccess, $IdTipo, $IdArancel, $clase, $claseI;
	public $adicionales = [], $arancels=[], $tipos = [];
    public function mount()
    {
        $this->arancels = Util::getArray('arancels');
        $this->tipos = Util::getArray('tipos');
    }    
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
                    ->orWhere('IdAccess', 'LIKE', $keyWord)
                    ->orWhere('clase', 'LIKE', $keyWord)
                    ->orWhere('claseI', 'LIKE', $keyWord);
			})
			->get();
	}

	public function render()
	{
		return view('livewire.clases.view', ['clases' => $this->filteredClases,]);
	}
    public function cancel()
    {
        $this->resetInput();
        $this->verModalClase = false;
    }
    public function resetInput()
    {
        $this->resetExcept('tipos','arancels');
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
		'IdAccess' => 'required',
		'IdTipo' => 'required',
		'IdArancel' => 'required',
		'clase' => 'required',
		'claseI' => 'required',
        ]);

        Clase::updateOrCreate(
			['id' => $this->selected_id],
			[
				'IdAccess' => $this-> IdAccess,
				'IdTipo' => $this-> IdTipo,
				'IdArancel' => $this-> IdArancel,
				'clase' =>  strtoupper($this->clase),
				'claseI' =>  strtoupper($this->claseI)
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