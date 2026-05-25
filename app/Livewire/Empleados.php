<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Empleado;
use Livewire\Attributes\Computed;
use App\Models\{Util};
class Empleados extends Component
{
    use WithPagination;
	protected $paginationTheme = 'bootstrap';
    public $verModalEmpleado=false, $selected_id, $keyWord, $empleado, $IdDepto, $vigente;
	public $adicionales = [], $deptos = [];
    public function mount(){
        $this->deptos = Util::getArray('deptos');
    }
    public function updatedKeyWord(){$this->resetPage();}
    #[Computed]
	public function filteredEmpleados()
	{
		$keyWord = '%' . $this->keyWord . '%';
		return Empleado::Where('id','>',0)
			->where(function ($query) use ($keyWord) {
				$query
						->orWhere('empleado', 'LIKE', $keyWord)
						->orWhere('IdDepto', 'LIKE', $keyWord)
						->orWhere('vigente', 'LIKE', $keyWord);
			})
			->paginate(12);
	}
	public function render()
	{
		return view('livewire.empleados.view', [
			'empleados' => $this->filteredEmpleados,
		]);
	}
    public function cancel()
    {
        $this->resetInput();
        $this->verModalEmpleado = false;
    }
    public function resetInput()
    {
        $this->resetExcept('deptos');
    }
    public function edit($id)
    {
        $this->selected_id = $id;
		$this->fill(Empleado::findOrFail($id)->toArray());
        $this->verModalEmpleado = true;
    }
    public function create()
    {
        $this->resetInput();
        $this->vigente = true;
        $this->verModalEmpleado = true;
    }    
    public function save()
    {
        $this->validate([
		'empleado' => 'required',
		'IdDepto' => 'required',
        ]);
        Empleado::updateOrCreate(
			['id' => $this->selected_id],
			[
				'empleado' => strtoupper($this->empleado),
				'IdDepto' => $this->IdDepto,
				'vigente' => $this->vigente,
			]
		);
        $this->resetInput();
        $this->verModalEmpleado = false;
    }
    public function paginationView()
    {
        return 'livewire.paginacionBase';
    }
    public function destroy($id)
    {
        if ($id) {
            Empleado::where('id', $id)->delete();
        }
    }
}