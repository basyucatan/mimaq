<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Proceso;
use Livewire\Attributes\Computed;
use App\Models\{Util};
use Illuminate\Support\Facades\DB;

class Procesos extends Component
{
    use WithPagination;
	protected $paginationTheme = 'bootstrap';
    public $verModalProceso=false, $selected_id, $keyWord, $proceso, $procesoI, $IdDepto, $PMaxMerma;
    public function mount(){}
    public function updatedKeyWord(){$this->resetPage();}
    #[Computed]
	public function filteredProcesos()
	{
		$keyWord = '%' . $this->keyWord . '%';
		return Proceso::Where('IdDepto',$this->IdDepto)
			->where(function ($query) use ($keyWord) {
				$query
						->orWhere('proceso', 'LIKE', $keyWord)
						->orWhere('procesoI', 'LIKE', $keyWord)
						->orWhere('IdDepto', 'LIKE', $keyWord)
						->orWhere('PMaxMerma', 'LIKE', $keyWord);
			})
			->paginate(12);
	}
	public function render()
	{
		return view('livewire.procesos.view', [
			'procesos' => $this->filteredProcesos,
		]);
	}
    public function cancel()
    {
        $this->resetInput();
        $this->verModalProceso = false;
    }
    public function resetInput()
    {
        $this->resetExcept('IdDepto');
    }
    public function edit($id)
    {
        $this->selected_id = $id;
		$this->fill(Proceso::findOrFail($id)->toArray());
        $this->verModalProceso = true;
    }
    public function create()
    {
        $this->resetInput();
        $this->verModalProceso = true;
    }    
    public function save()
    {
        $this->validate([
		'proceso' => 'required',
		'procesoI' => 'required',
		'IdDepto' => 'required',
		'PMaxMerma' => 'required',
        ]);

        Proceso::updateOrCreate(
			['id' => $this->selected_id],
			[
                'proceso' => strtoupper($this->proceso),
                'procesoI' => strtoupper($this->procesoI),
				'IdDepto' => $this-> IdDepto,
				'PMaxMerma' => $this-> PMaxMerma
			]
		);
        $this->resetInput();
        $this->verModalProceso = false;
    }
    public function paginationView()
    {
        return 'livewire.paginacionBase';
    }
    public function destroy($id)
    {
        if ($id) {
            Proceso::where('id', $id)->delete();
        }
    }
}