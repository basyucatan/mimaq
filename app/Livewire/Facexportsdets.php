<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Facexportsdet;
use Livewire\Attributes\Computed;
use App\Models\{Util};
use Illuminate\Support\Facades\DB;

class Facexportsdets extends Component
{
    use WithPagination;
	protected $paginationTheme = 'bootstrap';
    public $verModalFacexportsdet=false, $verModalMaterials=false, $selected_id, $keyWord, $IdFactura, 
		$IdBandeja, $productoFinal, $arancel, $cantidad, $precioU, $pesoG, 
		$castingG, $piedrasG, $diamantesG, $miscG;
public function plExport()
{
    $factura = \App\Models\Factura::with([
        'pedimento',
        'facExportsDets' => function ($query) {
            $query->orderBy('productoFinal', 'asc');
        },
        'facExportsDets.bandeja.folio.lote.orden',
        'facExportsDets.facExportsMats.facImportsDet.material'
    ])->findOrFail($this->IdFactura);
    $agrupadoPorProducto = $factura->facExportsDets->groupBy('productoFinal');
    $estructuraFinal = collect();
    $granTotalCantidad = 0;
    $granTotalPeso = 0;
    $granTotalImporte = 0;
    foreach ($agrupadoPorProducto as $nombreProducto => $detallesProducto) {
        $subTotalCantProd = $detallesProducto->sum('cantidad');
        $subTotalPesoProd = $detallesProducto->sum('pesoG');
        $subTotalImpProd = $detallesProducto->sum(function ($d) {
            return $d->cantidad * $d->precioU;
        });
        $gruposLoteEstilo = $detallesProducto->groupBy(function ($detalle) {
            $loteNum = $detalle->bandeja?->folio?->lote?->lote ?? 'Sin Lote';
            $estiloNum = $detalle->bandeja?->folio?->jobStyle ?? 'Sin Estilo';
            return $loteNum . '|' . $estiloNum;
        });
        $lotesEstilosProcesados = collect();
        foreach ($gruposLoteEstilo as $llaveAgrupacion => $coleccionDetalles) {
            $partes = explode('|', $llaveAgrupacion);
            $lote = $partes[0];
            $estilo = $partes[1];
            $sumaCantidad = $coleccionDetalles->sum('cantidad');
            $sumaPeso = $coleccionDetalles->sum('pesoG');
            $sumaImporte = $coleccionDetalles->sum(function ($d) {
                return $d->cantidad * $d->precioU;
            });
            $materialesConsolidados = [];
            foreach ($coleccionDetalles as $det) {
                foreach ($det->facExportsMats as $mat) {
                    $idEntrada = $mat->facImportsDet->IdEntradaMex ?? 'N/A';
                    $nombreMat = $mat->facImportsDet->material->material ?? 'MATERIAL';
                    $propsMat = $mat->facImportsDet->propsTot ?? '';
                    $arancelMat = $mat->facImportsDet->arancel ?? '';
                    $precioU = $mat->facImportsDet->precioU ?? 0;
                    $claveUnica = $idEntrada . '_' . $nombreMat;
                    if (!isset($materialesConsolidados[$claveUnica])) {
                        $materialesConsolidados[$claveUnica] = [
                            'identificador' => $idEntrada,
                            'descripcion' => $nombreMat . ' ' . $propsMat,
                            'cantidad' => 0,
                            'pesoG' => 0,
                            'precioU' => $precioU,
                        ];
                    }
                    $materialesConsolidados[$claveUnica]['cantidad'] += $mat->cantidad;
                    $materialesConsolidados[$claveUnica]['pesoG'] += $mat->pesoG;
                }
            }
            $lotesEstilosProcesados->push([
                'lote' => $lote,
                'estilo' => $estilo,
                'cantidad' => $sumaCantidad,
                'pesoG' => $sumaPeso,
                'importe' => $sumaImporte,
                'materiales' => array_values($materialesConsolidados)
            ]);
        }
        $granTotalCantidad += $subTotalCantProd;
        $granTotalPeso += $subTotalPesoProd;
        $granTotalImporte += $subTotalImpProd;
        $estructuraFinal->push([
            'productoFinal' => $nombreProducto,
            'cantidad' => $subTotalCantProd,
            'pesoG' => $subTotalPesoProd,
            'importe' => $subTotalImpProd,
            'bloques' => $lotesEstilosProcesados
        ]);
    }
    $totalesGlobales = [
        'cantidad' => $granTotalCantidad,
        'pesoG' => $granTotalPeso,
        'importe' => $granTotalImporte
    ];
    $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('livewire.facexportsdets.packingLPDF', [
        'factura' => $factura,
        'estructuraFinal' => $estructuraFinal,
        'totalesGlobales' => $totalesGlobales
    ]);
    return response()->streamDownload(function () use ($pdf) {
        echo $pdf->stream();
    }, 'Packing_List_' . $factura->factura . '.pdf');
}
public function factura()
{
    $factura = \App\Models\Factura::with([
        'pedimento',
        'facExportsDets'
    ])->findOrFail($this->IdFactura);
    $lineasFactura = $factura->facExportsDets->groupBy('productoFinal');
    $itemsFactura = collect();
    $totalGralCant = 0;
    $totalGralPeso = 0;
    $totalGralValorMP = 0;
    $totalGralVA = 0;
    $totalGralComercial = 0;
    foreach ($lineasFactura as $nombreProducto => $detalles) {
        $cant = $detalles->sum('cantidad');
        $peso = $detalles->sum('pesoG');
        $valorMP = $detalles->sum('valorMaterialRaw');
        $valorVA = $detalles->sum('valorAgregado');
        $totalComercial = $valorMP + $valorVA;
        $totalGralCant += $cant;
        $totalGralPeso += $peso;
        $totalGralValorMP += $valorMP;
        $totalGralVA += $valorVA;
        $totalGralComercial += $totalComercial;
        $itemsFactura->push([
            'descripcion' => $nombreProducto,
            'cantidad' => $cant,
            'pesoG' => $peso,
            'valorMP' => $valorMP,
            'valorVA' => $valorVA,
            'totalComercial' => $totalComercial,
            'precioUnitarioVA' => $cant > 0 ? ($valorVA / $cant) : 0,
            'precioUnitarioComercial' => $cant > 0 ? ($totalComercial / $cant) : 0
        ]);
    }
    $resumenArancelario = $factura->facExportsDets->groupBy('arancel')->map(function ($items) {
        return [
            'cantidad' => $items->sum('cantidad'),
            'pesoG' => $items->sum('pesoG')
        ];
    });
    $totalesFactura = [
        'cantidad' => $totalGralCant,
        'pesoG' => $totalGralPeso,
        'valorMP' => $totalGralValorMP,
        'valorVA' => $totalGralVA,
        'totalComercial' => $totalGralComercial
    ];
    $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('livewire.facexportsdets.facturaPDF', [
        'factura' => $factura,
        'itemsFactura' => $itemsFactura,
        'resumenArancelario' => $resumenArancelario,
        'totalesFactura' => $totalesFactura
    ]);
    return response()->streamDownload(function () use ($pdf) {
        echo $pdf->stream();
    }, 'Factura_' . $factura->factura . '.pdf');
}
	public $adicionales = [];
    public function verMaterials($id)
    {
        $this->selected_id = $id;
        $this->verModalMaterials = true;
    }
    public function mount(){}
    public function updatedKeyWord(){$this->resetPage();}
    #[Computed]
	public function filteredFacexportsdets()
	{
		$keyWord = '%' . $this->keyWord . '%';
		return Facexportsdet::Where('IdFactura', $this->IdFactura)
			->where(function ($query) use ($keyWord) {
				$query
						->orWhere('IdBandeja', 'LIKE', $keyWord)
						->orWhere('productoFinal', 'LIKE', $keyWord);
			})
			->paginate(12);
	}
	public function render()
	{
		return view('livewire.facexportsdets.view', [
			'facexportsdets' => $this->filteredFacexportsdets,
		]);
	}
    public function cancel()
    {
        $this->resetInput();
        $this->verModalFacexportsdet = false;
        $this->verModalMaterials = false;
    }
    public function resetInput()
    {
        $this->resetExcept('IdFactura');
    }
    public function edit($id)
    {
        $this->selected_id = $id;
		$this->fill(Facexportsdet::findOrFail($id)->toArray());
        $this->verModalFacexportsdet = true;
    }
    public function create()
    {
        $this->resetInput();
        $this->verModalFacexportsdet = true;
    }    
    public function save()
    {
        $this->validate([
		'IdFactura' => 'required',
		'productoFinal' => 'required',
		'arancel' => 'required',
		'cantidad' => 'required',
		'precioU' => 'required',
		'pesoG' => 'required',
		'castingG' => 'required',
		'diamantesG' => 'required',
		'piedrasG' => 'required',
		'miscG' => 'required',
        ]);

        Facexportsdet::updateOrCreate(
			['id' => $this->selected_id],
			[
				'IdFactura' => $this-> IdFactura,
				'IdBandeja' => $this-> IdBandeja,
				'productoFinal' => $this-> productoFinal,
				'arancel' => $this-> arancel,
				'cantidad' => $this-> cantidad,
				'precioU' => $this-> precioU,
				'pesoG' => $this-> pesoG,
				'castingG' => $this-> castingG,
				'diamantesG' => $this-> diamantesG,
				'miscG' => $this-> miscG,
				'piedrasG' => $this-> piedrasG
			]
		);
        $this->resetInput();
        $this->verModalFacexportsdet = false;
    }
    public function paginationView()
    {
        return 'livewire.paginacionBase';
    }
    public function destroy($id)
    {
        if ($id) {
            Facexportsdet::where('id', $id)->delete();
        }
    }
}