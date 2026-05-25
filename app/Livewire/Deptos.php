<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Depto;
use Livewire\Attributes\Computed;
class Deptos extends Component
{
    use WithPagination;

	protected $paginationTheme = 'bootstrap';
    public $verModalDepto=false, $selected_id, $keyWord, $depto, $deptoI, $orden;
	
    public function updatedKeyWord()
	{
		$this->resetPage();
	}
    #[Computed]
	public function filteredDeptos()
	{
		$keyWord = '%' . $this->keyWord . '%';
		return Depto::Where('id','>',0)
			->where(function ($query) use ($keyWord) {
				$query
						->orWhere('depto', 'LIKE', $keyWord);
			})
			->paginate(12);
	}

	public function render()
	{
		return view('livewire.deptos.view', [
			'deptos' => $this->filteredDeptos,
		]);
	}
	
    public function cancel()
    {
        $this->resetInput();
        $this->verModalDepto = false;
    }

    public function resetInput()
    {
        $this->resetExcept('selected_id');
    }

    public function edit($id)
    {
        $this->selected_id = $id;
		$this->fill(Depto::findOrFail($id)->toArray());
        $this->verModalDepto = true;
    }
    public function create()
    {
        $this->resetInput();
        $this->verModalDepto = true;
    }    
    public function save()
    {
        $this->validate([
		'depto' => 'required',
		'deptoI' => 'required',
		'orden' => 'required',
        ]);
        Depto::updateOrCreate(
			['id' => $this->selected_id],
			[
                'depto' => strtoupper($this-> depto),
                'deptoI' => strtoupper($this-> deptoI),
				'orden' => $this-> orden
			]
		);
        $this->resetInput();
        $this->verModalDepto = false;
    }
    public function paginationView()
    {
        return 'livewire.paginacionBase';
    }
    public function destroy($id)
    {
        if ($id) {
            Depto::where('id', $id)->delete();
        }
    }
}