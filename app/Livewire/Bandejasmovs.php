<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Bandejasmov;
use Livewire\Attributes\Computed;
use App\Models\{Util};
use Illuminate\Support\Facades\DB;

class Bandejasmovs extends Component
{
    use WithPagination;
	protected $paginationTheme = 'bootstrap';
    public $verModalBandejasmov=false, $selected_id, $keyWord, $IdBandeja, $IdProceso, $IdEmpleado, $pesoMetalEntrada, $pesoMetalSalida, $mermaMetal, $fechaH;
	
    public function mount(){}
    public function updatedKeyWord(){$this->resetPage();}
    #[Computed]
	public function filteredBandejasmovs()
	{
		$keyWord = '%' . $this->keyWord . '%';
		return Bandejasmov::Where('id','>',0)
			->where(function ($query) use ($keyWord) {
				$query
						->orWhere('IdBandeja', 'LIKE', $keyWord)
						->orWhere('IdProceso', 'LIKE', $keyWord)
						->orWhere('IdEmpleado', 'LIKE', $keyWord)
						->orWhere('pesoMetalEntrada', 'LIKE', $keyWord)
						->orWhere('pesoMetalSalida', 'LIKE', $keyWord)
						->orWhere('mermaMetal', 'LIKE', $keyWord)
						->orWhere('fechaH', 'LIKE', $keyWord);
			})
			->paginate(12);
	}
	public function render()
	{
		return view('livewire.bandejasmovs.view', [
			'bandejasmovs' => $this->filteredBandejasmovs,
		]);
	}
    public function cancel()
    {
        $this->resetInput();
        $this->verModalBandejasmov = false;
    }
    public function resetInput()
    {
        $this->reset();
    }
    public function edit($id)
    {
        $this->selected_id = $id;
		$this->fill(Bandejasmov::findOrFail($id)->toArray());
        $this->verModalBandejasmov = true;
    }
    public function create()
    {
        $this->resetInput();
        $this->verModalBandejasmov = true;
    }    
    public function save()
    {
        $this->validate([
		'IdBandeja' => 'required',
		'IdProceso' => 'required',
		'pesoMetalEntrada' => 'required',
		'pesoMetalSalida' => 'required',
		'mermaMetal' => 'required',
		'fechaH' => 'required',
        ]);

        Bandejasmov::updateOrCreate(
			['id' => $this->selected_id],
			[
				'IdBandeja' => $this-> IdBandeja,
				'IdProceso' => $this-> IdProceso,
				'IdEmpleado' => $this-> IdEmpleado,
				'pesoMetalEntrada' => $this-> pesoMetalEntrada,
				'pesoMetalSalida' => $this-> pesoMetalSalida,
				'mermaMetal' => $this-> mermaMetal,
				'fechaH' => $this-> fechaH
			]
		);
        $this->resetInput();
        $this->verModalBandejasmov = false;
    }
    public function paginationView()
    {
        return 'livewire.paginacionBase';
    }
    public function destroy($id)
    {
        if ($id) {
            Bandejasmov::where('id', $id)->delete();
        }
    }
}