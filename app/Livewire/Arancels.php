<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Arancel;
use Livewire\Attributes\Computed;
use App\Models\Util;
use Illuminate\Support\Facades\DB;

class Arancels extends Component
{
    use WithPagination;

	protected $paginationTheme = 'bootstrap';
    public $verModalArancel=false, $selected_id, $keyWord, $arancel, $arancelUSA, $descripcion, $IdPermiso;
	
    public function updatedKeyWord()
	{
		$this->resetPage();
	}
    #[Computed]
	public function filteredArancels()
	{
		$keyWord = '%' . $this->keyWord . '%';
		return Arancel::Where('id','>',0)
			->where(function ($query) use ($keyWord) {
				$query
						->orWhere('arancel', 'LIKE', $keyWord)
						->orWhere('arancelUSA', 'LIKE', $keyWord)
						->orWhere('descripcion', 'LIKE', $keyWord)
						->orWhere('IdPermiso', 'LIKE', $keyWord);
			})
			->paginate(12);
	}

	public function render()
	{
		return view('livewire.arancels.view', [
			'arancels' => $this->filteredArancels,
		]);
	}
	
    public function cancel()
    {
        $this->resetInput();
        $this->verModalArancel = false;
    }

    public function resetInput()
    {
        $this->reset();
    }

    public function edit($id)
    {
        $this->selected_id = $id;
		$this->fill(Arancel::findOrFail($id)->toArray());
        $this->verModalArancel = true;
    }
    public function create()
    {
        $this->resetInput();
        $this->verModalArancel = true;
    }    
    public function save()
    {
        $this->validate([
		'arancel' => 'required',
		'arancelUSA' => 'required',
		'descripcion' => 'required',
		'IdPermiso' => 'required',
        ]);

        Arancel::updateOrCreate(
			['id' => $this->selected_id],
			[
				'arancel' => $this-> arancel,
				'arancelUSA' => $this-> arancelUSA,
				'descripcion' => $this-> descripcion,
				'IdPermiso' => $this-> IdPermiso
			]
		);
        $this->resetInput();
        $this->verModalArancel = false;
    }
    public function paginationView()
    {
        return 'livewire.paginacionBase';
    }
    public function destroy($id)
    {
        if ($id) {
            Arancel::where('id', $id)->delete();
        }
    }
}