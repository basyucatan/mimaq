<?php
namespace App\Livewire;
use Livewire\Component;
use App\Models\{Util, Facimportsdet, Orden, Lote, Folio, Estilo, Factura};
use Illuminate\Support\Facades\DB;
use App\Traits\Utilfun;
use Livewire\WithPagination;
class Arbolfolios extends Component
{
    use Utilfun, WithPagination;
    public $keyWord = '', $tipoModal = null;
    public $selected_id, $IdOrden, $IdCliente, $cliente, $IdLote, $orden, $lote, $IdEstilo,
        $cantidad, $totalBandejas, $jobStyle, $abreviatura, $productoFinal, $IdFactura,
        $kt = '', $color = '',
        $fechaVen, $estatus = 'abierto';
    public $expandir = ['Orden' => [], 'Lote' => []], $estilos = [], $clientes = [], $adicionales = [],
        $alertas = [], $kts = [], $colors = [], $ordens = [], $facturas = [];
    protected $listeners = ['estilosDetsActualizado' => 'generarDef'];
    public function updated($propertyName)
    {
        if (in_array($propertyName, ['penalty', 'rush', 'alertaGeneral'])) {
            $this->alertas = [
                'penalty' => (bool) $this->penalty,
                'rush' => (bool) $this->rush,
                'alertaGeneral' => trim($this->alertaGeneral)
            ];
        }
        if($propertyName ='IdFactura'){
            $this->expandir = ['Orden' => [], 'Lote' => []];
        }
    }
    public function mount(){
        $this->facturas = Factura::whereHas('pedimento', function ($q) {
            $q->where('regimen', 'IN');})->pluck('factura', 'id')->toArray();
        $this->estilos = Util::getArray('estilos');
        $this->clientes = Util::getArray('clientes');
        $config = json_decode(file_get_contents(base_path('settings.json')), true);
        $this->kts = collect($config['kts'] ?? [])->pluck('valor')->toArray();
        $this->colors = collect($config['colors'] ?? [])->pluck('valor')->toArray();
    }
public function imprimirFolios()
{
    if (empty($this->IdFactura)) {
        return;
    }
$folios = Folio::with([
    'lote.orden.cliente',
    'estilo.clase.arancel',
    'foliosmats.material.clase',
    'foliosmats.material.unidad',
    'bandejas' => function ($query) {
        $query->orderBy('numeroBandeja');
    }
])
->where('adicionales->IdFactura', $this->IdFactura)
->take(10) 
->get();
// dd($folios);
    if ($folios->isEmpty()) {
        $this->alerta('⛔ No se encontraron folios para esta factura', 'warning');
        return;
    }
    $coleccionFoliosProcesados = [];
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
    foreach ($folios as $folio) {
        if ($folio->bandejas->isEmpty()) {
            continue;
        }
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
        $coleccionFoliosProcesados[] = [
            'folio' => $folio,
            'materialesAgrupados' => $materialesAgrupados
        ];
    }
    if (empty($coleccionFoliosProcesados)) {
        $this->alerta('⛔ Los folios encontrados no tienen bandejas asignadas', 'warning');
        return;
    }
    $htmlMasivo = view('livewire.folios.foliosMasivoPDF', [
        'coleccionFolios' => $coleccionFoliosProcesados,
        'procesosEstandar' => $procesosEstandar
    ])->render();
    $instanciaDompdf = \Pdf::loadHTML($htmlMasivo);
    $instanciaDompdf->setPaper('letter', 'landscape');
    $contenidoPdf = $instanciaDompdf->output();
    $rutaArchivo = 'folios/masivo_factura_' . $this->IdFactura . '.pdf';
    \Storage::disk('public')->put($rutaArchivo, $contenidoPdf);
    $rutaFisica = storage_path('app/public/' . $rutaArchivo);
    return response()->file($rutaFisica, [
        'Content-Type' => 'application/pdf',
        'Content-Disposition' => 'inline; filename="masivo_factura_' . $this->IdFactura . '.pdf"'
    ]);
}
    public function elegir($tipo, $id)
    {
        if ($tipo == 'Folio') {
            $this->selected_id = $id;
            $this->dispatch('IdFolioElecto', $id);
        } else {
            $this->alternarNodo($tipo, $id);
        }
    }

    public function alternarNodo($tipo, $id)
    {
        if (isset($this->expandir[$tipo])) {
            $this->expandir[$tipo][$id] = !($this->expandir[$tipo][$id] ?? false);
        }
    }
    public function replegarTodo()
    {
        $this->keyWord = '';
        $this->expandir = ['Orden' => [], 'Lote' => []];
    }

