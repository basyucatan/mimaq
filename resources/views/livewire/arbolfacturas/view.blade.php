<div class="cardSec shadow-sm">
    <div class="cardSec-header d-flex justify-content-between align-items-center p-2">
        <div class="flex-grow-1 me-2 position-relative">
            <input wire:model.lazy="keyWord" class="inpSolo w-100" onfocus="this.select()" placeholder="Buscar...">
            @if($keyWord)
                <span wire:click="limpiarBusqueda" class="bot botNaranja botChico" 
                style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); 
                cursor: pointer;">X</span>
            @endif
        </div>
        <div class="d-flex gap-1">
            <a class="bot botBlanco botChico" title="Replegar todo" wire:click="replegarTodo">📁</a>
            <button wire:click="nuevoPedimento" class="bot botBlanco botChico" title="Nuevo Pedimento">➕</button>
        </div>
    </div>
    @include('livewire.arbolfacturas.modals')
    <div class="cardSec-body" style="max-height: 75vh; overflow-y: auto; overflow-x: hidden;">
        <ul class="list-unstyled mb-0">
            @foreach($arbol as $p)
                @include('livewire.arbolfacturas.nodo', [
                    'tipo' => 'Pedimento',
                    'nodo' => $p,
                    'texto' => $p->pedimento,
                    'expanded' => $expandir['Pedimento'][$p->id] ?? false,
                    'hijos' => $p->Facturas
                ])
            @endforeach
        </ul>
    </div>
</div>