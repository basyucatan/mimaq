<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Permiso;
use Livewire\Attributes\Computed;
use App\Models\Util;
use Illuminate\Support\Facades\DB;

class Permisos extends Component
{
    use WithPagination;

	protected $paginationTheme = 'bootstrap';
    public $verModalPermiso=false, $selected_id, $keyWord, $fecha, $permiso;
	
    public function updatedKeyWord()
	{
		$this->resetPage();
	}
    #[Computed]
	public function filteredPermisos()
	{
		$keyWord = '%' . $this->keyWord . '%';
		return Permiso::Where('id','>',0)
			->where(function ($query) use ($keyWord) {
				$query
						->orWhere('fecha', 'LIKE', $keyWord)
						->orWhere('permiso', 'LIKE', $keyWord);
			})
			->paginate(12);
	}

	public function render()
	{
		return view('livewire.permisos.view', [
			'permisos' => $this->filteredPermisos,
		]);
	}
	
    public function cancel()
    {
        $this->resetInput();
        $this->verModalPermiso = false;
    }

    public function resetInput()
    {
        $this->reset();
    }

    public function edit($id)
    {
        $this->selected_id = $id;
		$this->fill(Permiso::findOrFail($id)->toArray());
        $this->verModalPermiso = true;
    }
    public function create()
    {
        $this->resetInput();
        $this->verModalPermiso = true;
    }    
    public function save()
    {
        $this->validate([
		'fecha' => 'required',
		'permiso' => 'required',
        ]);

        Permiso::updateOrCreate(
			['id' => $this->selected_id],
			[
				'fecha' => $this-> fecha,
				'permiso' => $this-> permiso
			]
		);
        $this->resetInput();
        $this->verModalPermiso = false;
    }
    public function paginationView()
    {
        return 'livewire.paginacionBase';
    }
    public function destroy($id)
    {
        if ($id) {
            Permiso::where('id', $id)->delete();
        }
    }
}