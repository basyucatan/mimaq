<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Facexportsmat;
use Livewire\Attributes\Computed;
use App\Models\{Util};
use Illuminate\Support\Facades\DB;

class Facexportsmats extends Component
{
    use WithPagination;
	protected $paginationTheme = 'bootstrap';
    public $verModalFacexportsmat=false, $selected_id, $keyWord, $IdFacExportsDet, 
        $IdFacImportsDet, $IdMaterial, $IdTipo, $cantidadDescargada, $pesoDescargadoG;
	
    public function mount(){}
    public function updatedKeyWord(){$this->resetPage();}
    #[Computed]
	public function filteredFacexportsmats()
	{
		return Facexportsmat::Where('IdFacExportsDet', $this->IdFacExportsDet)
			->get();
	}
	public function render()
	{
		return view('livewire.facexportsmats.view', [
			'facexportsmats' => $this->filteredFacexportsmats,
		]);
	}
    public function cancel()
    {
        $this->resetInput();
        $this->verModalFacexportsmat = false;
    }
    public function resetInput()
    {
        $this->resetExcept('IdFacExportsDet');
    }
    public function edit($id)
    {
        $this->selected_id = $id;
		$this->fill(Facexportsmat::findOrFail($id)->toArray());
        $this->verModalFacexportsmat = true;
    }
    public function create()
    {
        $this->resetInput();
        $this->verModalFacexportsmat = true;
    }    
    public function save()
    {
        $this->validate([
		'IdFacExportsDet' => 'required',
		'IdFacImportsDet' => 'required',
		'IdMaterial' => 'required',
		'IdTipo' => 'required',
		'cantidadDescargada' => 'required',
		'pesoDescargadoG' => 'required',
        ]);

        Facexportsmat::updateOrCreate(
			['id' => $this->selected_id],
			[
				'IdFacExportsDet' => $this-> IdFacExportsDet,
				'IdFacImportsDet' => $this-> IdFacImportsDet,
				'IdMaterial' => $this-> IdMaterial,
				'IdTipo' => $this-> IdTipo,
				'cantidadDescargada' => $this-> cantidadDescargada,
				'pesoDescargadoG' => $this-> pesoDescargadoG
			]
		);
        $this->resetInput();
        $this->verModalFacexportsmat = false;
    }
    public function paginationView()
    {
        return 'livewire.paginacionBase';
    }
    public function destroy($id)
    {
        if ($id) {
            Facexportsmat::where('id', $id)->delete();
        }
    }
}