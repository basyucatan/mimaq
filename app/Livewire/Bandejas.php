<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Bandeja;
use Livewire\Attributes\Computed;
use App\Models\{Util};
use Illuminate\Support\Facades\DB;

class Bandejas extends Component
{
    use WithPagination;
	protected $paginationTheme = 'bootstrap';
    public $verModalBandeja=false, $selected_id, $keyWord, $IdFolio, $IdFacturaExport, $cantidad, $pesoMetalInicial, $pesoMetalActual, $pesoPiedrasConstante, $mermaMetalAcumulada, $IdProcesoActual, $enBoveda, $habilitada, $estatus;
	
    public function mount(){}
    public function updatedKeyWord(){$this->resetPage();}
    #[Computed]
	public function filteredBandejas()
	{
		$keyWord = '%' . $this->keyWord . '%';
		return Bandeja::Where('id','>',0)
			->where(function ($query) use ($keyWord) {
				$query
						->orWhere('IdFolio', 'LIKE', $keyWord)
						->orWhere('IdFacturaExport', 'LIKE', $keyWord)
						->orWhere('cantidad', 'LIKE', $keyWord)
						->orWhere('pesoMetalInicial', 'LIKE', $keyWord)
						->orWhere('pesoMetalActual', 'LIKE', $keyWord)
						->orWhere('pesoPiedrasConstante', 'LIKE', $keyWord)
						->orWhere('mermaMetalAcumulada', 'LIKE', $keyWord)
						->orWhere('IdProcesoActual', 'LIKE', $keyWord)
						->orWhere('enBoveda', 'LIKE', $keyWord)
						->orWhere('habilitada', 'LIKE', $keyWord)
						->orWhere('estatus', 'LIKE', $keyWord);
			})
			->paginate(12);
	}
	public function render()
	{
		return view('livewire.bandejas.view', [
			'bandejas' => $this->filteredBandejas,
		]);
	}
    public function cancel()
    {
        $this->resetInput();
        $this->verModalBandeja = false;
    }
    public function resetInput()
    {
        $this->reset();
    }
    public function edit($id)
    {
        $this->selected_id = $id;
		$this->fill(Bandeja::findOrFail($id)->toArray());
        $this->verModalBandeja = true;
    }
    public function create()
    {
        $this->resetInput();
        $this->verModalBandeja = true;
    }    
    public function save()
    {
        $this->validate([
		'IdFolio' => 'required',
		'cantidad' => 'required',
		'pesoMetalInicial' => 'required',
		'pesoMetalActual' => 'required',
		'pesoPiedrasConstante' => 'required',
		'mermaMetalAcumulada' => 'required',
		'enBoveda' => 'required',
		'habilitada' => 'required',
		'estatus' => 'required',
        ]);

        Bandeja::updateOrCreate(
			['id' => $this->selected_id],
			[
				'IdFolio' => $this-> IdFolio,
				'IdFacturaExport' => $this-> IdFacturaExport,
				'cantidad' => $this-> cantidad,
				'pesoMetalInicial' => $this-> pesoMetalInicial,
				'pesoMetalActual' => $this-> pesoMetalActual,
				'pesoPiedrasConstante' => $this-> pesoPiedrasConstante,
				'mermaMetalAcumulada' => $this-> mermaMetalAcumulada,
				'IdProcesoActual' => $this-> IdProcesoActual,
				'enBoveda' => $this-> enBoveda,
				'habilitada' => $this-> habilitada,
				'estatus' => $this-> estatus
			]
		);
        $this->resetInput();
        $this->verModalBandeja = false;
    }
    public function paginationView()
    {
        return 'livewire.paginacionBase';
    }
    public function destroy($id)
    {
        if ($id) {
            Bandeja::where('id', $id)->delete();
        }
    }
}