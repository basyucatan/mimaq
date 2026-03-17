<?php
namespace App\Policies;
use App\Models\User;
use App\Models\Ocompra;

class OcompraPolicy
{
    public function validarAprobacion(User $usuario, Ocompra $compra)
    {
        if ($compra->estatus !== Ocompra::EST_EDICION) {
            return false;
        }
        $eSuperAdmin = $usuario->hasAnyRole(['SuperAdmin','Director']);
        $esAdmin = $usuario->hasRole('Admin');
        if ($compra->total > 5000) {
            return $eSuperAdmin;
        }
        return $eSuperAdmin || $esAdmin;
    }

    public function gestionar(User $usuario, Ocompra $compra)
    {
        if ($compra->estatus === Ocompra::EST_CANCELADO) {
            return false;
        }
        $esDueno = $usuario->id === $compra->IdUser;
        $nivelUsuario = $usuario->roles->first()->nivel ?? 999;
        $nivelCreador = $compra->Solicito->roles->first()->nivel ?? 999;
        $esSuperior = $nivelUsuario < $nivelCreador;
        return $esDueno || $esSuperior;
    }

    public function cambiarEstatus(User $usuario, Ocompra $compra, $nuevo)
    {
        if (!$this->gestionar($usuario, $compra)) {return false;}
        return in_array($nuevo, $compra->puedePasarA($nuevo));
    }
}