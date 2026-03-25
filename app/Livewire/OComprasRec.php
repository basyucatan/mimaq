<?php
namespace App\Livewire;
class OComprasRec extends OcomprasBase
{
    public function render()
    {
        return $this->aplicarRender(['ordenado', 'recibido', 'cancelado'], 'livewire.ocompras.recepcion.view');
    }
}