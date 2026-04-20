<?php
namespace App\Livewire;
use Livewire\Component;
use App\Models\Pedimento;
use App\Models\Factura;
class Arbolfacturas extends Component
{
    public $keyWord = '', $verModalFacimport = false, $verModalPedimento = false;
    public $selected_id, $factura, $IdPedimento, $fecha, $pedimento, 
        $viadE, $guiaA, $nPaq,
        $regimen = 'IN', $tipoCambio;
    public $expandir = ['Pedimento' => [], 'Factura' => []], $adicionales=[];

    public function alternarNodo($tipo, $id)
    {
        $this->expandir[$tipo][$id] = !($this->expandir[$tipo][$id] ?? false);
    }
    public function replegarTodo()
    {
        $this->keyWord = '';
        $this->expandir = ['Pedimento' => [], 'Factura' => []];
    }
    public function limpiarBusqueda()
    {
        $this->replegarTodo();
    }
    public function elegir($tipo, $id)
    {
        if ($tipo == 'Factura') {
            $this->selected_id = $id;
            $this->dispatch('IdFacturaElecta', $id);
        }
    }
    public function agregar($tipo, $id)
    {
        if ($tipo == 'Pedimento') {
            $this->editarPedimento($id);
        } elseif ($tipo == 'Factura') {
            $this->editarFactura($id);
        }
    }
    public function nuevoPedimento()
    {
        $this->resetInput();
        $this->pedimento = (Pedimento::max('id') ?? 0) + 1;
        $this->fecha = now()->tz('America/Mexico_City')->format('Y-m-d');
        $this->verModalPedimento = true;
    }
    public function nuevaFactura($idPedimento = null)
    {
        $this->resetInput();
        $this->IdPedimento = $idPedimento;
        $this->viadE='FEDEX';
        $this->guiaA='';
        $this->nPaq = 1;        
        $this->verModalFacimport = true;
    }
    public function editarPedimento($id)
    {
        $registro = Pedimento::findOrFail($id);
        $this->selected_id = $id;
        $this->pedimento = $registro->pedimento;
        $this->regimen = $registro->regimen;
        $this->fecha = $registro->fecha;
        $this->tipoCambio = $registro->tipoCambio;
        $this->verModalPedimento = true;
    }
    public function editarFactura($id)
    {
        $registro = Factura::findOrFail($id);
        $this->selected_id = $id;
        $this->factura = $registro->factura;
        $this->IdPedimento = $registro->IdPedimento;
        $this->fecha = $registro->fecha;
        $this->viadE = $registro->adicionales['viadE'] ?? null;
        $this->guiaA = $registro->adicionales['guiaA'] ?? null;
        $this->nPaq = $registro->adicionales['nPaq'] ?? null;
        $this->verModalFacimport = true;
    }
    public function savePedimento()
    {
        $this->validate(['pedimento' => 'required', 'fecha' => 'required']);
        Pedimento::updateOrCreate(
            ['id' => $this->selected_id],
            [
                'pedimento' => $this->pedimento,
                'regimen' => $this->regimen,
                'fecha' => $this->fecha
            ]
        );
        $this->cancel();
    }
    public function saveFactura()
    {
        $this->validate([
            'factura' => 'required',
            'IdPedimento' => 'required|exists:pedimentos,id',
            'fecha' => 'required',
            'tipoCambio' => 'required|numeric',
        ]);
        $factura = $this->selected_id ? Factura::find($this->selected_id) : null;
        $adActual = $factura?->adicionales ?? [];
        $this->adicionales = array_merge($adActual, [
            'viadE' => $this->viadE,
            'guiaA' => $this->guiaA,
            'nPaq' => $this->nPaq,
        ]);
        Factura::updateOrCreate(
            ['id' => $this->selected_id],
            ['factura' => $this->factura, 
            'IdPedimento' => $this->IdPedimento, 
            'fecha' => $this->fecha, 
            'adicionales' => $this->adicionales, 
            'tipoCambio' => $this->tipoCambio]
        );
        $this->cancel();
    }
    public function cancel()
    {
        $this->verModalFacimport = false;
        $this->verModalPedimento = false;
        $this->resetInput();
    }
    private function resetInput()
    {
        $this->resetexcept(['selected_id', 'regimen', 'expandir']);
    }
    public function destroy($tipo, $id)
    {
        if ($tipo == 'Pedimento') {
            $registro = Pedimento::withCount('Facturas')->find($id);
            if ($registro) {
                if ($registro->facturas_count > 0) {
                    return;
                }
                $registro->delete();
            }
        } elseif ($tipo == 'Factura') {
            $registro = Factura::find($id);
            if ($registro) {
                if ($registro->estatus == 'cerrado') {
                    return;
                }
                $registro->delete();
                if ($this->selected_id == $id) {
                    $this->selected_id = null;
                    $this->dispatch('IdFacturaElecta', id: null);
                }
            }
        }
    }  
    public function render()
    {
        $consulta = Pedimento::query()
            ->where('regimen', $this->regimen)
            ->orderBy('fecha','desc');
        if (!empty($this->keyWord)) {
            $keyWord = '%' . $this->keyWord . '%';
            $consulta->where(function($q) use ($keyWord) {
                $q->where('pedimento', 'like', $keyWord)
                    ->orWhereHas('Facturas', fn($qF) => $qF->where('factura', 'like', $keyWord));
            });
            $arbol = $consulta->with(['Facturas' => fn($qF) => $qF->where('factura', 'like', $keyWord)])->get();
            foreach ($arbol as $p) {
                $this->expandir['Pedimento'][$p->id] = true;
                $facturaCoincidente = $p->Facturas->where('factura', $this->keyWord)->first();
                if ($facturaCoincidente) {
                    $this->elegir('Factura', $facturaCoincidente->id);
                }
            }
        } else {
            $arbol = $consulta->with('Facturas')->get();
        }
        return view('livewire.arbolfacturas.view', ['arbol' => $arbol]);
    }
}