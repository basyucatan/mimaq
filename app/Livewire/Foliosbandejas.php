<?php
namespace App\Livewire;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\{Bandeja, Folio};
use App\Traits\Utilfun;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\{Computed, On};
use Barryvdh\DomPDF\Facade\Pdf;
class Foliosbandejas extends Component
{
    use WithPagination;
    use Utilfun;
    protected $paginationTheme = 'bootstrap';
    public $verModalFoliosban = false;
    public $verModalCrear = false;
    public $IdFolio;
    public $keyWord;
    public $bandejasEdit = [];
    public $nuevaCantidad = 0;
    #[On('refreshFolios')]
    public function refreshChild(){}
    public function abrirEdicionLote()
    {
        $this->cargarBandejas();
        $this->verModalFoliosban = true;
    }
    public function abrirModalCrear()
    {
        $this->nuevaCantidad = 0;
        $this->verModalCrear = true;
    }
    public function cargarBandejas()
    {
        $this->bandejasEdit = Bandeja::where('IdFolio', $this->IdFolio)
            ->get(['id', 'numeroBandeja', 'cantidad'])
            ->toArray();
    }
    public function cancel()
    {
        $this->resetInput();
        $this->verModalFoliosban = false;
        $this->verModalCrear = false;
    }
    public function resetInput()
    {
        $this->resetExcept('IdFolio', 'keyWord');
        $this->bandejasEdit = [];
        $this->nuevaCantidad = 0;
        $this->resetErrorBag();
    }
public function createBandeja()
{
    $folio = Folio::findOrFail($this->IdFolio);
    if ($folio->estatus !== 'abierto') return;
    $this->validate([
        'nuevaCantidad' => 'required|numeric|integer|min:1'
    ], [
        'nuevaCantidad.required' => 'La cantidad es obligatoria.',
        'nuevaCantidad.min' => 'Debe agregar al menos 1 pieza.'
    ]);
    $folio->agregarBandeja($this->nuevaCantidad);
    $nuevaSuma = Bandeja::where('IdFolio', $this->IdFolio)->sum('cantidad');
    if ($nuevaSuma != $folio->cantidad) {
        $dif = $nuevaSuma - $folio->cantidad;
        $msj = $dif > 0 ? "sobra(n) {$dif} pieza(s)" : "falta(n) " . abs($dif) . " pieza(s)";
        $this->alerta("⚠️ La suma total difiere de las piezas del folio, {$msj}.", 'warning', 3000);
    } else {
        $this->alerta("✅ Bandeja agregada e integridad verificada correctamente.", 'success');
    }
    $this->cancel();
    $this->dispatch('refreshFolios');
}
    public function save()
    {
        $folio = Folio::findOrFail($this->IdFolio);
        if ($folio->estatus !== 'abierto') return;
        $this->validate([
            'bandejasEdit.*.cantidad' => 'required|numeric|integer|min:1'
        ], [
            'bandejasEdit.*.cantidad.required' => 'La cantidad es requerida.',
            'bandejasEdit.*.cantidad.min' => 'Cada bandeja debe tener al menos 1 pieza.'
        ]);
        $sumaEditada = array_sum(array_column($this->bandejasEdit, 'cantidad'));
        foreach ($this->bandejasEdit as $item) {
            Bandeja::where('id', $item['id'])->update([
                'cantidad' => $item['cantidad']
            ]);
        }
        $folio->actualizarEstadoIntegridad();
        $folio->reprocesarEstructuraMovimientos();
        if ($sumaEditada != $folio->cantidad) {
            $dif = $sumaEditada - $folio->cantidad;
            $msj = $dif > 0 ? "sobra(n) {$dif} pieza(s)" : "falta(n) " . abs($dif) . " pieza(s)";
            $this->alerta("⚠️ No coincide con el total, {$msj}.", 'warning', 3000);
        } else {
            $this->alerta("✅ Cantidades e integridad confirmada.", 'success');
        }
        $this->cancel();
        $this->dispatch('refreshFolios');
    }
    public function destroy($id)
    {
        $folio = Folio::findOrFail($this->IdFolio);
        if ($folio->estatus !== 'abierto') return;
        $bandeja = Bandeja::where('IdFolio', $this->IdFolio)->where('id', $id)->first();
        if ($bandeja) {
            \App\Models\Bandejasmov::where('IdBandeja', $bandeja->id)->delete();
            $bandeja->delete();
            $folio->actualizarEstadoIntegridad();
            $folio->reprocesarEstructuraMovimientos();
            $nuevaSuma = Bandeja::where('IdFolio', $this->IdFolio)->sum('cantidad');
            if ($nuevaSuma != $folio->cantidad) {
                $dif = $folio->cantidad - $nuevaSuma;
                $this->alerta("⚠️ Se rompió la integridad: falta(n) {$dif} pieza(s) para completar el folio ({$folio->cantidad}).", 'warning', 3000);
            } else {
                $this->alerta("✅ Registro eliminado correctamente.", 'success');
            }
            $this->dispatch('refreshFolios');
        }
    }
    public function validarYCorregir()
    {
        $folio = Folio::findOrFail($this->IdFolio);
        if ($folio->corregirIntegridad()) {
            $this->alerta('✅ Integridad verificada y corregida conservando la distribución.', 'success');
            $this->dispatch('refreshFolios');
        } else {
            $this->alerta('⚠️ No fue posible procesar la corrección.', 'warning');
        }
    }
    private function getFolios($idFolio)
    {
        $folio = Folio::with([
            'lote.orden.cliente',
            'estilo.clase.arancel',
            'foliosmats.material.clase',
            'foliosmats.material.unidad',
            'bandejas' => function ($query) {
                $query->orderBy('numeroBandeja');
            }
        ])->findOrFail($idFolio);
        $materialesAgrupados = $folio->foliosmats
            ->groupBy(function ($item) {
                $nombreMaterial = $item->material->material ?? 'N/A';
                $propiedades = method_exists($item, 'getPropiedadesAttribute') ? strip_tags($item->propiedades) : '';
                return $nombreMaterial . ($propiedades ? ' ' . $propiedades : '');
            })
            ->map(function ($grupo) {
                return $grupo->sortBy('id');
            })
            ->sortBy(function ($grupo) {
                $primerItem = $grupo->first();
                return $primerItem->material?->clase?->IdAccess ?? '';
            });
        $procesosEstandar = collect([
            (object)['proceso' => '34-TOMBOLA'],
            (object)['proceso' => '61-LIMPIEZA'],
            (object)['proceso' => '62- PREPULIDO'],
            (object)['proceso' => '31-LAVADO 1'],
            (object)['proceso' => '40-ENGARCE 1'],
            (object)['proceso' => '64-LAPA'],
            (object)['proceso' => '33-LAV LAPA'],
            (object)['proceso' => '51-JOYERIA'],
            (object)['proceso' => '63- PULIDO'],
            (object)['proceso' => '32-LAVADO 2'],
            (object)['proceso' => '80-Q.C. 1'],
            (object)['proceso' => '41-ENGARCE 2'],
            (object)['proceso' => '34- RHODIO'],
            (object)['proceso' => '81-O.C. 2'],
            (object)['proceso' => '83-EMPAQUE']
        ]);        
        return [$folio, $materialesAgrupados, $procesosEstandar];
    }
    public function imprimir($id = null)
    {
        $targetId = $id ?? $this->IdFolio;
        if (!$targetId) return;
        $resultado = $this->getFolios($targetId);
        [$folio, $materialesAgrupados, $procesosEstandar] = $resultado;
        if ($folio->bandejas->isEmpty()) {
            $this->alerta('⛔ Folio sin bandejas asignadas', 'warning');
            return;
        }
        $htmlBandejas = view('livewire.folios.folioPDF', compact('folio', 'materialesAgrupados', 'procesosEstandar'))->render();
        $instanciaDompdf = Pdf::loadHTML($htmlBandejas);
        $instanciaDompdf->setPaper('letter', 'landscape');
        $contenidoPdf = $instanciaDompdf->output();
        $rutaArchivo = 'folios/folio_bandejas_' . $folio->id . '.pdf';
        Storage::disk('public')->put($rutaArchivo, $contenidoPdf);
        $rutaFisica = storage_path('app/public/' . $rutaArchivo);
        return response()->file($rutaFisica, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="folio_bandejas_' . $folio->id . '.pdf"'
        ]);
    }
    #[Computed]
    public function filteredFoliosban()
    {
        return Bandeja::query()
            ->with('folio')
            ->select('bandejas.*')
            ->where('bandejas.IdFolio', $this->IdFolio)
            ->paginate(12);
    }
    public function render()
    {
        return view('livewire.foliosban.view', [
            'foliosban' => $this->filteredFoliosban
        ]);
    }
}