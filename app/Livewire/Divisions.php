<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Division;
use Livewire\Attributes\Computed;
use App\Models\Util;
use Illuminate\Support\Facades\DB;

class Divisions extends Component
{
    use WithPagination;

	protected $paginationTheme = 'bootstrap';
    public $verModalDivision=false, $selected_id, $keyWord, $IdNegocio=1, $division;
	
    public function updatedKeyWord()
	{
		$this->resetPage();
	}
    #[Computed]
	public function filteredDivisions()
	{
		$keyWord = '%' . $this->keyWord . '%';
		return Division::Where('id','>',0)
			->where(function ($query) use ($keyWord) {
				$query
						->orWhere('IdNegocio', 'LIKE', $keyWord)
						->orWhere('division', 'LIKE', $keyWord);
			})
			->paginate(12);
	}

	public function render()
	{
		return view('livewire.divisions.view', [
			'divisions' => $this->filteredDivisions,
		]);
	}
	
    public function cancel()
    {
        $this->resetInput();
        $this->verModalDivision = false;
    }

    public function resetInput()
    {
        $this->resetExcept('IdNegocio');
    }

    public function edit($id)
    {
        $this->selected_id = $id;
		$this->fill(Division::findOrFail($id)->toArray());
        $this->verModalDivision = true;
    }
    public function create()
    {
        $this->resetInput();
        $this->verModalDivision = true;
    }    
    public function save()
    {
        $this->validate([
		'IdNegocio' => 'required',
		'division' => 'required',
        ]);

        Division::updateOrCreate(
			['id' => $this->selected_id],
			[
				'IdNegocio' => $this-> IdNegocio,
				'division' => $this-> division
			]
		);
        $this->resetInput();
        $this->verModalDivision = false;
    }

    public function destroy($id)
    {
        if ($id) {
            Division::where('id', $id)->delete();
        }
    }
}