<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Material;
use Livewire\Attributes\Computed;
use App\Models\Util;
use Illuminate\Support\Facades\DB;

class Materials extends Component
{
    use WithPagination;

	protected $paginationTheme = 'bootstrap';
    public $verModalMaterial=false, $selected_id, $keyWord, $IdClase, $IdUnidad, $IdUnidadP, $material, $materialI, $materialFiscal, $abreviatura;
	
	public $adicionales = [];
    public function updatedKeyWord()
	{
		$this->resetPage();
	}
    #[Computed]
	public function filteredMaterials()
	{
		$keyWord = '%' . $this->keyWord . '%';
		return Material::Where('id','>',0)
			->where(function ($query) use ($keyWord) {
				$query
						->orWhere('IdClase', 'LIKE', $keyWord)
						->orWhere('IdUnidad', 'LIKE', $keyWord)
						->orWhere('IdUnidadP', 'LIKE', $keyWord)
						->orWhere('material', 'LIKE', $keyWord)
						->orWhere('materialI', 'LIKE', $keyWord)
						->orWhere('materialFiscal', 'LIKE', $keyWord)
						->orWhere('abreviatura', 'LIKE', $keyWord);
			})
			->paginate(12);
	}

	public function render()
	{
		return view('livewire.materials.view', [
			'materials' => $this->filteredMaterials,
		]);
	}
	
    public function cancel()
    {
        $this->resetInput();
        $this->verModalMaterial = false;
    }

    public function resetInput()
    {
        $this->reset();
    }

    public function edit($id)
    {
        $this->selected_id = $id;
		$this->fill(Material::findOrFail($id)->toArray());
        $this->verModalMaterial = true;
    }
    public function create()
    {
        $this->resetInput();
        $this->verModalMaterial = true;
    }    
    public function save()
    {
        $this->validate([
		'IdClase' => 'required',
		'IdUnidad' => 'required',
		'IdUnidadP' => 'required',
		'material' => 'required',
		'materialI' => 'required',
		'materialFiscal' => 'required',
        ]);

        Material::updateOrCreate(
			['id' => $this->selected_id],
			[
				'IdClase' => $this-> IdClase,
				'IdUnidad' => $this-> IdUnidad,
				'IdUnidadP' => $this-> IdUnidadP,
				'material' => $this-> material,
				'materialI' => $this-> materialI,
				'materialFiscal' => $this-> materialFiscal,
				'abreviatura' => $this-> abreviatura
			]
		);
        $this->resetInput();
        $this->verModalMaterial = false;
    }
    public function paginationView()
    {
        return 'livewire.paginacionBase';
    }
    public function destroy($id)
    {
        if ($id) {
            Material::where('id', $id)->delete();
        }
    }
}