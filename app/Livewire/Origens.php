<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Origen;
use Livewire\Attributes\Computed;
use App\Models\Util;
use Illuminate\Support\Facades\DB;

class Origens extends Component
{
    use WithPagination;

	protected $paginationTheme = 'bootstrap';
    public $verModalOrigen=false, $selected_id, $keyWord, $origen;
	
    public function updatedKeyWord()
	{
		$this->resetPage();
	}
    #[Computed]
	public function filteredOrigens()
	{
		$keyWord = '%' . $this->keyWord . '%';
		return Origen::Where('id','>',0)
			->where(function ($query) use ($keyWord) {
				$query
						->orWhere('origen', 'LIKE', $keyWord);
			})
			->paginate(12);
	}

	public function render()
	{
		return view('livewire.origens.view', [
			'origens' => $this->filteredOrigens,
		]);
	}
	
    public function cancel()
    {
        $this->resetInput();
        $this->verModalOrigen = false;
    }

    public function resetInput()
    {
        $this->reset();
    }

    public function edit($id)
    {
        $this->selected_id = $id;
		$this->fill(Origen::findOrFail($id)->toArray());
        $this->verModalOrigen = true;
    }
    public function create()
    {
        $this->resetInput();
        $this->verModalOrigen = true;
    }    
    public function save()
    {
        $this->validate([
		'origen' => 'required',
        ]);

        Origen::updateOrCreate(
			['id' => $this->selected_id],
			[
				'origen' => $this-> origen
			]
		);
        $this->resetInput();
        $this->verModalOrigen = false;
    }
    public function paginationView()
    {
        return 'livewire.paginacionBase';
    }
    public function destroy($id)
    {
        if ($id) {
            Origen::where('id', $id)->delete();
        }
    }
}