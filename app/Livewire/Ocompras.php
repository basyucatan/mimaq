<?php
namespace App\Livewire;
use App\Livewire\OcomprasBase;

class Ocompras extends OcomprasBase
{
    public function render()
    {
        return $this->aplicarRender(['edicion', 'aprobado'], 'livewire.ocompras.view');
    }
}
