<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Existencia;
use Livewire\Attributes\Computed;
use App\Models\{Util};
use Illuminate\Support\Facades\DB;

class Existencias extends Component
{
    use WithPagination;
	protected $paginationTheme = 'bootstrap';
    public $verModalExistencia=false, $selected_id, $keyWord, $IdFacImportsDet, $IdDepto, $cantidad, $pesoG;
	
    public function mount(){}
    public function updatedKeyWord(){$this->resetPage();}
    #[Computed]
	public function filteredExistencias()
	{
		$keyWord = '%' . $this->keyWord . '%';
		return Existencia::Where('id','>',0)
			->where(function ($query) use ($keyWord) {
				$query
						->orWhere('IdFacImportsDet', 'LIKE', $keyWord)
						->orWhere('IdDepto', 'LIKE', $keyWord)
						->orWhere('cantidad', 'LIKE', $keyWord)
						->orWhere('pesoG', 'LIKE', $keyWord);
			})
			->paginate(12);
	}
	public function render()
	{
		return view('livewire.existencias.view', [
			'existencias' => $this->filteredExistencias,
		]);
	}
    public function cancel()
    {
        $this->resetInput();
        $this->verModalExistencia = false;
    }
    public function resetInput()
    {
        $this->reset();
    }
    public function edit($id)
    {
        $this->selected_id = $id;
		$this->fill(Existencia::findOrFail($id)->toArray());
        $this->verModalExistencia = true;
    }
    public function create()
    {
        $this->resetInput();
        $this->verModalExistencia = true;
    }    
    public function save()
    {
        $this->validate([
		'IdFacImportsDet' => 'required',
		'IdDepto' => 'required',
		'cantidad' => 'required',
		'pesoG' => 'required',
        ]);

        Existencia::updateOrCreate(
			['id' => $this->selected_id],
			[
				'IdFacImportsDet' => $this-> IdFacImportsDet,
				'IdDepto' => $this-> IdDepto,
				'cantidad' => $this-> cantidad,
				'pesoG' => $this-> pesoG
			]
		);
        $this->resetInput();
        $this->verModalExistencia = false;
    }
    public function paginationView()
    {
        return 'livewire.paginacionBase';
    }
    public function destroy($id)
    {
        if ($id) {
            Existencia::where('id', $id)->delete();
        }
    }
}