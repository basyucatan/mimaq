<?php

namespace App\Traits;

use App\Models\Traspaso;
use App\Models\MovInventario;

use App\Models\Util;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

trait CrudTraspasos
{
    public $verModalTraspaso = false, $verModalPresu = false, $verModalCortes = false,
        $queVer='Abierto', $orden = 'asc', $selected_id;
    public $tipo, $IdUserOri, $IdUserDes, $IdDeptoOri, $IdDeptoDes,
        $IdPresupuesto, $IdMarca, $msg, $avance,
        $fecha, $estatus='Abierto', $adicionales, $obs;
    public $users = [], $deptos = [], $presupuestos = [],  $marcas = [], $optim = [];
    public $EditIdDeptoOri = false, $EditIdDeptoDes = false, 
        $EditIdUserOri = false, $EditIdUserDes = false;
        
    public function getEstatusFiltro(string $queVer)
    {
        $this->queVer = $queVer;
        $this->orden = $queVer === 'Cerrado' ? 'desc' : 'asc';
        $this->selected_id =  null;
        $this->dispatch('cambioEstatus', null, 'Abierto');
        $this->resetPage();
    }

    public function mount(){
        $this->cargarArrays();
    }

    public function create($tipo)
    {
        $this->resetInput();
        $this->cargarArrays();
        $this->selected_id = null;
        $this->marcas = null; //se cargará cuando se generan varias compras
        $this->IdUserOri = null;
        $this->IdUserDes = null;
        $this->IdDeptoOri = null;
        $this->IdDeptoDes = null;
        $this->tipo = $tipo;
        if ($this->tipo == 'InvFisico') {
            $this->IdUserDes = Auth::id();
            $this->EditIdDeptoDes = true;
            $this->verModalTraspaso = true;
        }
        if ($this->tipo == 'Compras' || $this->tipo == 'Compra') {
            $this->IdDeptoOri = null;
            $this->IdDeptoDes = 2;
            $this->IdUserOri = Auth::id();
            $this->verModalPresu = true;	
        }       
        if ($this->tipo == 'Cortes' || $this->tipo == 'Compra') {
            $this->IdDeptoOri = null;
            $this->IdDeptoDes = 2;
            $this->IdUserOri = Auth::id();
            $this->verModalPresu = true;	
        }                 
    }

    public function save()
    {
        $this->fecha   = now('America/Mexico_City');
        $this->estatus = 'Abierto';
        $this->validate([
            'tipo'           => 'required',
            'fecha'          => 'required|date',
            'estatus'        => 'required',
            'IdUserOri'      => 'required_without:IdUserDes|nullable',
            'IdUserDes'      => 'required_without:IdUserOri|nullable',
            'IdDeptoOri'     => 'required_without:IdDeptoDes|nullable',
            'IdDeptoDes'     => 'required_without:IdDeptoOri|nullable',
            'IdPresupuesto'  => 'required_if:tipo,Compra',
        ]);
        $adicionales = [];
        if ($this->selected_id) {
            $adicionales = Traspaso::find($this->selected_id)?->adicionales ?? [];
        } else {
            $adicionales = [
                'IdPresupuesto' => (int) $this->IdPresupuesto,
                'presupuesto'   => (string) ($this->presupuestos[$this->IdPresupuesto] ?? ''),
                'IdMarca'       => (int) $this->IdMarca,
                'marca'         => $this->marcas[$this->IdMarca]['marca'] ?? '',
                'tipoCEuro'     => \App\Models\Moneda::find(2)?->tipoCambio,
                'tipoCDolar'    => \App\Models\Moneda::find(3)?->tipoCambio,
            ];
        }
        $adicionales['obs'] = (string) $this->obs;
        try {
            $data = [
                'tipo'        => $this->tipo === 'Compras' ? 'Compra' : $this->tipo,
                'IdUserOri'   => $this->IdUserOri,
                'IdUserDes'   => $this->IdUserDes,
                'IdDeptoOri'  => $this->IdDeptoOri,
                'IdDeptoDes'  => $this->IdDeptoDes,
                'estatus'     => $this->estatus,
                'adicionales' => $adicionales,
            ];
            if (!$this->selected_id) {
                $data['fecha'] = $this->fecha ?? now()->tz('America/Mexico_City')->toDateString();
            }
            $traspaso = Traspaso::updateOrCreate(
                ['id' => $this->selected_id],
                $data
            );
            $this->verModalTraspaso = false;
            if ($this->tipo === 'Compra') {
                $this->verModalPresu = false;
            }
            return $traspaso;
        } catch (\Throwable $e) {
            $this->dispatch('sweetalert', \App\Helpers\SweetAlert::mensaje(
                $e->getMessage(),
                1500,
                'error'
            ));
        }
    }

