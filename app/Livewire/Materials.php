<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Material;
use Livewire\Attributes\Computed;
use App\Models\Util;
use Illuminate\Support\Facades\DB;
use Livewire\WithFileUploads;

class Materials extends Component
{
    use WithPagination, WithFileUploads;

	protected $paginationTheme = 'bootstrap';
    public $verModalMaterial=false, $selected_id, $keyWord, $IdClase, $IdLinea, 
		$fotoSubida, $fotoUrl, $anchoLama, $IdMarca,
		$IdUnidad, $IdTipo, $referencia, $material, $foto, $KgxMetro, $rendimiento, 
		$IdUnidadRend, $adicionales;
	public $unidads = [], $tipos = [], $marcas = [], $lineas = [], $clases = [];
    public function updatedKeyWord()
	{
		$this->resetPage();
	}
    #[Computed]
	public function filteredMaterials()
	{
		$keyWord = '%' . $this->keyWord . '%';
		return Material::Where('id', $this->selected_id)
			->where(function ($query) use ($keyWord) {
				$query
						->orWhere('IdClase', 'LIKE', $keyWord)
						->orWhere('IdLinea', 'LIKE', $keyWord)
						->orWhere('IdUnidad', 'LIKE', $keyWord)
						->orWhere('IdTipo', 'LIKE', $keyWord)
						->orWhere('referencia', 'LIKE', $keyWord)
						->orWhere('material', 'LIKE', $keyWord)
						->orWhere('foto', 'LIKE', $keyWord)
						->orWhere('KgxMetro', 'LIKE', $keyWord)
						->orWhere('rendimiento', 'LIKE', $keyWord)
						->orWhere('IdUnidadRend', 'LIKE', $keyWord)
						->orWhere('adicionales', 'LIKE', $keyWord);
			})
			->paginate(12);
	}

	public function render()
	{
		return view('livewire.materials.view', [
			'materials' => $this->filteredMaterials,
		]);
	}
	public function mount(){
		$this->marcas = Util::getArray('marcas');
		$this->lineas = Util::getArray('lineas');
		$this->clases = Util::getArray('clases');
		$this->unidads = Util::getArray('unidads');
		$this->tipos = Util::getArray('tipos');
	}
    public function cancel()
    {
        $this->resetInput();
        $this->verModalMaterial = false;
    }

    public function resetInput()
    {
        $this->resetExcept('selected_id',
			'clases','marcas','lineas','unidads','tipos');
    }

public function edit($id)
{
    $material = Material::findOrFail($id);
    $this->fill($material->toArray());
    $this->adicionales = $material->adicionales ?? [];
    $this->anchoLama = $this->adicionales['anchoLama'] ?? null;
    $this->IdMarca = $material->linea->IdMarca;
    $this->fotoUrl = $material->fotoUrl . '?v=' . time();
    $this->fotoSubida = null;
    $this->verModalMaterial = true;
}
    public function create()
    {
        $this->resetInput();
        $this->verModalMaterial = true;
		$this->selected_id = null;
    }    

    public function save()
    {
        $this->validate([
		'referencia' => 'required',
		'IdClase' => 'required',
		'IdUnidad' => 'required',
		'material' => 'required',
        ]);
        $adicionales = [
            'anchoLama' => $this->anchoLama
        ];
        $archivoFoto = $this->foto;
        $Linea = \App\Models\Linea::find($this->IdLinea);
        $marcaNombre = $Linea->Marca->marca ?? 'generico';
        $this->fotoUrl = "materiales/" . strtolower(mb_convert_encoding($marcaNombre, 'UTF-8', 'UTF-8'));
		if ($this->fotoSubida) {
			if ($this->selected_id && $this->foto) {
				Util::borrarArchivo($this->fotoUrl, $this->foto); 
			}
			$archivoFoto = Util::guardarFoto($this->fotoSubida, $this->material, $this->fotoUrl);
		}
        $adicionales = [
            'anchoLama' => $this->anchoLama
        ];		
        $material = Material::updateOrCreate(
			['id' => $this->selected_id],
			[
				'IdClase' => $this-> IdClase,
				'IdLinea' => $this-> IdLinea,
				'IdUnidad' => $this-> IdUnidad,
				'IdTipo' => $this-> IdTipo,
				'referencia' => $this-> referencia,
				'material' => $this-> material,
				'foto' => $archivoFoto,
				'KgxMetro' => $this-> KgxMetro,
				'rendimiento' => $this-> rendimiento,
				'IdUnidadRend' => $this-> IdUnidadRend,
				'adicionales' => $adicionales
			]
		);
		$this->selected_id = $material->id;
        $this->resetInput();
        $this->verModalMaterial = false;
    }

    public function destroy($id)
    {
        if ($id) {
            Material::where('id', $id)->delete();
        }
    }
}