<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Divsbodega;
use Livewire\Attributes\Computed;
use App\Models\Util;
use Illuminate\Support\Facades\DB;

class Divsbodegas extends Component
{
    use WithPagination;

	protected $paginationTheme = 'bootstrap';
    public $verModalDivsbodega=false, $selected_id, $keyWord, $IdDivision, $bodega;
	
    public function updatedKeyWord()
	{
		$this->resetPage();
	}
    #[Computed]
	public function filteredDivsbodegas()
	{
		$keyWord = '%' . $this->keyWord . '%';
		return Divsbodega::Where('id','>',0)
			->where(function ($query) use ($keyWord) {
				$query
						->orWhere('IdDivision', 'LIKE', $keyWord)
						->orWhere('bodega', 'LIKE', $keyWord);
			})
			->paginate(12);
	}

	public function render()
	{
		return view('livewire.divsbodegas.view', [
			'divsbodegas' => $this->filteredDivsbodegas,
		]);
	}
	
    public function cancel()
    {
        $this->resetInput();
        $this->verModalDivsbodega = false;
    }

    public function resetInput()
    {
        $this->reset();
    }

    public function edit($id)
    {
        $this->selected_id = $id;
		$this->fill(Divsbodega::findOrFail($id)->toArray());
        $this->verModalDivsbodega = true;
    }
    public function create()
    {
        $this->resetInput();
        $this->verModalDivsbodega = true;
    }    
    public function save()
    {
        $this->validate([
		'bodega' => 'required',
        ]);

        Divsbodega::updateOrCreate(
			['id' => $this->selected_id],
			[
				'IdDivision' => $this-> IdDivision,
				'bodega' => $this-> bodega
			]
		);
        $this->resetInput();
        $this->verModalDivsbodega = false;
    }

    public function destroy($id)
    {
        if ($id) {
            Divsbodega::where('id', $id)->delete();
        }
    }
}