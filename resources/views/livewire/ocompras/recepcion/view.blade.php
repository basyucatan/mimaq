@section('title', __('Recepción OC'))
<div class="cardPrin">
    <div class="cardPrin-header">
        <div>Recepción (OC)</div>
        <div class="d-flex gap-2">
            <input wire:model.live="keyWord" type="text" class="inpSolo" placeholder="Buscar">
            <button class="bot botVerde" wire:click="create">Nueva</button>
        </div>
    </div>
    @include('livewire.ocompras.bodyView')
</div>