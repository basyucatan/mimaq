<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Material;
use App\Models\Materialscosto;
use App\Models\Modelo;
use App\Models\ModelosPre;
use App\Models\Regla;
use App\Services\TcpdfService;

class Fichamats extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';
    public $verModal = false, $tabActivo = 'tab1', $subTabActivo = 'sub1',
    $IdMaterial, $material;
    protected $listeners = [
        'IdArbolElecto' => 'IdArbolElecto',
    ];
    public function IdArbolElecto($tipo, $id)
    {
        if ($tipo == 'Material') {
            $this->IdMaterial = $id;
            $this->material = Material::find($id);
        }
    }
    public function render()
    {
        $modelos = []; $modelosPres = []; $reglas = [];
        if ($this->IdMaterial) {
            $modelos = Modelo::whereHas('modelosmats', function ($query) {
                    $query->where('IdMaterial', $this->IdMaterial);
                })
                ->orderby('IdLinea')
                ->orderby('modelo')
                ->get();
            $modelosPres = ModelosPre::whereHas('modelopremats', function ($query) {
                    $query->where('IdMaterial', $this->IdMaterial);
                })
                ->orderby('id', 'desc')
                ->paginate(12);
            $reglas = Regla::where('IdMatRelacion',$this->IdMaterial)->get();
        }        
        return view('livewire.fichamats.view', [
                'modelos' => $modelos,
                'modelosPres' => $modelosPres,
                'reglas' => $reglas,
            ]);
    }

    public function costosPDF($opcion)
    {
        $materiales = Materialscosto::query()
            ->when($opcion === 'sin', fn($q) => $q->where(fn($q2) => $q2->whereNull('costo')->orWhere('costo', 0)))
            ->when($opcion === 'con', fn($q) => $q->where('costo', '>', 0))
            ->join('materials', 'materialscostos.IdMaterial', '=', 'materials.id')
            ->join('clases', 'materials.IdClase', '=', 'clases.id')
            ->leftJoin('lineas', 'materials.IdLinea', '=', 'lineas.id')
            ->leftJoin('marcas', 'lineas.IdMarca', '=', 'marcas.id')
            ->with(['material.Clase', 'material.Linea.Marca', 'Color', 'Barra', 'Panel', 'Moneda'])
            ->select('materialscostos.*')
            ->orderBy('clases.orden')
            ->orderBy('marcas.marca')
            ->orderBy('lineas.linea')
            ->orderBy('materials.material')
            ->orderBy('materials.referencia')
            ->get();
        $totalGeneral = $materiales->count();
        $agrupados = $materiales
            ->groupBy(fn($row) => $row->material?->Clase?->id ?? 'sin-clase')
            ->map(fn($itemsPorClase) => $itemsPorClase->groupBy(fn($row) => $row->material?->Linea?->Marca?->id ?? 'sin-marca')->map(fn($itemsPorMarca) => $itemsPorMarca->groupBy(fn($row) => $row->material?->Linea?->id ?? 'sin-linea')));
        $totales = $agrupados->map(fn($marcasGroup) => ['totalClase' => $marcasGroup->flatten(2)->count(), 'marcas' => $marcasGroup->map(fn($lineasGroup) => ['totalMarca' => $lineasGroup->flatten()->count(), 'lineas' => $lineasGroup->map(fn($items) => $items->count())])]);
        $negocio = \App\Models\Negocio::find(1);
        $pdf = new TcpdfService();
        $pdf->AddPage();
        $pdf->setEqualColumns(2, 95);
        $pdf->SetFont('', '', 9);
        $pdf->Image(public_path('img/' . $negocio->logo), $pdf->GetX(), $pdf->GetY(), 30, 0);
        $pdf->SetY($pdf->getImageRBY() + 1);
        $pdf->SetFont('', 'B', 14);
        $pdf->Write(0, "Materiales $opcion precio ($totalGeneral)\n\n", '', 0, '', true);
        $pdf->SetFont('', '', 9);
        $contador = 1;
        foreach ($agrupados as $idClase => $marcasGroup) {
            $claseNombre = $marcasGroup->first()->first()->first()?->material?->Clase?->clase ?? 'Sin clase';
            $pdf->Cell(0, 6, "$claseNombre ({$totales[$idClase]['totalClase']})", $pdf->aplicarEstilo('nivel1'), 1, 'L', true);
            foreach ($marcasGroup as $idMarca => $lineasGroup) {
                $marcaNombre = $lineasGroup->first()->first()?->material?->Linea?->Marca?->marca ?? 'Sin marca';
                $pdf->Cell(0, 6, "$marcaNombre ({$totales[$idClase]['marcas'][$idMarca]['totalMarca']})", $pdf->aplicarEstilo('nivel2'), 1, 'C', true);
                foreach ($lineasGroup as $idLinea => $items) {
                    $lineaNombre = $items->first()?->material?->Linea?->linea ?? 'Sin línea';
                    $pdf->Cell(0, 6, "$lineaNombre ({$totales[$idClase]['marcas'][$idMarca]['lineas'][$idLinea]})", $pdf->aplicarEstilo('nivel3'), 1, 'L', true);
                    $pdf->Cell(8, 6, '#', $pdf->aplicarEstilo('header'), 0, 'C', true);
                    $pdf->Cell(46, 6, 'Material', $pdf->aplicarEstilo('header'), 0, 'L', true);
                    $pdf->Cell(12, 6, 'Med', $pdf->aplicarEstilo('header'), 0, 'L', true);
                    $pdf->Cell(14, 6, 'Costo', $pdf->aplicarEstilo('header'), 0, 'R', true);
                    $pdf->Cell(15, 6, '$MXN', $pdf->aplicarEstilo('header'), 1, 'R', true);
                    $items = $items->sortBy(fn($a) => ($a->material->material ?? '') . ' ' . $a->referencia);
                    foreach ($items as $msc) {
                        $altoFila = 6;
                        $fillKey = $pdf->fillRow ? 'odd' : 'even';
                        $pdf->fillRow = !$pdf->fillRow;
                        $pdf->aplicarFondo($msc->Color->colorRgba ?? $fillKey);
                        $pdf->Cell(8, $altoFila, $contador, 1, 0, 'C', true);
                        $pdf->SetTextColor(0, 0, 0);
                        $pdf->Cell(46, $altoFila, mb_strimwidth(($msc->referencia ?? '') . ' | ' . ($msc->material->material ?? ''), 0, 35, '...'), $pdf->aplicarEstilo($fillKey), 0, 'L', true);
                        $barraPanel = $msc->Barra ? $msc->Barra->longitud : ($msc->Panel ? $msc->Panel->ancho . 'x' . $msc->Panel->alto : '');
                        $pdf->Cell(12, $altoFila, $barraPanel, $pdf->aplicarEstilo($fillKey), 0, 'L', true);
                        $pdf->Cell(14, $altoFila, ($msc->Moneda->simbolo ?? '') . number_format($msc->costo, 2), $pdf->aplicarEstilo($fillKey), 0, 'R', true);
                        $pdf->Cell(15, $altoFila, number_format($msc->valores['valorURealMXN'] ?? 0, 2), $pdf->aplicarEstilo($fillKey), 1, 'R', true);
                        $contador++;
                    }
                }
            }
        }
        $folder = public_path('reportes');
        if (!file_exists($folder)) mkdir($folder, 0755, true);
        $pdfPath = $folder . '/costos_' . ($opcion === 'con' ? 'con' : 'sin') . 'costos.pdf';
        $pdf->Output($pdfPath, 'F');
        return response()->file($pdfPath, ['Content-Type' => 'application/pdf', 'Content-Disposition' => 'inline; filename="' . basename($pdfPath) . '"']);
    }
}