    public function edit($id)
    {
        $record = $this->elegir($id);
        $this->fill($record->toArray());
        $this->obs = $record->adicionales['obs'] ?? null;
        $this->verModalTraspaso = true;
    }

    public function elegir($id)
    {
        if (!$id) return null;
        $traspaso = Traspaso::with('traspasosdets')->findOrFail($id);
        $this->selected_id = $traspaso->id;
        $this->estatus = $traspaso->estatus;
        $this->IdPresupuesto = $traspaso->adicionales['IdPresupuesto'] ?? null;
        $this->dispatch('cambioEstatus', $this->selected_id, $traspaso->estatus);
        return $traspaso;
    }
    
    public function cerrar($id)
    {
        $traspaso = $this->elegir($id);
        if (!$traspaso->traspasosdets->count()) {
            $this->dispatch('sweetalert', \App\Helpers\SweetAlert::mensaje(
                'No hay movimientos a operar',
                1500,
                'warning'
            ));
            return;
        }
        $traspaso->estatus = 'Cerrado';
        $traspaso->IdUserDes = Auth::id();
        $traspaso->save();
        foreach ($traspaso->traspasosdets as $det) {
            $adicionales = [];
            if ($traspaso->tipo == 'Compra') {
                $adicionales['IdPresupuesto'] = $traspaso->adicionales['IdPresupuesto'] ?? 0;
            }
            MovInventario::create([
                'IdUserOri' => $traspaso->IdUserOri,
                'IdUserDes' => $traspaso->IdUserDes,
                'tipo' => $traspaso->tipo,
                'IdMatCosto' => $det->IdMatCosto,
                'IdDeptoOri' => $traspaso->IdDeptoOri,
                'IdDeptoDes' => $traspaso->IdDeptoDes,
                'fechaH' => now()->tz('America/Mexico_City'),
                'cantidad' => $det->cantidad,
                'valorU' => $det->valorU ?? 0,
                'dimensiones' => $det->dimensiones ?? null,
                'adicionales' => $adicionales,
            ]);
        }
        $this->dispatch('cambioEstatus', $traspaso->id, 'Cerrado');
    }

    public function devolver($id)
    {
        $traspaso = Traspaso::with('traspasosdets')->find($id);
        if (!$traspaso || $traspaso->traspasosdets->isEmpty()) {
            $this->dispatch('sweetalert', \App\Helpers\SweetAlert::mensaje(
                'No hay movimientos que devolver',
                1500,
                'warning'
            ));
            return;
        }
        $traspaso->estatus = 'Abierto';
        $traspaso->save();
        foreach ($traspaso->traspasosdets as $det) {
            MovInventario::create([
                'IdUserOri'   => $traspaso->IdUserDes,
                'IdUserDes'   => $traspaso->IdUserOri,
                'IdDeptoOri'  => $traspaso->IdDeptoDes,
                'IdDeptoDes'  => $traspaso->IdDeptoOri,
                'tipo'        => 'Devolucion',
                'fechaH'      => now()->tz('America/Mexico_City'),
                'IdMatCosto'  => $det->IdMatCosto,
                'cantidad'    => $det->cantidad,
                'valorU'      => $det->valorU ?? 0,
                'dimensiones' => $det->dimensiones ?? null,
                'adicionales' => $det->adicionales ?? null,
            ]);
        }
        $this->dispatch('sweetalert', \App\Helpers\SweetAlert::mensaje(
            'Movimientos revertidos',
            1000,
            'success'
        ));
        $this->dispatch('cambioEstatus', $traspaso->id, 'Abierto');
    }

    private function resetInput()
    {
        $this->resetExcept('selected_id','users', 'deptos', 'presupuestos');
    }

    public function cargarArrays()
    {
        $this->users  = User::pluck('name', 'id')->toArray();
        $this->deptos = Util::getArray('deptos');
        $this->presupuestos = DB::table('presupuestos')
            ->join('empresas', 'presupuestos.IdCliente', '=', 'empresas.id')
            ->where('presupuestos.estatus', 'aprobado')
            ->select('presupuestos.id', 'presupuestos.fecha', 'empresas.empresa')
            ->orderBy('presupuestos.fecha', 'desc') 
            ->get()
            ->mapWithKeys(function ($item) {
                return [
                    $item->id => '#'.$item->id . ' |' . Util::formatFecha($item->fecha,'D/MMM') . '|' . $item->nombre
                ];
            });
    }

    public function cancel()
    {
        $this->resetInput();
        $this->verModalTraspaso = false;
    }

    public function destroy($id)
    {
        if (!$id) return;
        Traspaso::where('id', $id)->delete();
        $this->dispatch('sweetalert', \App\Helpers\SweetAlert::mensaje(
            'Traspaso eliminado',
            1000,
            'success'
        ));        
    }
}
