<?php

namespace App\Livewire;
use Livewire\Component;
use Livewire\Attributes\On;
class Facexports extends Component
{
    public $selected_id;
    #[On('IdFacturaElecta')]
    public function elegirFactura($id)
    {
        $this->selected_id = $id;
    }
    public function render()
    {
        return view('livewire.facexports.view');
    }
}