    public function agregar($tipo, $id)
    {
        if ($tipo == 'Orden') $this->editarOrden($id);
        elseif ($tipo == 'Lote') $this->editarLote($id);
        elseif ($tipo == 'Folio') $this->editarFolio($id);
    }
    public function nuevaOrden()
    {
        $this->resetInput();
        $this->fechaVen = now()->format('Y-m-d');
        $this->estatus = 'abierto';
        $this->tipoModal = 'Orden';
    }
    public function editarOrden($id)
    {
        $registro = Orden::findOrFail($id);
        $this->selected_id = $id;
        $this->orden = $registro->orden;
        $this->IdCliente = $registro->IdCliente;
        $this->cliente = $registro->cliente?->cliente;
        $this->fechaVen = $registro->fechaVen;
        $this->estatus = $registro->estatus;
        $this->tipoModal = 'Orden';
    }
    public function nuevoLote($idOrden)
    {
        $this->resetInput();
        $this->IdOrden = $idOrden;
        $ultimoLote = Lote::where('IdOrden', $idOrden)->max('lote');
        $this->lote = $ultimoLote ? $ultimoLote + 1 : 1;
        $this->tipoModal = 'Lote';
    }
public function editarLote($id)
    {
        $registro = Lote::with('orden')->findOrFail($id);
        $this->selected_id = $id;
        $this->lote = $registro->lote;
        $this->IdOrden = $registro->IdOrden;
        $this->ordens = DB::table('ordens')
            ->where('IdCliente', $registro->orden->IdCliente)
            ->orderByDesc('id')
            ->get();
        $this->tipoModal = 'Lote';
    }
    public function nuevoFolio($idLote)
    {
        $this->resetInput();
        $this->IdLote = $idLote;
        $this->cantidad = 1;
        $this->totalBandejas = 1;
        $this->fechaVen = now()->tz('America/Mexico_City')->addDays(7)->format('Y-m-d');
        $this->alertas = [
            'penalty' => false,
            'rush' => false,
            'alertaGeneral' => ''
        ];
        $this->estatus = 'abierto';
        $this->tipoModal = 'Folio';
    }
    public function editarFolio($id)
    {
        $registro = Folio::findOrFail($id);
        $this->selected_id = $id;
        $this->IdLote = $registro->IdLote;
        $this->IdEstilo = $registro->IdEstilo;
        $this->cantidad = $registro->cantidad ?? 1;
        $this->totalBandejas = $registro->totalBandejas;
        $this->jobStyle = $registro->jobStyle;
        $this->abreviatura = $registro->abreviatura;
        $this->productoFinal = $registro->productoFinal;
        $this->fechaVen = $registro->fechaVen;
        $this->estatus = $registro->estatus;
        $this->alertas = $registro->alertas ?? [
            'penalty' => false,
            'rush' => false,
            'alertaGeneral' => ''
        ];
        $this->adicionales = $registro->adicionales ?? [];
        $this->kt = $this->adicionales['kt'] ?? '';
        $this->color = $this->adicionales['color'] ?? '';
        $this->tipoModal = 'Folio';
    }
    public function generarDef()
    {
        if (!$this->IdEstilo) return;
        $folioEdit = new Folio();
        $folioEdit->adicionales = $this->adicionales;
        $folioEdit->definirProducto($this->IdEstilo, $this->cantidad, $this->kt, $this->color);
        $this->jobStyle = $folioEdit->jobStyle;
        $this->abreviatura = $folioEdit->abreviatura;
        $this->productoFinal = $folioEdit->productoFinal;
        $this->totalBandejas = $folioEdit->totalBandejas;
        $this->adicionales = $folioEdit->adicionales;
    }
    public function guardar()
    {
        if ($this->tipoModal == 'Orden') {
            $this->orden = strtoupper(trim($this->orden));
            $this->IdCliente = array_search(trim($this->cliente), $this->clientes) ?: null;
            $this->validate([
                'orden' => 'required',
                'fechaVen' => 'required|date',
                'IdCliente' => 'required'
            ], [
                'IdCliente.required' => 'El cliente escrito no es válido. Por favor, selecciona uno de la lista.'
            ]);
            $ordenExistente = Orden::where('orden', $this->orden)->first();
            if ($ordenExistente && $ordenExistente->id != $this->selected_id) {
                $this->addError('orden', 'Esta orden ya existe');
                return;
            }
            $idBusqueda = $this->selected_id ?: ($ordenExistente?->id ?? null);
            Orden::updateOrCreate(['id' => $idBusqueda], [
                'orden' => $this->orden,
                'IdCliente' => $this->IdCliente,
                'estatus' => $this->estatus,
                'fechaVen' => $this->fechaVen
            ]);
        } elseif ($this->tipoModal == 'Lote') {
            $this->validate(['lote' => 'required|numeric', 'IdOrden' => 'required']);
            Lote::updateOrCreate(['id' => $this->selected_id], [
                'lote' => $this->lote,
                'IdOrden' => $this->IdOrden
            ]);
        } elseif ($this->tipoModal == 'Folio') {
            $this->validate(['IdEstilo' => 'required', 'cantidad' => 'required|numeric', 'IdLote' => 'required']);
            $this->jobStyle = $this->selected_id ? $this->jobStyle : Estilo::find($this->IdEstilo)?->estilo;
            $this->adicionales = array_merge($this->adicionales ?? [], [
                'kt' => is_array($this->kt) ? ($this->kt['valor'] ?? '') : $this->kt,
                'color' => is_array($this->color) ? ($this->color['valor'] ?? '') : $this->color
            ]);
            Folio::updateOrCreate(['id' => $this->selected_id], [
                'IdLote' => $this->IdLote,
                'IdEstilo' => $this->IdEstilo,
                'cantidad' => $this->cantidad,
                'totalBandejas' => $this->totalBandejas,
                'fechaVen' => $this->fechaVen,
                'jobStyle' => $this->jobStyle,
                'abreviatura' => $this->abreviatura,
                'productoFinal' => $this->productoFinal,
                'alertas' => $this->alertas,
                'adicionales' => $this->adicionales,
                'estatus' => $this->estatus,
                'precioU' => 0
            ]);
        }
        $this->cancel();
    }
    public function destroy($tipo, $id)
    {
        $modelos = [
            'Orden' => Orden::class, 
            'Lote' => Lote::class, 
            'Folio' => Folio::class 
        ];
        if (!isset($modelos[$tipo])) return;
        $registro = $modelos[$tipo]::find($id);
        if (!$registro) return;
        $tieneReservasFiscales = false;
        if ($tipo == 'Folio') {
            $tieneReservasFiscales = Facimportsdet::where('IdFolio', $id)->exists();
        } elseif ($tipo == 'Lote') {
            $tieneReservasFiscales = Facimportsdet::whereIn('IdFolio', function($query) use ($id) {
                $query->select('id')->from('folios')->where('IdLote', $id);
            })->exists();
        } elseif ($tipo == 'Orden') {
            $tieneReservasFiscales = Facimportsdet::whereIn('IdFolio', function($query) use ($id) {
                $query->select('f.id')
                    ->from('folios as f')
                    ->join('lotes as l', 'f.IdLote', '=', 'l.id')
                    ->where('l.IdOrden', $id);
            })->exists();
        }
        if ($tieneReservasFiscales) {
            $this->alerta('Tiene materiales definidos en la importación', 'error', 2000);
            return;
        }
        $registro->delete();
        if ($tipo == 'Folio' && $this->selected_id == $id) {
            $this->selected_id = null;
        }
        $this->dispatch('refreshComponent');
    }    
    public function cancel()
    {
        $this->tipoModal = null;
        $this->resetInput();
    }
    private function resetInput()
    {
        $this->resetExcept(['selected_id', 'expandir', 'keyWord', 'estilos','clientes',
            'kts','colors','facturas']);
        $this->selected_id = null;
    }
public function render()
{
    $keyWord = trim($this->keyWord);

    $consulta = !empty($this->IdFactura)
        ? Orden::query()->orderBy('orden')
        : Orden::query()->orderByDesc('id');

    if (!empty($this->IdFactura)) {
        $idFactura = $this->IdFactura;

        $consulta->whereHas('lotes.folios', function ($qF) use ($idFactura) {
            $qF->where('adicionales->IdFactura', $idFactura);
        });
    }

    if ($keyWord !== '') {
        $like = '%' . $keyWord . '%';

        $consulta->where(function ($q) use ($like) {
            $q->whereHas('cliente', function ($qC) use ($like) {
                $qC->where('cliente', 'like', $like);
            })
            ->orWhere('orden', 'like', $like)
            ->orWhereHas('lotes', function ($qL) use ($like) {
                $qL->where('lote', 'like', $like);
            })
            ->orWhereHas('lotes.folios', function ($qF) use ($like) {
                $qF->where('id', 'like', $like)
                    ->orWhereHas('Estilo', function ($qE) use ($like) {
                        $qE->where('estilo', 'like', $like);
                    });
            });
        });
    }

    $arbol = $consulta
        ->with('cliente', 'lotes.folios.Estilo')
        ->paginate(50);

    if ($keyWord !== '') {
        foreach ($arbol as $orden) {
            $clienteCoincide = $orden->cliente &&
                stripos($orden->cliente->cliente, $keyWord) !== false;

            $ordenCoincide = stripos($orden->orden, $keyWord) !== false;

            if ($clienteCoincide || $ordenCoincide) {
                continue;
            }

            foreach ($orden->lotes as $lote) {
                $loteCoincide = stripos((string) $lote->lote, $keyWord) !== false;

                if ($loteCoincide) {
                    $this->expandir['Orden'][$orden->id] = true;
                    $this->expandir['Lote'][$lote->id] = true;
                    continue;
                }

                foreach ($lote->folios as $folio) {
                    $folioCoincide = stripos((string) $folio->id, $keyWord) !== false;

                    $estiloCoincide = $folio->Estilo &&
                        stripos($folio->Estilo->estilo, $keyWord) !== false;

                    if ($folioCoincide || $estiloCoincide) {
                        $this->expandir['Orden'][$orden->id] = true;
                        $this->expandir['Lote'][$lote->id] = true;
                        $this->expandir['Folio'][$folio->id] = true;
                    }
                }
            }
        }
    }

    return view('livewire.arbolfolios.view', [
        'arbol' => $arbol
    ]);
}
    public function paginationView()
    {
        return 'livewire.paginacionBase';
    }
}