<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Forma;
use Livewire\Attributes\Computed;
use App\Models\Util;
use Illuminate\Support\Facades\DB;

class Formas extends Component
{
    use WithPagination;

	protected $paginationTheme = 'bootstrap';
    public $verModalForma=false, $selected_id, $keyWord, $forma;
	
    public function updatedKeyWord()
	{
		$this->resetPage();
	}
    #[Computed]
	public function filteredFormas()
	{
		$keyWord = '%' . $this->keyWord . '%';
		return Forma::Where('id','>',0)
			->where(function ($query) use ($keyWord) {
				$query
						->orWhere('forma', 'LIKE', $keyWord);
			})
			->paginate(12);
	}

	public function render()
	{
		return view('livewire.formas.view', [
			'formas' => $this->filteredFormas,
		]);
	}
	
    public function cancel()
    {
        $this->resetInput();
        $this->verModalForma = false;
    }

    public function resetInput()
    {
        $this->reset();
    }

    public function edit($id)
    {
        $this->selected_id = $id;
		$this->fill(Forma::findOrFail($id)->toArray());
        $this->verModalForma = true;
    }
    public function create()
    {
        $this->resetInput();
        $this->verModalForma = true;
    }    
    public function save()
    {
        $this->validate([
		'forma' => 'required',
        ]);

        Forma::updateOrCreate(
			['id' => $this->selected_id],
			[
				'forma' => $this-> forma
			]
		);
        $this->resetInput();
        $this->verModalForma = false;
    }
    public function paginationView()
    {
        return 'livewire.paginacionBase';
    }
    public function destroy($id)
    {
        if ($id) {
            Forma::where('id', $id)->delete();
        }
    }
}