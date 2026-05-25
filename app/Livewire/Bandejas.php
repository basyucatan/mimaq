<?php
namespace App\Livewire;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Bandeja;
use App\Models\Bandejasmov;
use Livewire\Attributes\Computed;
use App\Models\{Util, Facexportsdet, FacExportsMat};
use Illuminate\Support\Facades\DB;
use App\Traits\Utilfun;
class Bandejas extends Component
{
    use WithPagination, Utilfun;
    protected $paginationTheme = 'bootstrap';
    public $verModalBandeja=false, $verModalTraspaso = false, $verModalDividir = false,
        $verModalUnir = false, $verModalHistorial = false,
        $selected_id, $keyWord, $IdFolio, $IdFactura, $codigoBandeja,
        $IdFacturaExport, $cantidad, $castingInicial, $castingFinal, $piedras, 
        $piezasADividir = 1, $IdBandejaDestino,
        $diamantes, $miscelaneo, $IdProcesoActual, $enBoveda, $habilitada, $estatus;
    public $adicionales = [], $procesos = [], $empleados = [], $facturas = [];
    public $idBandejaTraspaso, $idProcesoDestino, $idEmpleadoTraspaso, $pesoEntradaTraspaso, $pesoSalidaTraspaso;
public function exportar()
{
    if (empty($this->IdFactura)) {
        return;
    }
    $bandejas = Bandeja::where('estatus', 'terminado')
        ->where('adicionales->IdFactura', $this->IdFactura)
        ->with([
            'folio.estilo.clase.arancel',
            'folio.foliosmats.material.clase',
            'folio.foliosmats.facImportsDet'
        ])
        ->get();
    if ($bandejas->isEmpty()) {
        return;
    }
    foreach ($bandejas as $bandeja) {
        $folio = $bandeja->folio;
        if (!$folio || $folio->cantidad <= 0) {
            continue;
        }
        $arancel = $folio->estilo?->clase?->arancel?->arancel ?? '';
        $pesoTotal = $bandeja->castingFin + $bandeja->piedrasG + $bandeja->diamantesG + $bandeja->miscG;
        $exportDet = FacExportsDet::create([
            'IdFactura' => $this->IdFactura,
            'IdBandeja' => $bandeja->id,
            'productoFinal' => $folio->productoFinal ?? '--',
            'arancel' => $arancel,
            'cantidad' => $bandeja->cantidad,
            'precioU' => $folio->precioU(),
            'pesoG' => $pesoTotal,
            'castingIni' => $bandeja->castingIni,
            'castingG' => $bandeja->castingFin,
            'piedrasG' => $bandeja->piedrasG,
            'diamantesG' => $bandeja->diamantesG,
            'miscG' => $bandeja->miscG,
            'adicionales' => null
        ]);
        $composicion = $folio->adicionales['composicion'] ?? [];
        foreach ($composicion as $indice => $datosComponente) {
            $cantidadPorPieza = $datosComponente['cantidad'] ?? 0;
            $idTipo = $datosComponente['idTipo'] ?? null;
            $cantidadRequeridaBandeja = $cantidadPorPieza * $bandeja->cantidad;
            if ($cantidadRequeridaBandeja <= 0 || !$idTipo) {
                continue;
            }
            $materialesFolio = $folio->foliosmats->filter(function ($item) use ($idTipo) {
                return $item->IdTipo == $idTipo;
            })->sortBy('id');
            foreach ($materialesFolio as $mat) {
                if ($cantidadRequeridaBandeja <= 0) {
                    break;
                }
                $consumidoPreviamente = FacExportsMat::where('IdFacImportsDet', $mat->IdFacImportsDet)
                    ->whereHas('facExportsDet', function ($query) use ($folio) {
                        $query->where('IdBandeja', '!=', 0)
                            ->whereHas('bandeja', function ($q) use ($folio) {
                                $q->where('IdFolio', $folio->id);
                            });
                    })->sum('cantidad');
                $saldoDisponibleInm = $mat->cantidad - $consumidoPreviamente;
                if ($saldoDisponibleInm <= 0) {
                    continue;
                }
                $pesoUnitarioImportacion = $mat->cantidad > 0 ? ($mat->pesoG / $mat->cantidad) : 0;
                $cantidadADescargar = min($cantidadRequeridaBandeja, $saldoDisponibleInm);
                $pesoADescargar = $cantidadADescargar * $pesoUnitarioImportacion;
                FacExportsMat::create([
                    'IdFacExportsDet' => $exportDet->id,
                    'IdFacImportsDet' => $mat->IdFacImportsDet,
                    'cantidad' => $cantidadADescargar,
                    'pesoG' => $pesoADescargar
                ]);
                $cantidadRequeridaBandeja -= $cantidadADescargar;
            }
        }
        $bandeja->IdFacturaExport = $this->IdFactura;
        $bandeja->estatus = 'exportado';
        $bandeja->save();
    }
    $this->dispatch('render');
}
public function terminar($id)
{
    $this->validate([
        'IdFactura' => 'required'
    ]);
    $bandeja = Bandeja::find($id);
    if ($bandeja) {
        $adicionales = $bandeja->adicionales ?? [];
        if ($bandeja->estatus === 'terminado') {
            $bandeja->estatus = 'proceso';
            unset($adicionales['IdFactura']);
        } else {
            $bandeja->estatus = 'terminado';
            $adicionales['IdFactura'] = $this->IdFactura;
        }
        $bandeja->adicionales = $adicionales;
        $bandeja->save();
    }
}
    public function elegirFactura(){}
    public function verHistorial($id)
    {
        $this->selected_id = $id;
        $this->verModalHistorial = true;
    }
    public function cerrarModalHistorial()
    {
        $this->verModalHistorial = false;
        $this->reset('selected_id');
    }
    public function traspasar($id){
        $bandeja = Bandeja::find($id);
        if ($bandeja) {
            $this->iniciarTraspaso($bandeja->id);
        } else {
            $this->dispatch('error', ['message' => 'La bandeja escaneada no existe.']);
        }
    }    
    public function escanear()
    {
        if (empty($this->selected_id)) {return;}
        $this->traspasar($this->selected_id);
        $this->reset('selected_id');
    } 
    public function iniciarTraspaso($id)
    {
        $this->idBandejaTraspaso = $id;
        $bandeja = Bandeja::findOrFail($id);
        $this->codigoBandeja = $bandeja->codigoBandeja;
        $this->idProcesoDestino = $bandeja->IdProcesoActual;
        
        $ultimoMov = Bandejasmov::where('IdBandeja', $id)
            ->whereNull('fechaHSalida')
            ->latest('id')
            ->first();

        if ($ultimoMov) {
            $this->idEmpleadoTraspaso = $ultimoMov->IdEmpleado;
            $this->pesoEntradaTraspaso = $ultimoMov->pesoEntrada;
        } else {
            $this->idEmpleadoTraspaso = null;
            $this->pesoEntradaTraspaso = null;
        }
        
        $this->pesoSalidaTraspaso = null;
        $this->verModalTraspaso = true;
    }

public function guardarTraspaso()
{
    $this->validate([
        'idProcesoDestino' => 'required|exists:procesos,id',
        'idEmpleadoTraspaso' => 'required|exists:empleados,id',
    ]);

    \DB::transaction(function () {

        $movimientoActivo = Bandejasmov::where('IdBandeja', $this->idBandejaTraspaso)
            ->whereNull('fechaHSalida')
            ->latest('id')
            ->first();

        if ($movimientoActivo) {

            $movimientoActivo->update([
                'pesoSalida' => $this->pesoSalidaTraspaso,
                'fechaHSalida' => now()->tz('America/Mexico_City')
            ]);
        }

        Bandejasmov::create([
            'IdBandeja' => $this->idBandejaTraspaso,
            'IdProceso' => $this->idProcesoDestino,
            'IdEmpleado' => $this->idEmpleadoTraspaso,
            'pesoEntrada' => $this->pesoEntradaTraspaso,
            'fechaHEntrada' => now()->tz('America/Mexico_City'),
            'fechaHSalida' => now()->tz('America/Mexico_City'),
        ]);
    });

    $this->verModalTraspaso = false;

    $this->reset([
        'idBandejaTraspaso',
        'idProcesoDestino',
        'idEmpleadoTraspaso',
        'pesoEntradaTraspaso',
        'pesoSalidaTraspaso'
    ]);
}
    public function cerrarModalTraspaso()
    {
        $this->verModalTraspaso = false;
        $this->reset(['idBandejaTraspaso', 'idProcesoDestino', 'idEmpleadoTraspaso', 'pesoEntradaTraspaso', 'pesoSalidaTraspaso']);
    }       
    public function mount(){
        $this->procesos = util::getArray('procesos');
        $this->empleados = util::getArray('empleados');
        $this->facturas = DB::table('facturas')
            ->join('pedimentos','pedimentos.id','=','facturas.IdPedimento')
            ->where('pedimentos.regimen', 'RT')
            ->orderby('facturas.fecha', 'desc')
            ->pluck('facturas.factura','facturas.id')
            ->toArray();         
    }
    public function updatedKeyWord(){$this->resetPage();}
    #[Computed]
public function filteredBandejas()
{
    $query = Bandeja::query();
    if (!empty($this->keyWord)) {
        $partes = explode('-', $this->keyWord);
        $totalPartes = count($partes);
        if ($totalPartes === 1 && strlen($partes[0]) === 4 && is_numeric($partes[0])) {
            $query->whereHas('folio', function ($q) use ($partes) {
                $q->where('periodo', $partes[0]);
            });
        } elseif ($totalPartes === 2 && strlen($partes[0]) === 4 && !empty($partes[1])) {
            $query->whereHas('folio', function ($q) use ($partes) {
                $q->where('periodo', $partes[0])->where('consecutivoMensual', $partes[1]);
            });
        } elseif ($totalPartes === 3 && strlen($partes[0]) === 4 && !empty($partes[1]) && !empty($partes[2])) {
            $query->whereHas('folio', function ($q) use ($partes) {
                $q->where('periodo', $partes[0])->where('consecutivoMensual', $partes[1]);
            });
            $query->where('numeroBandeja', $partes[2]);
        } else {
            $buscarParcial = '%' . $this->keyWord . '%';
            $query->where(function ($q) use ($buscarParcial) {
                $q->whereHas('folio', function ($subQuery) use ($buscarParcial) {
                    $subQuery->where('consecutivoMensual', 'LIKE', $buscarParcial)
                        ->orWhere('jobStyle', 'LIKE', $buscarParcial)
                        ->orWhere('productoFinal', 'LIKE', $buscarParcial)
                        ->orWhereHas('lote', function ($qLote) use ($buscarParcial) {
                            $qLote->where('lote', 'LIKE', $buscarParcial)
                                ->orWhereHas('orden', function ($qOrden) use ($buscarParcial) {
                                    $qOrden->where('orden', 'LIKE', $buscarParcial)
                                        ->orWhereHas('cliente', function ($qCliente) use ($buscarParcial) {
                                            $qCliente->where('cliente', 'LIKE', $buscarParcial);
                                        });
                                });
                        });
                })->orWhere('id', 'LIKE', $buscarParcial)
                ->orWhere('estatus', 'LIKE', $buscarParcial);
            });
        }
    }
    return $query->latest()->paginate(12);
}
	public function render()
    {
        return view('livewire.bandejas.view', [
            'bandejas' => $this->filteredBandejas,
            'bandejasCompatibles' => $this->compatibles(),
        ]);
    }
    public function compatibles()
    {
        if (!$this->selected_id) return collect();
        $bandejaActual = Bandeja::find($this->selected_id);
        if (!$bandejaActual) return collect();
        if ($bandejaActual->IdFacturaExport) return collect();
        return Bandeja::where('IdFolio', $bandejaActual->IdFolio)
            ->where('id', '!=', $this->selected_id)
            ->where('IdProcesoActual', $bandejaActual->IdProcesoActual)
            ->whereNull('IdFacturaExport')
            ->get();
    }
    public function cancel()
    {
        $this->resetInput();
        $this->verModalBandeja = false;
        $this->verModalDividir = false;
        $this->verModalUnir = false;
    }
    public function resetInput()
    {
        $this->resetExcept('procesos', 'empleados');
    }
    public function edit($id)
    {
        $bandeja = Bandeja::findOrFail($id);
        if ($bandeja->IdFacturaExport) {
            $this->alerta('⛔ Bandeja ya integrada a Exportación.', 'warning');
            return;
        }
        $this->selected_id = $id;
        $this->fill($bandeja->toArray());
        $this->verModalBandeja = true;
    }
    public function create()
    {
        $this->resetInput();
        $this->verModalBandeja = true;
    }    
    public function save()
    {
        $this->validate([
            'IdFolio' => 'required',
            'cantidad' => 'required',
            'castingInicial' => 'required',
            'castingFinal' => 'required',
            'piedras' => 'required',
            'diamantes' => 'required',
            'miscelaneo' => 'required',
            'enBoveda' => 'required',
            'habilitada' => 'required',
            'estatus' => 'required',
        ]);
        if ($this->selected_id) {
            $bandejaExistente = Bandeja::find($this->selected_id);
            if ($bandejaExistente && $bandejaExistente->IdFacturaExport) {
                session()->flash('error', 'Acción denegada por Factura de Exportación.');
                return;
            }
        }
        Bandeja::updateOrCreate(
            ['id' => $this->selected_id],
            [
                'IdFolio' => $this-> IdFolio,
                'IdFacturaExport' => $this-> IdFacturaExport,
                'cantidad' => $this-> cantidad,
                'castingInicial' => $this-> castingInicial,
                'castingFinal' => $this-> castingFinal,
                'piedras' => $this-> piedras,
                'diamantes' => $this-> diamantes,
                'miscelaneo' => $this-> miscelaneo,
                'IdProcesoActual' => $this-> IdProcesoActual,
                'enBoveda' => $this-> enBoveda,
                'habilitada' => $this-> habilitada,
                'estatus' => $this-> estatus
            ]
        );
        $this->resetInput();
        $this->verModalBandeja = false;
    }
    public function paginationView()
    {
        return 'livewire.paginacionBase';
    }
    public function destroy($id)
    {
        if ($id) {
            $bandeja = Bandeja::find($id);
            if ($bandeja && $bandeja->IdFacturaExport) {
                session()->flash('error', 'No se puede eliminar una bandeja facturada.');
                return;
            }
            Bandeja::where('id', $id)->delete();
        }
    }
    public function iniciarDivision($id)
    {
        $bandeja = Bandeja::findOrFail($id);
        if ($bandeja->IdFacturaExport) {
            session()->flash('error', 'No se puede dividir una bandeja vinculada a una Factura de Exportación.');
            return;
        }
        $this->selected_id = $id;
        $this->cantidad = $bandeja->cantidad;
        $this->piezasADividir = 1;
        $this->verModalDividir = true;
    }
	public function procesarDivision()
    {
        $this->validate([
            'piezasADividir' => 'required|integer|min:1|max:' . ($this->cantidad - 1)
        ]);
        DB::transaction(function () {
            $origen = Bandeja::lockForUpdate()->findOrFail($this->selected_id);
            if ($origen->IdFacturaExport) return;
            $piezasOriginales = $origen->cantidad;
            $factorNueva = $this->piezasADividir / $piezasOriginales;
            $factorOrigen = ($piezasOriginales - $this->piezasADividir) / $piezasOriginales;
            $nuevaBandeja = $origen->replicate();
            $nuevaBandeja->cantidad = $this->piezasADividir;
            $nuevaBandeja->castingInicial = round($origen->castingInicial * $factorNueva, 4);
            $nuevaBandeja->castingFinal = round($origen->castingFinal * $factorNueva, 4);
            $nuevaBandeja->piedras = round($origen->piedras * $factorNueva, 4);
            $nuevaBandeja->diamantes = round($origen->diamantes * $factorNueva, 4);
            $nuevaBandeja->miscelaneo = round($origen->miscelaneo * $factorNueva, 4);
            $nuevaBandeja->save();
            $origen->cantidad = $piezasOriginales - $this->piezasADividir;
            $origen->castingInicial = round($origen->castingInicial * $factorOrigen, 4);
            $origen->castingFinal = round($origen->castingFinal * $factorOrigen, 4);
            $origen->piedras = round($origen->piedras * $factorOrigen, 4);
            $origen->diamantes = round($origen->diamantes * $factorOrigen, 4);
            $origen->miscelaneo = round($origen->miscelaneo * $factorOrigen, 4);
            $origen->save();
            $movimientosPrevios = Bandejasmov::where('IdBandeja', $origen->id)->get();
            foreach ($movimientosPrevios as $movimiento) {
                $nuevoMovimiento = $movimiento->replicate();
                $nuevoMovimiento->IdBandeja = $nuevaBandeja->id;
                $nuevoMovimiento->pesoEntrada = round($movimiento->pesoEntrada * $factorNueva, 4);
                if ($movimiento->pesoSalida !== null) {
                    $nuevoMovimiento->pesoSalida = round($movimiento->pesoSalida * $factorNueva, 4);
                }
                $nuevoMovimiento->save();
                $movimiento->pesoEntrada = round($movimiento->pesoEntrada * $factorOrigen, 4);
                if ($movimiento->pesoSalida !== null) {
                    $movimiento->pesoSalida = round($movimiento->pesoSalida * $factorOrigen, 4);
                }
                $movimiento->save();
            }
            if ($origen->IdProcesoActual) {
                Bandejasmov::create([
                    'IdBandeja' => $origen->id,
                    'IdProceso' => $origen->IdProcesoActual,
                    'IdEmpleado' => null,
                    'pesoEntrada' => $origen->castingFinal,
                    'pesoSalida' => $origen->castingFinal,
                    'fechaHEntrada' => now(),
                    'fechaHSalida' => now()
                ]);
                Bandejasmov::create([
                    'IdBandeja' => $nuevaBandeja->id,
                    'IdProceso' => $nuevaBandeja->IdProcesoActual,
                    'IdEmpleado' => null,
                    'pesoEntrada' => $nuevaBandeja->castingFinal,
                    'pesoSalida' => $nuevaBandeja->castingFinal,
                    'fechaHEntrada' => now(),
                    'fechaHSalida' => now()
                ]);
            }
        });
        $this->resetInput();
    }
	public function iniciarUnion($id)
    {
        $bandeja = Bandeja::findOrFail($id);
        if ($bandeja->IdFacturaExport) {
            session()->flash('error', 'No se puede unir una bandeja vinculada a una Factura de Exportación.');
            return;
        }
        $this->selected_id = $id;
        $this->verModalUnir = true;
    }
    public function procesarUnion()
    {
        $this->validate([
            'IdBandejaDestino' => 'required|exists:bandejas,id'
        ]);
        DB::transaction(function () {
            $origen = Bandeja::lockForUpdate()->findOrFail($this->selected_id);
            $destino = Bandeja::lockForUpdate()->findOrFail($this->IdBandejaDestino);
            if ($origen->IdFacturaExport || $destino->IdFacturaExport) return;
            if ($origen->IdFolio !== $destino->IdFolio || $origen->IdProcesoActual !== $destino->IdProcesoActual) {
                return;
            }
            $destino->cantidad += $origen->cantidad;
            $destino->castingInicial += $origen->castingInicial;
            $destino->castingFinal += $origen->castingFinal;
            $destino->piedras += $origen->piedras;
            $destino->diamantes += $origen->diamantes;
            $destino->miscelaneo += $origen->miscelaneo;
            $destino->save();
            Bandejasmov::where('IdBandeja', $origen->id)->update(['IdBandeja' => $destino->id]);
            if ($destino->IdProcesoActual) {
                Bandejasmov::create([
                    'IdBandeja' => $destino->id,
                    'IdProceso' => $destino->IdProcesoActual,
                    'IdEmpleado' => null,
                    'pesoEntrada' => $destino->castingFinal,
                    'pesoSalida' => $destino->castingFinal,
                    'fechaHEntrada' => now(),
                    'fechaHSalida' => now()
                ]);
            }
            $origen->delete();
        });
        $this->resetInput();
    }
}