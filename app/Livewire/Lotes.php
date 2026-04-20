<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Lote;
use Livewire\Attributes\Computed;
use App\Models\Util;
use Illuminate\Support\Facades\DB;

class Lotes extends Component
{
    use WithPagination;

	protected $paginationTheme = 'bootstrap';
    public $verModalLote=false, $selected_id, $keyWord, $lote, $IdOrden;
	
	public $adicionales = [];
    public function updatedKeyWord()
	{
		$this->resetPage();
	}
    #[Computed]
	public function filteredLotes()
	{
		$keyWord = '%' . $this->keyWord . '%';
		return Lote::Where('id','>',0)
			->where(function ($query) use ($keyWord) {
				$query
						->orWhere('lote', 'LIKE', $keyWord)
						->orWhere('IdOrden', 'LIKE', $keyWord);
			})
			->paginate(12);
	}

	public function render()
	{
		return view('livewire.lotes.view', [
			'lotes' => $this->filteredLotes,
		]);
	}
	
    public function cancel()
    {
        $this->resetInput();
        $this->verModalLote = false;
    }

    public function resetInput()
    {
        $this->reset();
    }

    public function edit($id)
    {
        $this->selected_id = $id;
		$this->fill(Lote::findOrFail($id)->toArray());
        $this->verModalLote = true;
    }
    public function create()
    {
        $this->resetInput();
        $this->verModalLote = true;
    }    
    public function save()
    {
        $this->validate([
		'lote' => 'required',
		'IdOrden' => 'required',
        ]);

        Lote::updateOrCreate(
			['id' => $this->selected_id],
			[
				'lote' => $this-> lote,
				'IdOrden' => $this-> IdOrden
			]
		);
        $this->resetInput();
        $this->verModalLote = false;
    }
    public function paginationView()
    {
        return 'livewire.paginacionBase';
    }
    public function destroy($id)
    {
        if ($id) {
            Lote::where('id', $id)->delete();
        }
    }
}