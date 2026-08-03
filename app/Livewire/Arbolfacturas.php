<?php
namespace App\Livewire;
use Livewire\Component;
use App\Models\{Pedimento, Factura, Util, Facimportsdet, Facexportsdet};
use App\Traits\Utilfun;
class Arbolfacturas extends Component
{
    use Utilfun;
    public $keyWord = '', $verModalFactura = false, $verModalPedimento = false, 
        $verModalSecuencias = false, $cerrado = false;
    public $selected_id, $idFacturaElecta, $factura, $IdPedimento, $fecha, $pedimento, 
        $estatus, $nivelMinimo, $serie = 'A',
        $regimen = 'IN', $tipoCambio;
    public $expandir = ['Pedimento' => [], 'Factura' => []], $guias=[], 
        $secuencias = [], $adicionales=[];
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
            $this->idFacturaElecta = $id;
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
        $this->selected_id = null;
        $this->pedimento = null;
        $this->fecha = now()->tz('America/Mexico_City')->format('Y-m-d');
        $this->tipoCambio = Util::getParametro('tipoCambio', 18);
        if(auth()->user()?->hasRole('adminUSA')){
            $this->savePedimento();
        } else {
            $this->verModalPedimento = true;
        }
        }
    public function nuevaFactura($idPedimento = null)
    {
        $this->resetInput();
        $this->IdPedimento = $idPedimento;
        $this->selected_id = null;
        $this->serie = 'A';
        $pedimento = Pedimento::findOrFail($idPedimento);
        $this->factura = Factura::getConsecutivo($this->serie, $pedimento->regimen);
        $this->fecha = now()->tz('America/Mexico_City')->format('Y-m-d');
        $this->guias = [''];
        $this->verModalFactura = true;
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
        $this->fill($registro->toArray());
        $this->selected_id = $id;
        $this->guias = $registro->guias ?? [''];
        $this->cerrado = ($registro->estatus === 'cerrado');
        $this->verModalFactura = true;
    }
    public function savePedimento()
    {
        $this->validate([ 'fecha' => 'required']);
        Pedimento::updateOrCreate(
            ['id' => $this->selected_id],
            [
                'pedimento' => $this->pedimento,
                'regimen' => $this->regimen,
                'tipoCambio' => $this->tipoCambio,
                'fecha' => $this->fecha
            ]
        );
        $this->cancel();
    }
    public function agregarGuia()
    {
        $this->guias[] = '';
    }
    public function removerGuia($indice)
    {
        unset($this->guias[$indice]);
        $this->guias = array_values($this->guias);
        if (empty($this->guias)) {
            $this->guias[] = '';
        }
    }
    public function saveFactura()
    {
        $this->validate([
            'factura' => 'required',
            'IdPedimento' => 'required|exists:pedimentos,id',
            'fecha' => 'required',
            'guias.*' => 'required|string|distinct',
        ]);
        $existe = Factura::where('serie', $this->serie)
            ->where('factura', $this->factura)
            ->when($this->selected_id, function ($q) {
                $q->where('id', '!=', $this->selected_id);
            })
            ->exists();

        if ($existe) {
            $this->addError('factura', 'El número de factura se está duplicando.');
            return;
        }
        Factura::updateOrCreate(
            ['id' => $this->selected_id],
            [
                'serie' => $this->serie,
                'factura' => $this->factura,
                'IdPedimento' => $this->IdPedimento,
                'fecha' => $this->fecha,
                'estatus' => $this->cerrado ? 'cerrado' : 'abierto',
                'guias' => array_values(array_filter($this->guias)),
                'adicionales' => $this->adicionales
            ]
        );
        $this->dispatch('IdFacturaElecta',$this->selected_id);
        $this->cancel();
    }
    public function cancel()
    {
        $this->verModalFactura = false;
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
                    $this->alerta('⛔ This customs declaration has invoices', 'warning');
                    return;
                }
                $registro->delete();
            }
        } elseif ($tipo == 'Factura') {
            $registro = Factura::find($id);
            if ($registro) {
                if ($registro->estatus == 'cerrado') {
                    $this->alerta('⛔ This invocie is closed', 'warning');
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
    public function asignarSecuencias($idPedimento)
{
    $pedimento = Pedimento::with('Facturas')->findOrFail($idPedimento);
    $this->selected_id = $idPedimento;
    $regimen = $pedimento->regimen;

    if ($regimen === 'IN') {
        $detalles = Facimportsdet::whereHas('factura', function ($q) use ($idPedimento) {
            $q->where('IdPedimento', $idPedimento);
        })->get();
    } else {
        $detalles = Facexportsdet::whereHas('factura', function ($q) use ($idPedimento) {
            $q->where('IdPedimento', $idPedimento);
        })->get();
    }

    $grupos = $detalles->groupBy(function ($item) {
        return (string) ($item->arancel ?? 'Sin Fracción');
    });

    $this->secuencias = [];

    foreach ($grupos as $arancel => $items) {
        $primerRegistro = $items->first();
        $secuenciaExistente = data_get($primerRegistro->adicionales, 'secuencia', '');

        $this->secuencias[] = [
            'arancel' => $arancel,
            'secuencia' => $secuenciaExistente
        ];
    }

    $this->verModalSecuencias = true;
}
public function saveSecuencias()
{
    if (!$this->selected_id) return;
    $pedimento = Pedimento::findOrFail($this->selected_id);
    $regimen = $pedimento->regimen;
    foreach ($this->secuencias as $item) {
        $arancelBuscado = $item['arancel'];
        $secuenciaVal = $item['secuencia'];
        if ($regimen === 'IN') {
            $query = Facimportsdet::whereHas('factura', function ($q) {
                $q->where('IdPedimento', $this->selected_id);
            });
        } else {
            $query = Facexportsdet::whereHas('factura', function ($q) {
                $q->where('IdPedimento', $this->selected_id);
            });
        }
        $detalles = $query->where(function ($q) use ($arancelBuscado) {
            if ($arancelBuscado === 'Sin Fracción') {
                $q->whereNull('arancel')->orWhere('arancel', '');
            } else {
                $q->where('arancel', $arancelBuscado);
            }
        })->get();
        foreach ($detalles as $det) {
            $adicionales = $det->adicionales ?? [];
            $adicionales['secuencia'] = $secuenciaVal;

            $det->update([
                'adicionales' => $adicionales
            ]);
        }
    }

    $this->verModalSecuencias = false;

    if ($this->idFacturaElecta) {
        $this->dispatch('IdFacturaElecta', $this->idFacturaElecta);
    }

    $this->alerta('Secuencias asignadas correctamente', 'success');
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