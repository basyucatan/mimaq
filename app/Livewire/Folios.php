<?php
namespace App\Livewire;
use Livewire\{Component, WithPagination};
use Livewire\Attributes\{Computed, On};
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Folio;
use App\Traits\{Utilfun, FolioManager}; 
class Folios extends Component
{
    use WithPagination, Utilfun, FolioManager;
    protected $paginationTheme = 'bootstrap';
    public $verModalFolio=false, $selected_id, $keyWord, $IdLote, $IdFolio, $folio,
        $IdEstilo, $jobStyle, $cantidad, $totalBandejas, $precioU, $fechaVen, $estatus;
    public $confirmadoExceso = false;
    public $adicionales = [];
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
    $procesosEstandar = \DB::table('procesos')
        ->join('deptos', 'procesos.IdDepto', '=', 'deptos.id')
        ->orderBy('deptos.orden')
        ->orderBy('procesos.proceso')
        ->select('procesos.proceso')
        ->get();
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
public function imprimir($id)
{
    $resultado = $this->getFolios($id);
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
    #[On('refreshFolios')]
    public function refresh()
    {
        if ($this->IdFolio) {
            $this->folio = Folio::find($this->IdFolio);
        }
    }   
    public function mount()
    {
        $this->folio = Folio::find($this->IdFolio);
    }
    public function updatedKeyWord()
    {
        $this->resetPage();
    }
    #[Computed]
    public function filteredFolios()
    {
        $keyWord = '%' . $this->keyWord . '%';
        return Folio::Where('id', $this->IdFolio)
            ->where(function ($query) use ($keyWord) {
                $query->orWhere('IdLote', 'LIKE', $keyWord)
                ->orWhere('IdEstilo', 'LIKE', $keyWord)
                ->orWhere('jobStyle', 'LIKE', $keyWord)
                ->orWhere('cantidad', 'LIKE', $keyWord)
                ->orWhere('totalBandejas', 'LIKE', $keyWord)
                ->orWhere('precioU', 'LIKE', $keyWord)
                ->orWhere('fechaVen', 'LIKE', $keyWord)
                ->orWhere('estatus', 'LIKE', $keyWord);
            })->paginate(12);
    }
    public function render()
    {
        return view('livewire.folios.view', [
            'folios' => $this->filteredFolios,
        ]);
    }
    public function cancel()
    {
        $this->resetInput();
        $this->verModalFolio = false;
    }
    public function resetInput()
    {
        $this->reset();
    }
    public function edit($id)
    {
        $this->selected_id = $id;
        $this->fill(Folio::findOrFail($id)->toArray());
        $this->verModalFolio = true;
    }
    public function create()
    {
        $this->resetInput();
        $this->verModalFolio = true;
    }    
    public function save()
    {
        $this->validate([
            'IdLote' => 'required',
            'cantidad' => 'required',
            'totalBandejas' => 'required',
            'precioU' => 'required',
            'fechaVen' => 'required',
            'estatus' => 'required',
        ]);
        Folio::updateOrCreate(
            ['id' => $this->selected_id],
            [
                'IdLote' => $this->IdLote,
                'IdEstilo' => $this->IdEstilo,
                'jobStyle' => $this->jobStyle,
                'cantidad' => $this->cantidad,
                'totalBandejas' => $this->totalBandejas,
                'precioU' => $this->precioU,
                'fechaVen' => $this->fechaVen,
                'estatus' => $this->estatus
            ]
        );
        $this->resetInput();
        $this->verModalFolio = false;
    }
    public function paginationView()
    {
        return 'livewire.paginacionBase';
    }
    public function destroy($id)
    {
        if ($id) Folio::where('id', $id)->delete();
    }
}