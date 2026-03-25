<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Tablaherrajesdet;
use Livewire\Attributes\Computed;
use Illuminate\Support\Facades\DB;

class Tablaherrajesdets extends Component
{
    use WithPagination;

	protected $paginationTheme = 'bootstrap';
    public $verModalTablaherrajesdet=false, $selected_id, $keyWord, 
	$IdTablaHerraje, $cantidad, $IdMaterial, $rangoMenor, $tablaHerraje,
	$rangoMayor, $factorExtra, $adicionales, $material;
	protected $listeners = ['IdArbolAgregar' => 'agregarMaterial'];
	public function updatingKeyWord()
	{
		$this->resetPage();
	}
    #[Computed]
	public function filteredTablaherrajesdets()
	{
		$keyWord = '%' . $this->keyWord . '%';
		return Tablaherrajesdet::Where('IdTablaHerraje', $this->IdTablaHerraje)
			->where(function ($query) use ($keyWord) {
				$query
						->orWhere('cantidad', 'LIKE', $keyWord)
						->orWhere('rangoMenor', 'LIKE', $keyWord)
						->orWhere('rangoMayor', 'LIKE', $keyWord)
						->orWhere('adicionales', 'LIKE', $keyWord);
			})
			->paginate(18);
	}

	public function mount(){
		if($this->IdTablaHerraje){
			$this->tablaHerraje = DB::table('tablaherrajes')->find($this->IdTablaHerraje)->tablaHerraje;
		}
	}
	public function render()
	{
		return view('livewire.tablaherrajesdets.view', [
			'tablaherrajesdets' => $this->filteredTablaherrajesdets,
		]);
	}
	
    public function cancel()
    {
        $this->resetInput();
        $this->verModalTablaherrajesdet = false;
    }

    public function edit($id)
    {
        $this->selected_id = $id;
		$this->fill(Tablaherrajesdet::findOrFail($id)->toArray());
		$this->material = DB::table('materials')->find($this->IdMaterial);
        $this->verModalTablaherrajesdet = true;
    }
			
    public function create()
    {
        $this->resetInput();
        $this->verModalTablaherrajesdet = true;
    }    
	public function resetInput(){
		$this->resetExcept('IdTablaHerraje', 'tablaHerraje');
	}
	public function agregarMaterial($tipo, $id){
		if ($tipo != 'Material') return;
		$this->resetInput();
		$this->IdMaterial = $id;
		$this->material = DB::table('materials')->find($id);
		$this->cantidad = 1;
		$this->rangoMenor = 0;
		$this->rangoMayor = 1000;
		$this->factorExtra = null;
		$this->verModalTablaherrajesdet = true;
	}
    public function save()
    {
		if(!$this->IdTablaHerraje){
			$this->verModalTablaherrajesdet = false;
			$this->dispatch('sweetalert', \App\Helpers\SweetAlert::mensaje(
				'Selecciona una tabla de Herrajes',
				1200,
				'warning'
			));			
		}
        $this->validate([
		'IdTablaHerraje' => 'required',
		'cantidad' => 'required',
        ]);

        Tablaherrajesdet::updateOrCreate(
			['id' => $this->selected_id],
			[
				'IdTablaHerraje' => $this-> IdTablaHerraje,
				'cantidad' => $this-> cantidad,
				'IdMaterial' => $this-> IdMaterial,
				'rangoMenor' => $this-> rangoMenor,
				'rangoMayor' => $this-> rangoMayor,
				'factorExtra' => $this-> factorExtra,				
				'adicionales' => $this-> adicionales
			]
		);
        $this->resetInput();
        $this->verModalTablaherrajesdet = false;
    }
    public function destroy($id)
    {
        if ($id) {
            Tablaherrajesdet::where('id', $id)->delete();
        }
    }
    public function paginationView()
    {
        return 'livewire.paginacionBase';
    }	
}