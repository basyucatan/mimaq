<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Obra;
use Livewire\Attributes\Computed;
use App\Models\Util;
use Illuminate\Support\Facades\DB;

class Obras extends Component
{
    use WithPagination;

	protected $paginationTheme = 'bootstrap';
    public $verModalObra=false, $selected_id, $keyWord, $IdEmpresa, $obra, $gmaps, $adicionales;
	
    public function updatedKeyWord()
	{
		$this->resetPage();
	}
    #[Computed]
	public function filteredObras()
	{
		$keyWord = '%' . $this->keyWord . '%';
		return Obra::Where('IdEmpresa',$this->IdEmpresa)
			->where(function ($query) use ($keyWord) {
				$query
						->orWhere('IdEmpresa', 'LIKE', $keyWord)
						->orWhere('obra', 'LIKE', $keyWord)
						->orWhere('gmaps', 'LIKE', $keyWord)
						->orWhere('adicionales', 'LIKE', $keyWord);
			})
			->paginate(12);
	}

	public function render()
	{
		return view('livewire.obras.view', [
			'obras' => $this->filteredObras,
		]);
	}
	
    public function cancel()
    {
        $this->resetInput();
        $this->verModalObra = false;
    }

    public function resetInput()
    {
        $this->resetExcept('IdEmpresa');
    }

    public function edit($id)
    {
        $this->selected_id = $id;
		$this->fill(Obra::findOrFail($id)->toArray());
        $this->verModalObra = true;
    }
    public function create()
    {
        $this->resetInput();
        $this->verModalObra = true;
    }    
    public function save()
    {
        $this->validate([
		'IdEmpresa' => 'required',
		'obra' => 'required',
        ]);

        Obra::updateOrCreate(
			['id' => $this->selected_id],
			[
				'IdEmpresa' => $this-> IdEmpresa,
				'obra' => $this-> obra,
				'gmaps' => $this-> gmaps,
				'adicionales' => $this-> adicionales
			]
		);
        $this->resetInput();
        $this->verModalObra = false;
    }

    public function destroy($id)
    {
        if ($id) {
            Obra::where('id', $id)->delete();
        }
    }
}