<?php
namespace App\Livewire;
use Livewire\Component;
use Livewire\WithPagination;
use App\Services\ReporteService;
use App\Models\{Util};
use Illuminate\Support\Facades\DB;

class Consultas extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $obrasVigentes = 0, $gastoMes = 0, $ocMes = 0;
    public $IdObra, $IdDivision, $filtroMes = '';
    public $obras = [], $divisions = [];
    public function mount()
    {
        $service = new ReporteService();
        $this->obrasVigentes = $service->obrasVigentes();
        $this->gastoMes = $service->gastoMes();
        $this->ocMes = $service->ocMes();
        $this->obras = DB::table('obras')
            ->leftJoin('empresas', 'obras.IdEmpresa', '=', 'empresas.id')
            ->selectRaw("obras.id, CONCAT(obras.obra, '-', 
                    COALESCE(
                        SUBSTRING_INDEX(empresas.empresa, ' ', 2), 
                        'Sin cliente'
                    )
                ) as obra")
            ->orderBy('obras.obra', 'asc')
            ->pluck('obra', 'id');
        $this->divisions = Util::getArray('divisions');
        $this->filtroMes = now()->format('Y-m');
    }
    public function updatingIdObra() { $this->resetPage(); }
    public function updatingIdDivision() { $this->resetPage(); }
    public function updatingFiltroMes() { $this->resetPage(); }
  
public function render()
{
    $service = new ReporteService();

    $queryTotales = \App\Models\Ocompra::where('estatus', '!=', 'cancelado');

    // Usamos filled() o verificamos que no sea vacío para evitar filtrar por ""
    if (!empty($this->IdObra)) $queryTotales->where('IdObra', $this->IdObra);
    if (!empty($this->IdDivision)) $queryTotales->where('IdDivision', $this->IdDivision);
    
    if ($this->filtroMes) {
        $fecha = \Carbon\Carbon::parse($this->filtroMes . '-01');
        $queryTotales->whereBetween('fechaHSol', [
            $fecha->copy()->startOfMonth()->format('Y-m-d 00:00:00'), 
            $fecha->copy()->endOfMonth()->format('Y-m-d 23:59:59')
        ]);
    }

    // Calculamos el Gran Total
    $granTotal = $queryTotales->get()->sum(function($oc) {
        $descuento = $oc->subtotal * ($oc->porDescuento ?? 0) / 100;
        $base = $oc->subtotal - $descuento;
        $iva = $base * ($oc->tasaIva ?? 0);
        return $base + $iva;
    });

    $resultados = $service->obtenerGastos(
        $this->IdObra, 
        $this->IdDivision, 
        $this->filtroMes,
        15
    );

    return view('livewire.consultas.view', [
        'resultados' => $resultados,
        'granTotal' => $granTotal
    ]);
}
    public function paginationView()
    {
        return 'livewire.paginacionBase';
    } 
}