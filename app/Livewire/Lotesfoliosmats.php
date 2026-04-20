<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Lotesfoliosmat;
use Livewire\Attributes\Computed;
use App\Models\Util;
use Illuminate\Support\Facades\DB;

class Lotesfoliosmats extends Component
{
    use WithPagination;

	protected $paginationTheme = 'bootstrap';
    public $verModalLotesfoliosmat=false, $selected_id, $keyWord, $IdLotesFolio, $IdFacImportsDet, $cantidad, $IdMaterial, $pesoEnUMat, $IdSize, $IdForma;
	
	public $adicionales = [];
    public function updatedKeyWord()
	{
		$this->resetPage();
	}
    #[Computed]
	public function filteredLotesfoliosmats()
	{
		$keyWord = '%' . $this->keyWord . '%';
		return Lotesfoliosmat::Where('id','>',0)
			->where(function ($query) use ($keyWord) {
				$query
						->orWhere('IdLotesFolio', 'LIKE', $keyWord)
						->orWhere('IdFacImportsDet', 'LIKE', $keyWord)
						->orWhere('cantidad', 'LIKE', $keyWord)
						->orWhere('IdMaterial', 'LIKE', $keyWord)
						->orWhere('pesoEnUMat', 'LIKE', $keyWord)
						->orWhere('IdSize', 'LIKE', $keyWord)
						->orWhere('IdForma', 'LIKE', $keyWord);
			})
			->paginate(12);
	}

	public function render()
	{
		return view('livewire.lotesfoliosmats.view', [
			'lotesfoliosmats' => $this->filteredLotesfoliosmats,
		]);
	}
	
    public function cancel()
    {
        $this->resetInput();
        $this->verModalLotesfoliosmat = false;
    }

    public function resetInput()
    {
        $this->reset();
    }

    public function edit($id)
    {
        $this->selected_id = $id;
		$this->fill(Lotesfoliosmat::findOrFail($id)->toArray());
        $this->verModalLotesfoliosmat = true;
    }
    public function create()
    {
        $this->resetInput();
        $this->verModalLotesfoliosmat = true;
    }    
    public function save()
    {
        $this->validate([
		'IdLotesFolio' => 'required',
		'cantidad' => 'required',
        ]);

        Lotesfoliosmat::updateOrCreate(
			['id' => $this->selected_id],
			[
				'IdLotesFolio' => $this-> IdLotesFolio,
				'IdFacImportsDet' => $this-> IdFacImportsDet,
				'cantidad' => $this-> cantidad,
				'IdMaterial' => $this-> IdMaterial,
				'pesoEnUMat' => $this-> pesoEnUMat,
				'IdSize' => $this-> IdSize,
				'IdForma' => $this-> IdForma
			]
		);
        $this->resetInput();
        $this->verModalLotesfoliosmat = false;
    }
    public function paginationView()
    {
        return 'livewire.paginacionBase';
    }
    public function destroy($id)
    {
        if ($id) {
            Lotesfoliosmat::where('id', $id)->delete();
        }
    }
}