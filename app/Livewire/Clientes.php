<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Cliente;
use Livewire\Attributes\Computed;
use App\Models\Util;
use Illuminate\Support\Facades\DB;

class Clientes extends Component
{
    use WithPagination;

	protected $paginationTheme = 'bootstrap';
    public $verModalCliente=false, $selected_id, $keyWord, $cliente;
	
	public $adicionales = [];
    public function updatedKeyWord()
	{
		$this->resetPage();
	}
    #[Computed]
	public function filteredClientes()
	{
		$keyWord = '%' . $this->keyWord . '%';
		return Cliente::Where('id','>',0)
			->where(function ($query) use ($keyWord) {
				$query
						->orWhere('cliente', 'LIKE', $keyWord);
			})
			->paginate(12);
	}

	public function render()
	{
		return view('livewire.clientes.view', [
			'clientes' => $this->filteredClientes,
		]);
	}
	
    public function cancel()
    {
        $this->resetInput();
        $this->verModalCliente = false;
    }

    public function resetInput()
    {
        $this->reset();
    }

    public function edit($id)
    {
        $this->selected_id = $id;
		$this->fill(Cliente::findOrFail($id)->toArray());
        $this->verModalCliente = true;
    }
    public function create()
    {
        $this->resetInput();
        $this->verModalCliente = true;
    }    
    public function save()
    {
        $this->validate([
		'cliente' => 'required',
        ]);

        Cliente::updateOrCreate(
			['id' => $this->selected_id],
			[
				'cliente' => $this-> cliente
			]
		);
        $this->resetInput();
        $this->verModalCliente = false;
    }
    public function paginationView()
    {
        return 'livewire.paginacionBase';
    }
    public function destroy($id)
    {
        if ($id) {
            Cliente::where('id', $id)->delete();
        }
    }
}