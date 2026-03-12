<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Empresascuenta;
use Livewire\Attributes\Computed;
use App\Models\Util;
use Illuminate\Support\Facades\DB;

class Empresascuentas extends Component
{
    use WithPagination;

	protected $paginationTheme = 'bootstrap';
    public $verModalEmpresascuenta=false, $selected_id, $keyWord, $IdEmpresa, $banco, $cuenta, $cuentaClabe, $adicionales;
	
    public function updatedKeyWord()
	{
		$this->resetPage();
	}
    #[Computed]
	public function filteredEmpresascuentas()
	{
		$keyWord = '%' . $this->keyWord . '%';
		return Empresascuenta::Where('IdEmpresa',$this->IdEmpresa)
			->where(function ($query) use ($keyWord) {
				$query
						->orWhere('IdEmpresa', 'LIKE', $keyWord)
						->orWhere('banco', 'LIKE', $keyWord)
						->orWhere('cuenta', 'LIKE', $keyWord)
						->orWhere('cuentaClabe', 'LIKE', $keyWord)
						->orWhere('adicionales', 'LIKE', $keyWord);
			})
			->paginate(12);
	}

	public function render()
	{
		return view('livewire.empresascuentas.view', [
			'empresascuentas' => $this->filteredEmpresascuentas,
		]);
	}
	
    public function cancel()
    {
        $this->resetInput();
        $this->verModalEmpresascuenta = false;
    }

    public function resetInput()
    {
        $this->resetExcept('IdEmpresa');
    }

    public function edit($id)
    {
        $this->selected_id = $id;
		$this->fill(Empresascuenta::findOrFail($id)->toArray());
        $this->verModalEmpresascuenta = true;
    }
    public function create()
    {
        $this->resetInput();
        $this->verModalEmpresascuenta = true;
    }    
    public function save()
    {
        $this->validate([
		'banco' => 'required',
		'cuentaClabe' => 'required',
        ]);

        Empresascuenta::updateOrCreate(
			['id' => $this->selected_id],
			[
				'IdEmpresa' => $this->IdEmpresa,
				'banco' => $this->banco,
				'cuenta' => $this->cuenta,
				'cuentaClabe' => $this->cuentaClabe,
				'adicionales' => $this->adicionales
			]
		);
        $this->resetInput();
        $this->verModalEmpresascuenta = false;
    }

    public function destroy($id)
    {
        if ($id) {
            Empresascuenta::where('id', $id)->delete();
        }
    }
}