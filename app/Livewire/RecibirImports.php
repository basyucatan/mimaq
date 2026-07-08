<?php
namespace App\Livewire;
use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\{Factura, Facimportsdet, Existencia, Referenciasmov};
use App\Traits\Utilfun;
use Illuminate\Support\Facades\DB;
class Recibirimports extends Component
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
    $movimientosAbiertos = Referenciasmov::where('IdDoc', $this->factura->id)
        ->where('tipoDoc', 'import')
        ->where('estatus', 'abierto')
        ->get();
    if ($movimientosAbiertos->isEmpty()) {
        $this->alerta('⚠️ No hay movimientos abiertos para confirmar', 'warning', 1500);
        return;
    }
    $idDeptoBoveda = DB::table('deptos')->where('depto', '0 BOVEDA')->value('id');
    if (!$idDeptoBoveda) {
        $this->alerta('❌ Error: El departamento "0 BOVEDA" no existe en el sistema', 'error', 3000);
        return;
    }
    foreach ($movimientosAbiertos as $movimiento) {
        $existencia = Existencia::where('IdFacImportsDet', $movimiento->IdFacImportsDet)
            ->where('IdDepto', $idDeptoBoveda)
            ->first();
        if ($existencia) {
            $existencia->update([
                'cantidad' => DB::raw("cantidad + $movimiento->cantidad"),
                'pesoG' => DB::raw("pesoG + $movimiento->pesoG")
            ]);
        } else {
            Existencia::create([
                'IdFacImportsDet' => $movimiento->IdFacImportsDet,
                'IdDepto' => $idDeptoBoveda,
                'cantidad' => $movimiento->cantidad,
                'pesoG' => $movimiento->pesoG
            ]);
        }
        $movimiento->update([
            'IdDeptoDes' => $idDeptoBoveda,
            'estatus' => 'cerrado',
            'glosa' => 'Ingreso a bóveda ' . now()->tz('America/Mexico_City')->format('d/M H:i')
        ]);
    }
    $this->factura->update(['estatus' => 'recibido']);
    $this->alerta("🔐 Inventario actualizado y movimientos cerrados", 'success', 2000);
    $this->dispatch('refreshRefsMovs');
}
public function recibirFactura()
{
    if (!$this->factura) return;
    if ($this->factura->estatus == 'abierto') {
        return $this->alerta('⚠️ Esta factura aún no se ha cerrado', 'warning', 1500);
    }
    $facturaConDetalles = Factura::with('facimportsdets')->find($this->factura->id);
    if (!$facturaConDetalles || $facturaConDetalles->facimportsdets->isEmpty()) {
        $this->alerta('⚠️ No hay registros que operar', 'warning', 1500);
        return;
    }
    $idsExistentes = Referenciasmov::where('IdDoc', $this->factura->id)
        ->where('tipoDoc', 'import')
        ->pluck('IdFacImportsDet')
        ->toArray();
    $detallesFaltantes = $facturaConDetalles->facimportsdets->whereNotIn('id', $idsExistentes);
    if ($detallesFaltantes->isEmpty()) {
        $this->alerta('ℹ️ Todo está al día', 'info', 1000);
        return;
    }
    foreach ($detallesFaltantes as $detalle) {
        Referenciasmov::create([
            'IdFacImportsDet' => $detalle->id,
            'IdMaterial' => $detalle->IdMaterial,
            'IdDeptoOri' => null,
            'IdDeptoDes' => null,
            'tipo' => 'entrada',
            'cantidad' => $detalle->cantidad,
            'pesoG' => $detalle->pesoG,
            'tipoDoc' => 'import',
            'IdDoc' => $this->factura->id,
            'glosa' => 'Carga inicial por importación',
            'estatus' => 'abierto'
        ]);
    }
    $this->alerta("✅ {$detallesFaltantes->count()} registros descargados", 'success', 1000);
    $this->dispatch('refreshRefsMovs');
}
    public function limpiar()
    {
        if (!$this->factura) return;
        if ($this->factura->estatus == 'recibido') {
            return $this->alerta('⚠️ Esta factura ya se ha recibido', 'warning', 1500);
        }
        DB::transaction(function () {
            $idsDetalles = Facimportsdet::where('IdFactura', $this->factura->id)->pluck('id');
            Existencia::whereIn('IdFacImportsDet', $idsDetalles)->delete();
            Referenciasmov::where('IdDoc', $this->factura->id)
                ->where('tipoDoc', 'import')
                ->delete();
        });
        $this->alerta('🗑️ Movimientos y existencias eliminados', 'success', 1000);
        $this->dispatch('refreshRefsMovs');
    }    
    public function render()
    {
        return view('livewire.recibirimports.view');
    }
}