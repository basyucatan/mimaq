<?php
namespace App\Livewire;
use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\{Factura, Facimportsdet, Referenciasmov};
use App\Traits\Utilfun;

class RecibirImports extends Component
{
    use Utilfun;
    public $selected_id, $factura, $difs;
    public $edicionFisica = [];

    #[On('IdFacturaElecta')]
    public function elegirFactura($id)
    {
        $this->selected_id = $id;
        $this->factura = Factura::find($id);
    }

    public function confirmarIngreso()
    {
        if (!$this->factura) return;

        $movimientos = Referenciasmov::where('IdDoc', $this->factura->id)
            ->where('tipoDoc', 'import')
            ->where('estatus', 'import')
            ->get();

        foreach ($movimientos as $mov) {
            $referencia = $mov->Referencia;
            $diferencias = [];
            if ($mov->pesoG != $referencia->pesoG) {
                $diferencias['pesoG'] = $mov->pesoG - $referencia->pesoG;
            }
            if ($mov->cantidad != $referencia->cantidad) {
                $diferencias['cantidad'] = $mov->cantidad - $referencia->cantidad;
            }
            $mov->update([
                'estatus' => 'boveda',
                'diferencias' => !empty($diferencias) ? $diferencias : null
            ]);
        }
        $this->alerta('✅ Ingreso a Bóveda confirmado', 'success');
        $this->dispatch('refreshRefsMovs');
    } 
    public function recibirFactura()
    {
        if (!$this->factura) return;
        $detalles = Facimportsdet::where('IdFactura', $this->factura->id)->get();
        if ($detalles->isEmpty()) {
            return $this->alerta('⚠️ No hay registros que operar', 'warning', 1500);
        }
        $idsExistentes = Referenciasmov::where('IdDoc', $this->factura->id)
            ->where('tipoDoc', 'import')
            ->pluck('IdFacImportsDet')
            ->toArray();
        $detallesFaltantes = $detalles->whereNotIn('id', $idsExistentes);
        if ($detallesFaltantes->isEmpty()) {
            return $this->alerta('ℹ️ Todo está al día', 'info', 1000);
        }
        foreach ($detallesFaltantes as $detalle) {
            Referenciasmov::create([
                'IdFacImportsDet' => $detalle->id,
                'IdDoc' => $this->factura->id,
                'tipoDoc' => 'import',
                'estatus' => 'import',
                'cantidad' => $detalle->cantidad,
                'pesoG' => $detalle->pesoG,
            ]);
        }
        $conteo = $detallesFaltantes->count();
        $this->alerta("✅ {$conteo} filas nuevas añadidas", 'success', 1000);
        $this->dispatch('refreshRefsMovs');
    }
    public function limpiar()
    {
        if (!$this->factura) return;
        $movimientos = Referenciasmov::where('IdDoc', $this->factura->id)
            ->where('tipoDoc', 'import');
        if ($movimientos->exists()) {
            $movimientos->delete();
            $this->alerta('🗑️ Registros eliminados', 'success', 1000);
        } else {
            $this->alerta('⚠️ No hay registros para limpiar', 'warning', 1000);
        }
        $this->dispatch('refreshRefsMovs');
    }    
    public function render()
    {
        return view('livewire.recibirimports.view');
    }
}