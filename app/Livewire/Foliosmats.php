<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Foliosmat;
use Livewire\Attributes\Computed;
use App\Models\{Util};
use Illuminate\Support\Facades\DB;

class Foliosmats extends Component
{
    use WithPagination;

	protected $paginationTheme = 'bootstrap';
    public $verModalFoliosmat=false, $selected_id, $keyWord, $IdFolio, $IdFacImportsDet, $IdMaterial, $cantidad, $pesoG, $integrado;
	
	public $adicionales = [];
    public function mount()
    {
    }    
    public function updatedKeyWord()
	{
		$this->resetPage();
	}
    #[Computed]
	public function filteredFoliosmats()
	{
		$keyWord = '%' . $this->keyWord . '%';
		return Foliosmat::Where('IdFolio', $this->IdFolio)
			->where(function ($query) use ($keyWord) {
				$query
                    ->orWhere('cantidad', 'LIKE', $keyWord)
                    ->orWhere('pesoG', 'LIKE', $keyWord)
                    ->orWhere('integrado', 'LIKE', $keyWord);
			})
			->paginate(12);
	}

	public function render()
	{
		return view('livewire.foliosmats.view', [
			'foliosmats' => $this->filteredFoliosmats,
		]);
	}
	
    public function cancel()
    {
        $this->resetInput();
        $this->verModalFoliosmat = false;
    }

    public function resetInput()
    {
        $this->reset();
    }

    public function edit($id)
    {
        $this->selected_id = $id;
		$this->fill(Foliosmat::findOrFail($id)->toArray());
        $this->verModalFoliosmat = true;
    }
    public function create()
    {
        $this->resetInput();
        $this->verModalFoliosmat = true;
    }    
    public function save()
    {
        $this->validate([
		'IdFolio' => 'required',
		'IdMaterial' => 'required',
		'cantidad' => 'required',
		'pesoG' => 'required',
		'integrado' => 'required',
        ]);

        Foliosmat::updateOrCreate(
			['id' => $this->selected_id],
			[
				'IdFolio' => $this-> IdFolio,
				'IdFacImportsDet' => $this-> IdFacImportsDet,
				'IdMaterial' => $this-> IdMaterial,
				'cantidad' => $this-> cantidad,
				'pesoG' => $this-> pesoG,
				'integrado' => $this-> integrado
			]
		);
        $this->resetInput();
        $this->verModalFoliosmat = false;
    }
    public function paginationView()
    {
        return 'livewire.paginacionBase';
    }
    public function destroy($id)
    {
        if ($id) {
            Foliosmat::where('id', $id)->delete();
        }
    }
}