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
    public $verModalBandejasmov=false, $selected_id, $keyWord, $IdBandeja, $IdProceso, $IdEmpleado, $pesoEntrada, $pesoSalida, $fechaHEntrada, $fechaHSalida;
	
    public function mount(){}
    public function updatedKeyWord(){$this->resetPage();}
    #[Computed]
	public function filteredBandejasmovs()
	{
		$keyWord = '%' . $this->keyWord . '%';
		return Bandejasmov::Where('IdBandeja',$this->IdBandeja)
			->get();
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
		'pesoEntrada' => 'required',
		'fechaHEntrada' => 'required',
		'fechaHSalida' => 'required',
        ]);

        Bandejasmov::updateOrCreate(
			['id' => $this->selected_id],
			[
				'IdBandeja' => $this-> IdBandeja,
				'IdProceso' => $this-> IdProceso,
                'IdUser' => Auth()->user()->id,
				'IdEmpleado' => $this-> IdEmpleado,
				'pesoEntrada' => $this-> pesoEntrada,
				'pesoSalida' => $this-> pesoSalida,
				'fechaHEntrada' => $this-> fechaHEntrada,
				'fechaHSalida' => $this-> fechaHSalida
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