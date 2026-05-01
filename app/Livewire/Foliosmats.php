<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Foliosmat;
use Livewire\Attributes\Computed;
use App\Models\{Util, Referenciasmov};
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;
class Foliosmats extends Component
{
    use WithPagination;

	protected $paginationTheme = 'bootstrap';
    public $verModalFoliosmat=false, $selected_id, $keyWord, $IdFolio, $IdFacImportsDet, 
        $IdMaterial, $cantidad, $pesoG, $integrado;
	public $adicionales = [], $materials = [], $referencias = [];

    public function elegirMaterial()
    {
        $this->referencias = Referenciasmov::with('Referencia')
            ->where('estatus', 'boveda')
            ->whereHas('Referencia', function ($query) {
                $query->where('IdMaterial', $this->IdMaterial);
            })
            ->get()
            ->mapWithKeys(function ($item) {
                $entrada = $item->Referencia->IdEntradaMex ?? 'S/N';
                $cantidad = number_format($item->cantidad, 3, '.', '');
                return [$item->IdFacImportsDet => "{$entrada} ({$cantidad})"];
            })
            ->toArray();
    }
public function validarDisponibilidad()
{
    if (!$this->IdFacImportsDet) return;
    $referencia = Referenciasmov::where('IdFacImportsDet', $this->IdFacImportsDet)
        ->where('estatus', 'boveda')
        ->first();
    if ($referencia) {
        $this->pesoG = $referencia->pesoG;
        $cantidadIngresada = floatval($this->cantidad);
        $cantidadDisponible = floatval($referencia->cantidad);
        if ($cantidadIngresada > $cantidadDisponible) {
            $max = number_format($cantidadDisponible, 3, '.', '');
            $this->addError('cantidad', "La cantidad excede el disponible ({$max})");
        } else {
            $this->resetErrorBag('cantidad');
        }
    }
}
    #[On('refreshFolios')]
    public function refreshChild(){}
    public function mount(){
        $this->materials = Util::getArray('materials');
    }
    public function updatedKeyWord()
	{
		$this->resetPage();
	}
    #[Computed]
	public function filteredFoliosmats()
	{
		$keyWord = '%' . $this->keyWord . '%';
		return Foliosmat::Where('IdFolio', $this->IdFolio)
			->where(function ($query) use ($keyWord) {
				$query
                    ->orWhere('cantidad', 'LIKE', $keyWord)
                    ->orWhere('pesoG', 'LIKE', $keyWord)
                    ->orWhere('integrado', 'LIKE', $keyWord);
			})
			->paginate(12);
	}
	public function render()
	{
		return view('livewire.foliosmats.view', [
			'foliosmats' => $this->filteredFoliosmats,
		]);
	}
    public function cancel()
    {
        $this->resetInput();
        $this->verModalFoliosmat = false;
    }
    public function resetInput()
    {
        $this->resetExcept('materials', 'IdFolio');
    }
    public function edit($id)
    {
        $this->selected_id = $id;
		$this->fill(Foliosmat::findOrFail($id)->toArray());
        $this->elegirMaterial();
        $this->verModalFoliosmat = true;
    }
    public function create()
    {
        $this->resetInput();
        $this->verModalFoliosmat = true;
    }    
    public function save()
    {
        $this->validate([
		'IdFolio' => 'required',
		'IdMaterial' => 'required',
		'cantidad' => 'required',
		'pesoG' => 'required',
		'integrado' => 'required',
        ]);
        Foliosmat::updateOrCreate(
			['id' => $this->selected_id],
			[
				'IdFolio' => $this-> IdFolio,
				'IdFacImportsDet' => $this-> IdFacImportsDet,
				'IdMaterial' => $this-> IdMaterial,
				'cantidad' => $this-> cantidad,
				'pesoG' => $this-> pesoG,
				'integrado' => $this-> integrado
			]
		);
        $this->resetInput();
        $this->verModalFoliosmat = false;
    }
    public function paginationView()
    {
        return 'livewire.paginacionBase';
    }
    public function destroy($id)
    {
        if ($id) {
            Foliosmat::where('id', $id)->delete();
        }
    }
}