<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Tablaherraje;
use App\Models\Modelo;
use Livewire\Attributes\Computed;
use App\Models\Util;
use Illuminate\Support\Facades\DB;

class Tablaherrajes extends Component
{
    use WithPagination;

	protected $paginationTheme = 'bootstrap';
    public $marcas = [],$lineas = [], $modelos = [];
    public $verModalTablaherraje=false, $selected_id, $keyWord, $tabActivo = 'tab1',
    $IdMarca, $IdLinea, $tablaHerraje, $fichaTecnica;

	public function updatingKeyWord()
	{
		$this->resetPage();
	}

    #[Computed]
    public function filteredTablaherrajes()
    {
        $keyWord = '%' . $this->keyWord . '%';

        return Tablaherraje::where('id', '>', 0)
            ->where(function ($query) use ($keyWord) {
                $query->orWhere('tablaHerraje', 'LIKE', $keyWord)
                    ->orWhere('fichaTecnica', 'LIKE', $keyWord)
                    // Filtrar por Linea->linea
                    ->orWhereHas('Linea', function ($q) use ($keyWord) {
                        $q->where('linea', 'LIKE', $keyWord)
                            // Filtrar también por Linea->Marca->marca
                            ->orWhereHas('Marca', function ($q2) use ($keyWord) {
                                $q2->where('marca', 'LIKE', $keyWord);
                            });
                    });
            })
            ->paginate(6);
    }


public $tablasdeps = []; // Propiedad pública para persistencia

public function render()
{
    if ($this->selected_id) {
        $this->tablasdeps = Modelo::whereHas('modelosmats', function ($query) {
                $query->where('IdTablaHerraje', $this->selected_id);
            })
            ->orderBy('IdLinea')
            ->orderBy('modelo')
            ->get();
    } else {
        $this->tablasdeps = [];
    }
    return view('livewire.tablaherrajes.view', [
        'tablaherrajes' => $this->filteredTablaherrajes,
        'modelos' => $this->tablasdeps
    ]);
}
	
	public function mount()
	{
		$this->cargarArrays();
	}    

    public function cargarArrays()
    {
        $this->marcas = Util::getArray('marcas');
        $this->lineas = Util::getArray('lineas');
    }    

    public function elegirMarca()
    {
        if(!empty($this->IdMarca)){
            $this->lineas = DB::table('lineas')
                ->where('IdMarca', $this->IdMarca)
                ->pluck('linea', 'id');           
        }
    }     
    
    public function cancel()
    {
        $this->resetInput();
        $this->verModalTablaherraje = false;
    }
    public function resetInput()
    {
        $this->resetExcept('marcas','lineas', 'selected_id', 'keyWord');
    }
    public function edit($id)
    {
        $this->selected_id = $id;
		$this->fill(Tablaherraje::findOrFail($id)->toArray());
        $this->IdMarca = DB::table('lineas')->where('id', $this->IdLinea)->first()->IdMarca;
        $this->elegirMarca();
        $this->verModalTablaherraje = true;
    }
    public function elegir($id)
    {
        $this->selected_id = $id;
    }    
    public function create()
    {
        $this->resetInput();
        $this->selected_id = null;
        $this->verModalTablaherraje = true;
    }    
    public function save()
    {
        $this->validate([
		'IdLinea' => 'required',
		'tablaHerraje' => 'required',
        ]);
        Tablaherraje::updateOrCreate(
			['id' => $this->selected_id],
			[
				'IdLinea' => $this-> IdLinea,
				'tablaHerraje' => $this-> tablaHerraje,
				'fichaTecnica' => $this-> fichaTecnica
			]
		);
        $this->resetInput();
        $this->verModalTablaherraje = false;
    }

    public function destroy($id)
    {
        if ($id) {
            Tablaherraje::where('id', $id)->delete();
        }
    }
    public function paginationView()
    {
        return 'livewire.paginacionBase';
    }    
}