<div class="cardSec shadow-sm">
    <div class="cardSec-header d-flex justify-content-between align-items-center p-2">
        <div class="flex-grow-1 me-2 position-relative">
            <input wire:model.live="keyWord" class="inpSolo" 
                wire:keydown.escape="$set('keyWord','')"
                onfocus="this.select()" placeholder="Buscar Orden/Lote/Estilo">
            @if($keyWord)
                <span wire:click="$set('keyWord','')" class="bot botNegro botChico" 
                style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); cursor: pointer;">X</span>
            @endif
        </div>
        <div class="d-flex gap-1">
            <a class="bot botBlanco botChico" title="Replegar todo" wire:click="replegarTodo">📁</a>
            <button wire:click="nuevaOrden" class="bot botBlanco botChico" title="Nueva Orden">➕</button>
        </div>
    </div>
    @include('livewire.arbolfolios.modals')
    <div class="cardSec-body" style="min-height: 30vh; max-height: 35vh; overflow-y: auto; overflow-x: hidden;">
        <ul class="list-unstyled mb-0">
            @foreach($arbol as $o)
                @include('livewire.arbolfolios.nodo', [
                    'tipo' => 'Orden',
                    'nodo' => $o,
                    'texto' => $o->orden,
                    'expanded' => $expandir['Orden'][$o->id] ?? false,
                    'hijos' => $o->lotes,
                    'icono' => '📦',
                    'selected_id' => $selected_id
                ])
            @endforeach
        </ul>
    </div>
</div>