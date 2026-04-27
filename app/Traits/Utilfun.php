<?php
namespace App\Traits;
use App\Helpers\SweetAlert;
trait Utilfun
{
    public function alerta($msj, $tipo = 'success', $duracion = 1500)
    {
        $this->dispatch('sweetalert', SweetAlert::mensaje($msj, $tipo, $duracion));
    }
    public function limpiarMsg()
    {
        $this->msg = null;
    }
}