<?php
namespace App\Livewire;
use Livewire\Component;
use App\Models\{Util, Facimportsdet, Cliente, Orden, Lote, Folio, Estilo};
use App\Traits\Utilfun;
class Arbolfolios extends Component
{
    use Utilfun;
    public $keyWord = '', $tipoModal = null;
    public $selected_id, $IdOrden, $IdCliente, $cliente, $IdLote, $orden, $lote, $IdEstilo,
        $cantidad, $totalBandejas, $jobStyle, $abreviatura, $productoFinal, 
        $kt = '', $color = '',
        $fechaVen, $estatus = 'abierto';
    public $expandir = ['Orden' => [], 'Lote' => []], $estilos = [], $clientes = [], $adicionales = [],
        $kts = [], $colors = [];
    protected $listeners = ['estilosDetsActualizado' => 'generarDef'];
    public function mount(){
        $this->estilos = Util::getArray('estilos');
        $this->clientes = Util::getArray('clientes');
        $config = json_decode(file_get_contents(base_path('settings.json')), true);
        $this->kts = collect($config['kts'] ?? [])->pluck('valor')->toArray();
        $this->colors = collect($config['colors'] ?? [])->pluck('valor')->toArray();
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
        $registro = Lote::findOrFail($id);
        $this->selected_id = $id;
        $this->lote = $registro->lote;
        $this->IdOrden = $registro->IdOrden;
        $this->tipoModal = 'Lote';
    }
    public function nuevoFolio($idLote)
    {
        $this->resetInput();
        $this->IdLote = $idLote;
        $this->cantidad = 1;
        $this->totalBandejas = 1;
        $this->fechaVen = now()->tz('America/Mexico_City')->addDays(7)->format('Y-m-d');
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
            $this->IdCliente = array_search(trim($this->cliente), $this->clientes) ?: null;
            $this->validate([
                'orden' => 'required',
                'fechaVen' => 'required|date',
                'IdCliente' => 'required'
            ], [
                'IdCliente.required' => 'El cliente escrito no es válido. Por favor, selecciona uno de la lista.'
            ]);
            Orden::updateOrCreate(['id' => $this->selected_id], [
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
        $this->resetExcept(['selected_id', 'expandir', 'keyWord', 'estilos','clientes','kts','colors']);
        $this->selected_id = null;
    }
    public function render()
    {
        $consulta = Orden::query()->orderBy('id', 'desc');

        if (!empty($this->keyWord)) {
            $keyWord = '%' . $this->keyWord . '%';

            $consulta->where(function($q) use ($keyWord) {
                $q->where('orden', 'like', $keyWord)
                    ->orWhereHas('cliente', function($qC) use ($keyWord) {
                        $qC->where('cliente', 'like', $keyWord);
                    })
                    ->orWhereHas('lotes', function($qL) use ($keyWord) {
                        $qL->where('lote', 'like', $keyWord)
                            ->orWhereHas('folios', function($qF) use ($keyWord) {
                                $qF->where('id', 'like', $keyWord)
                                    ->orWhereHas('Estilo', function($qE) use ($keyWord) {
                                        $qE->where('estilo', 'like', $keyWord);
                                    });
                            });
                    });
            });

            $arbol = $consulta->with(['lotes.folios.Estilo'])->get();
            foreach ($arbol as $o) {
                $this->expandir['Orden'][$o->id] = true;
                foreach ($o->lotes as $l) {
                    $this->expandir['Lote'][$l->id] = true;
                }
            }
        } else {
            $arbol = $consulta->with('lotes.folios.Estilo')->get();
        }
        return view('livewire.arbolfolios.view', ['arbol' => $arbol]);
    }
}