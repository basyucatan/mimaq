<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Facimportsdet;
use Livewire\Attributes\Computed;
use App\Models\{Util,Factura};
use Illuminate\Support\Facades\DB;

class Facimportsdets extends Component
{
    use WithPagination;

	protected $paginationTheme = 'bootstrap';
    public $verModalFacimportsdet=false, $selected_id, $keyWord, $IdFactura, $Factura,
    $IdEntradaMex, $IdOrigen, $IdMaterial, $cantidad, $precioU, $pesoEnUMat, 
    $pesoG, $IdSize, $IdForma;
	
	public $adicionales = [];

    public function mount()
    {
		$this->Factura = Factura::find($this->IdFactura);
    }    
    public function updatedKeyWord()
	{
		$this->resetPage();
	}
    #[Computed]
	public function filteredFacimportsdets()
	{
		$keyWord = '%' . $this->keyWord . '%';
		return Facimportsdet::Where('IdFactura',$this->IdFactura)
			->where(function ($query) use ($keyWord) {
				$query
						->orWhere('IdFactura', 'LIKE', $keyWord)
						->orWhere('IdEntradaMex', 'LIKE', $keyWord);
			})
			->paginate(12);
	}

	public function render()
	{
		return view('livewire.facimportsdets.view', [
			'facimportsdets' => $this->filteredFacimportsdets,
		]);
	}
	
    public function cancel()
    {
        $this->resetInput();
        $this->verModalFacimportsdet = false;
    }

    public function resetInput()
    {
        $this->resetexcept('Factura');
    }

    public function edit($id)
    {
        $this->selected_id = $id;
		$this->fill(Facimportsdet::findOrFail($id)->toArray());
        $this->verModalFacimportsdet = true;
    }
    public function create()
    {
        $this->resetInput();
        $this->verModalFacimportsdet = true;
    }    
    public function save()
    {
        $this->validate([
		'IdFactura' => 'required',
		'IdEntradaMex' => 'required',
		'cantidad' => 'required',
		'precioU' => 'required',
		'pesoEnUMat' => 'required',
		'pesoG' => 'required',
        ]);

        Facimportsdet::updateOrCreate(
			['id' => $this->selected_id],
			[
				'IdFactura' => $this-> IdFactura,
				'IdEntradaMex' => $this-> IdEntradaMex,
				'IdOrigen' => $this-> IdOrigen,
				'IdMaterial' => $this-> IdMaterial,
				'cantidad' => $this-> cantidad,
				'precioU' => $this-> precioU,
				'pesoEnUMat' => $this-> pesoEnUMat,
				'pesoG' => $this-> pesoG,
				'IdSize' => $this-> IdSize,
				'IdForma' => $this-> IdForma
			]
		);
        $this->resetInput();
        $this->verModalFacimportsdet = false;
    }
    public function paginationView()
    {
        return 'livewire.paginacionBase';
    }
    public function destroy($id)
    {
        if ($id) {
            Facimportsdet::where('id', $id)->delete();
        }
    }
}