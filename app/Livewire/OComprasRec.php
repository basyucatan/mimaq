<?php
namespace App\Livewire;
class Ocomprasrec extends OcomprasBase
{
    public function render()
    {
        return $this->aplicarRender(['ordenado', 'recibido', 'cancelado'], 'livewire.ocompras.recepcion.view');
    }
}