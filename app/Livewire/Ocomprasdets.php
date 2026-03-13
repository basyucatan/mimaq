<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Ocomprasdet;
use Livewire\Attributes\Computed;
use App\Models\Util;
use Illuminate\Support\Facades\DB;

class Ocomprasdets extends Component
{
    use WithPagination;

	protected $paginationTheme = 'bootstrap';
    public $verModalOcomprasdet=false, $selected_id, $keyWord, $IdOCompra, $IdMatCosto, $cantidad, $costoU;
	
    public function updatedKeyWord()
	{
		$this->resetPage();
	}
    #[Computed]
	public function filteredOcomprasdets()
	{
		$keyWord = '%' . $this->keyWord . '%';
		return Ocomprasdet::Where('id','>',0)
			->where(function ($query) use ($keyWord) {
				$query
						->orWhere('IdOCompra', 'LIKE', $keyWord)
						->orWhere('IdMatCosto', 'LIKE', $keyWord)
						->orWhere('cantidad', 'LIKE', $keyWord)
						->orWhere('costoU', 'LIKE', $keyWord);
			})
			->paginate(12);
	}

	public function render()
	{
		return view('livewire.ocomprasdets.view', [
			'ocomprasdets' => $this->filteredOcomprasdets,
		]);
	}
	
    public function cancel()
    {
        $this->resetInput();
        $this->verModalOcomprasdet = false;
    }

    public function resetInput()
    {
        $this->reset();
    }

    public function edit($id)
    {
        $this->selected_id = $id;
		$this->fill(Ocomprasdet::findOrFail($id)->toArray());
        $this->verModalOcomprasdet = true;
    }
    public function create()
    {
        $this->resetInput();
        $this->verModalOcomprasdet = true;
    }    
    public function save()
    {
        $this->validate([
		'IdOCompra' => 'required',
		'IdMatCosto' => 'required',
        ]);

        Ocomprasdet::updateOrCreate(
			['id' => $this->selected_id],
			[
				'IdOCompra' => $this-> IdOCompra,
				'IdMatCosto' => $this-> IdMatCosto,
				'cantidad' => $this-> cantidad,
				'costoU' => $this-> costoU
			]
		);
        $this->resetInput();
        $this->verModalOcomprasdet = false;
    }

    public function destroy($id)
    {
        if ($id) {
            Ocomprasdet::where('id', $id)->delete();
        }
    }
}