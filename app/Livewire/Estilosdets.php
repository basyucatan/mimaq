<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Estilosdet;
use Livewire\Attributes\Computed;
use App\Models\{Util};
use Illuminate\Support\Facades\DB;

class Estilosdets extends Component
{
    use WithPagination;

	protected $paginationTheme = 'bootstrap';
    public $verModalEstilosdet=false, $selected_id, $keyWord, $IdEstilo, $cantidad, $IdMaterial, $IdSize, $IdForma, $estiloY;
	
	public $adicionales = [];
    public function mount()
    {
    }    
    public function updatedKeyWord()
	{
		$this->resetPage();
	}
    #[Computed]
	public function filteredEstilosdets()
	{
		$keyWord = '%' . $this->keyWord . '%';
		return Estilosdet::Where('id','>',0)
			->where(function ($query) use ($keyWord) {
				$query
						->orWhere('IdEstilo', 'LIKE', $keyWord)
						->orWhere('cantidad', 'LIKE', $keyWord)
						->orWhere('IdMaterial', 'LIKE', $keyWord)
						->orWhere('IdSize', 'LIKE', $keyWord)
						->orWhere('IdForma', 'LIKE', $keyWord)
						->orWhere('estiloY', 'LIKE', $keyWord);
			})
			->paginate(12);
	}

	public function render()
	{
		return view('livewire.estilosdets.view', [
			'estilosdets' => $this->filteredEstilosdets,
		]);
	}
	
    public function cancel()
    {
        $this->resetInput();
        $this->verModalEstilosdet = false;
    }

    public function resetInput()
    {
        $this->reset();
    }

    public function edit($id)
    {
        $this->selected_id = $id;
		$this->fill(Estilosdet::findOrFail($id)->toArray());
        $this->verModalEstilosdet = true;
    }
    public function create()
    {
        $this->resetInput();
        $this->verModalEstilosdet = true;
    }    
    public function save()
    {
        $this->validate([
		'IdEstilo' => 'required',
		'cantidad' => 'required',
        ]);

        Estilosdet::updateOrCreate(
			['id' => $this->selected_id],
			[
				'IdEstilo' => $this-> IdEstilo,
				'cantidad' => $this-> cantidad,
				'IdMaterial' => $this-> IdMaterial,
				'IdSize' => $this-> IdSize,
				'IdForma' => $this-> IdForma,
				'estiloY' => $this-> estiloY
			]
		);
        $this->resetInput();
        $this->verModalEstilosdet = false;
    }
    public function paginationView()
    {
        return 'livewire.paginacionBase';
    }
    public function destroy($id)
    {
        if ($id) {
            Estilosdet::where('id', $id)->delete();
        }
    }
}