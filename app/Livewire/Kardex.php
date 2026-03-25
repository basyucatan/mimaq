<?php

namespace App\Livewire;
use Livewire\Component;
use Livewire\WithPagination; 
use App\Models\{Util, Movinventario, Materialscosto};
use Illuminate\Support\Facades\DB;

class Kardex extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $matCosto, $existencia, $IdBodega = 1, $IdDepto = 1, $fechaIni, $fechaFin;
    public $bodegas = [], $deptos = [];
    protected $listeners = ['IdArbolElecto' => 'IdArbolElecto'];
    public function mount(){
        $this->bodegas = Util::getArray('divsbodegas','bodega');
        $this->deptos = Util::getArray('deptos');
    }
    public function IdArbolElecto($tipo, $id){
        if($tipo != 'Materialscosto') return;
        $this->matCosto = Materialscosto::find($id);
        $this->resetPage();
    }
    public function calcularMovs()
    {
        $this->resetPage();
    }
    public function render()
    {
        $this->fechaIni = $this->fechaIni ?? now('America/Mexico_City')->startOfYear()->toDateString();
        $this->fechaFin = $this->fechaFin ?? now('America/Mexico_City')->toDateString();    
        $movimientos = null;
        if ($this->matCosto) {
            $movimientos = Movinventario::obtenerKardex(
                $this->matCosto->id, 
                $this->IdBodega, 
                $this->IdDepto,
                $this->fechaIni,
                $this->fechaFin
            );
            $this->existencia = $movimientos->total() > 0 ? $movimientos->first()->saldoCalculado : 0;
        }
        if (!$this->deptos) {
            $this->deptos = DB::table('deptos')->where('id', '<', 6)->pluck('depto', 'id');
        }
        return view('livewire.kardex.view', [
            'movsPaginados' => $movimientos
        ]);
    }
    public function paginationView()
    {
        return 'livewire.paginacionBase';
    }
}