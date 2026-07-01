<?php
namespace App\Livewire;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Computed;
use Illuminate\Support\Facades\DB;
use App\Models\{Util, Bandeja, Bandejasmov};
use App\Traits\{TraspasoManager, Utilfun};
class Bandejas extends Component
{
    use WithPagination, Utilfun, TraspasoManager;
    protected $paginationTheme = 'bootstrap';
    public $verModalBandeja = false, $verModalTraspaso = false, $verModalDividir = false,
    $verModalUnir = false, $verModalHistorial = false,
    $selected_id, $keyWord, $IdFolio, $IdFactura, $codigoBandeja,
    $IdFacturaExport, $cantidad, $castingIni, $castingFin, $piedrasG,
    $piezasADividir = 1, $IdBandejaDestino,
    $diamantesG, $miscG, $IdProcesoActual, $enBoveda, $habilitada, $estatus;
    public $adicionales = [], $procesos = [], $empleados = [], $facturas = [];
    public $idBandejaTraspaso, $idProcesoDestino, $empTraspaso, $regTraspaso,
    $pesoEntrada, $pesoSalida;
    public function elegirFactura(){} //habilitar el envío a export
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
    public function mount()
    {
        $this->procesos = util::getArray('procesos');
        $this->empleados = util::getArray('empleados');
        $this->facturas = DB::table('facturas')
            ->join('pedimentos', 'pedimentos.id', '=', 'facturas.IdPedimento')
            ->where('pedimentos.regimen', 'RT')
            ->orderby('facturas.fecha', 'desc')
            ->pluck('facturas.factura', 'facturas.id')
            ->toArray();
    }
    public function updatedKeyWord()
    {
        $this->resetPage();
    }
    #[Computed]
    public function filteredBandejas()
    {
        $query = Bandeja::with(['ultimoMovimiento.proceso.depto', 'folio.lote.orden.cliente', 'folio.estilo']);
        if (!empty($this->keyWord)) {
            $partes = explode('-', $this->keyWord);
            $totalPartes = count($partes);
            if ($totalPartes >= 1 && strlen($partes[0]) === 4 && is_numeric($partes[0])) {
                $query->whereHas('folio', function ($q) use ($partes, $totalPartes) {
                    $q->where('periodo', $partes[0]);
                    if ($totalPartes >= 2 && !empty($partes[1])) {
                        $q->where('consecutivoMensual', $partes[1]);
                    }
                });
                if ($totalPartes >= 3 && !empty($partes[2])) {
                    $query->where('numeroBandeja', $partes[2]);
                }
            } else {
                $buscarParcial = '%' . $this->keyWord . '%';
                $query->where(function ($q) use ($buscarParcial) {
                    $q->whereHas('folio', function ($sub) use ($buscarParcial) {
                        $sub->where('consecutivoMensual', 'LIKE', $buscarParcial)
                            ->orWhere('jobStyle', 'LIKE', $buscarParcial)
                            ->orWhere('productoFinal', 'LIKE', $buscarParcial)
                            ->orWhere('abreviatura', 'LIKE', $buscarParcial)
                            ->orWhereHas('estilo', function ($qE) use ($buscarParcial) {
                                $qE->where('estilo', 'LIKE', $buscarParcial);
                            })
                            ->orWhereHas('lote', function ($qL) use ($buscarParcial) {
                                $qL->where('lote', 'LIKE', $buscarParcial)
                                    ->orWhereHas('orden', function ($qO) use ($buscarParcial) {
                                        $qO->where('orden', 'LIKE', $buscarParcial)
                                            ->orWhereHas('cliente', function ($qC) use ($buscarParcial) {
                                                $qC->where('cliente', 'LIKE', $buscarParcial);
                                            });
                                    });
                            });
                    })
                    ->orWhere('id', 'LIKE', $buscarParcial)
                    ->orWhere('estatus', 'LIKE', $buscarParcial)
                    ->orWhereHas('ultimoMovimiento.proceso', function ($qP) use ($buscarParcial) {
                        $qP->where('proceso', 'LIKE', $buscarParcial)
                            ->orWhereHas('depto', function ($qD) use ($buscarParcial) {
                                $qD->where('depto', 'LIKE', $buscarParcial);
                            });
                    });
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
        if (!$this->selected_id)
            return collect();
        $bandejaActual = Bandeja::find($this->selected_id);
        if (!$bandejaActual)
            return collect();
        if ($bandejaActual->IdFacturaExport)
            return collect();
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
        $this->resetExcept('procesos', 'empleados', 'keyWord', 'facturas');
    }
    public function edit($id)
    {
        $bandeja = Bandeja::findOrFail($id);
        if ($bandeja->IdFacturaExport) {
            $this->alerta('⛔ Bandeja ya integrada a Exportación.', 'warning');
            return;
        }
        $this->codigoBandeja = $bandeja->codigoBandeja;
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
            'castingIni' => 'required',
            'castingFin' => 'required',
            'piedrasG' => 'required',
            'diamantesG' => 'required',
            'miscG' => 'required',
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
                'IdFolio' => $this->IdFolio,
                'IdFacturaExport' => $this->IdFacturaExport,
                'cantidad' => $this->cantidad,
                'castingIni' => $this->castingIni,
                'castingFin' => $this->castingFin,
                'piedrasG' => $this->piedrasG,
                'diamantesG' => $this->diamantesG,
                'miscG' => $this->miscG,
                'IdProcesoActual' => $this->IdProcesoActual,
                'enBoveda' => $this->enBoveda,
                'habilitada' => $this->habilitada,
                'estatus' => $this->estatus
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
        $this->codigoBandeja = $bandeja->codigoBandeja;
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
            if ($origen->IdFacturaExport)
                return;
            $piezasOriginales = $origen->cantidad;
            $factorNueva = $this->piezasADividir / $piezasOriginales;
            $factorOrigen = ($piezasOriginales - $this->piezasADividir) / $piezasOriginales;
            $nuevaBandeja = $origen->replicate();
            $nuevaBandeja->cantidad = $this->piezasADividir;
            $nuevaBandeja->castingIni = round($origen->castingIni * $factorNueva, 4);
            $nuevaBandeja->castingFin = round($origen->castingFin * $factorNueva, 4);
            $nuevaBandeja->piedrasG = round($origen->piedrasG * $factorNueva, 4);
            $nuevaBandeja->diamantesG = round($origen->diamantesG * $factorNueva, 4);
            $nuevaBandeja->miscG = round($origen->miscG * $factorNueva, 4);
            $nuevaBandeja->save();
            $origen->cantidad = $piezasOriginales - $this->piezasADividir;
            $origen->castingIni = round($origen->castingIni * $factorOrigen, 4);
            $origen->castingFin = round($origen->castingFin * $factorOrigen, 4);
            $origen->piedrasG = round($origen->piedrasG * $factorOrigen, 4);
            $origen->diamantesG = round($origen->diamantesG * $factorOrigen, 4);
            $origen->miscG = round($origen->miscG * $factorOrigen, 4);
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
                    'IdUser' => Auth()->user()->id,
                    'IdEmpleado' => null,
                    'pesoEntrada' => $origen->castingFin,
                    'pesoSalida' => $origen->castingFin,
                    'fechaHEntrada' => now(),
                    'fechaHSalida' => now()
                ]);
                Bandejasmov::create([
                    'IdBandeja' => $nuevaBandeja->id,
                    'IdProceso' => $nuevaBandeja->IdProcesoActual,
                    'IdUser' => Auth()->user()->id,
                    'IdEmpleado' => null,
                    'pesoEntrada' => $nuevaBandeja->castingFin,
                    'pesoSalida' => $nuevaBandeja->castingFin,
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
        $this->codigoBandeja = $bandeja->codigoBandeja;
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
            if ($origen->IdFacturaExport || $destino->IdFacturaExport)
                return;
            if ($origen->IdFolio !== $destino->IdFolio || $origen->IdProcesoActual !== $destino->IdProcesoActual) {
                return;
            }
            $destino->cantidad += $origen->cantidad;
            $destino->castingIni += $origen->castingIni;
            $destino->castingFin += $origen->castingFin;
            $destino->piedrasG += $origen->piedrasG;
            $destino->diamantesG += $origen->diamantesG;
            $destino->miscG += $origen->miscG;
            $destino->save();
            Bandejasmov::where('IdBandeja', $origen->id)->update(['IdBandeja' => $destino->id]);
            if ($destino->IdProcesoActual) {
                Bandejasmov::create([
                    'IdBandeja' => $destino->id,
                    'IdProceso' => $destino->IdProcesoActual,
                    'IdUser' => Auth()->user()->id,
                    'IdEmpleado' => null,
                    'pesoEntrada' => $destino->castingFin,
                    'pesoSalida' => $destino->castingFin,
                    'fechaHEntrada' => now(),
                    'fechaHSalida' => now()
                ]);
            }
            $origen->delete();
        });
        $this->resetInput();
    }
}