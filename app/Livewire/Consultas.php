<?php
namespace App\Livewire;
use Livewire\Component;
use App\Services\ReporteService;
use App\Models\{Obra, Division};

class Consultas extends Component
{
    public $obrasVigentes = 0, $gastoMes = 0, $ocMes = 0;

    public $filtroObra = '', $filtroDivision = '', $filtroMes = '';
    public $listaObras = [], $listaDivisiones = [];
    
    public function mount()
    {
        $service = new ReporteService();
        $this->obrasVigentes = $service->obrasVigentes();
        $this->gastoMes = $service->gastoMes();
        $this->ocMes = $service->ocMes();
        
        $this->listaObras = Obra::orderBy('obra')->get();
        $this->listaDivisiones = Division::orderBy('division')->get();
        $this->filtroMes = now()->format('Y-m');
    }

    public function render()
    {
        $service = new ReporteService();
        $resultados = $service->obtenerGastos(
            $this->filtroObra, 
            $this->filtroDivision, 
            $this->filtroMes
        );

        return view('livewire.consultas.view', [
            'resultados' => $resultados
        ]);
    }
}