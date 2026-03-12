<?php
namespace App\Policies;
use App\Models\User;
use App\Models\Ocompra;
class OcompraPolicy
{
    public function validarAprobacion(User $usuario, Ocompra $compra)
    {
        $eSuperAdmin = $usuario->hasAnyRole(['SuperAdmin', 'Director']);
        $esAdmin = $usuario->hasRole('Admin');
        if ($compra->subtotal > 5000) {
            return $eSuperAdmin;
        }
        return $eSuperAdmin || $esAdmin;
    }
    public function gestionar(User $usuario, Ocompra $compra)
    {
        if ($compra->estatus === 'aprobado') {
            return false;
        }
        $esDueno = $usuario->id === $compra->IdUser;
        $nivelUsuario = $usuario->roles->first()->nivel;
        $nivelCreador = $compra->Solicito->roles->first()->nivel ?? 7;
        $esSuperior = $nivelUsuario < $nivelCreador;
        return $esDueno || $esSuperior;
    }
}