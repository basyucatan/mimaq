<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Lotesfolio;
use Livewire\Attributes\Computed;
use App\Models\Util;
use Illuminate\Support\Facades\DB;

class Lotesfolios extends Component
{
    use WithPagination;

	protected $paginationTheme = 'bootstrap';
    public $verModalLotesfolio=false, $selected_id, $keyWord, $IdLote, $IdEstilo, $cantidad, $precioU, $peso, $jobStyle, $fechaVen;
	
	public $adicionales = [];
    public function updatedKeyWord()
	{
		$this->resetPage();
	}
    #[Computed]
	public function filteredLotesfolios()
	{
		$keyWord = '%' . $this->keyWord . '%';
		return Lotesfolio::Where('id','>',0)
			->where(function ($query) use ($keyWord) {
				$query
						->orWhere('IdLote', 'LIKE', $keyWord)
						->orWhere('IdEstilo', 'LIKE', $keyWord)
						->orWhere('cantidad', 'LIKE', $keyWord)
						->orWhere('precioU', 'LIKE', $keyWord)
						->orWhere('peso', 'LIKE', $keyWord)
						->orWhere('jobStyle', 'LIKE', $keyWord)
						->orWhere('fechaVen', 'LIKE', $keyWord);
			})
			->paginate(12);
	}

	public function render()
	{
		return view('livewire.lotesfolios.view', [
			'lotesfolios' => $this->filteredLotesfolios,
		]);
	}
	
    public function cancel()
    {
        $this->resetInput();
        $this->verModalLotesfolio = false;
    }

    public function resetInput()
    {
        $this->reset();
    }

    public function edit($id)
    {
        $this->selected_id = $id;
		$this->fill(Lotesfolio::findOrFail($id)->toArray());
        $this->verModalLotesfolio = true;
    }
    public function create()
    {
        $this->resetInput();
        $this->verModalLotesfolio = true;
    }    
    public function save()
    {
        $this->validate([
		'IdLote' => 'required',
		'cantidad' => 'required',
		'precioU' => 'required',
		'fechaVen' => 'required',
        ]);

        Lotesfolio::updateOrCreate(
			['id' => $this->selected_id],
			[
				'IdLote' => $this-> IdLote,
				'IdEstilo' => $this-> IdEstilo,
				'cantidad' => $this-> cantidad,
				'precioU' => $this-> precioU,
				'peso' => $this-> peso,
				'jobStyle' => $this-> jobStyle,
				'fechaVen' => $this-> fechaVen
			]
		);
        $this->resetInput();
        $this->verModalLotesfolio = false;
    }
    public function paginationView()
    {
        return 'livewire.paginacionBase';
    }
    public function destroy($id)
    {
        if ($id) {
            Lotesfolio::where('id', $id)->delete();
        }
    }
}