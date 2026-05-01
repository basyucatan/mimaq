<div class="cardSec shadow-sm mt-2">
    <div class="cardSec-header d-flex justify-content-between align-items-center p-2 bg-secondary text-white">
        <div class="flex-grow-1 me-2 position-relative">
            <input wire:model.live="keyWord" class="inpSolo" 
                wire:keydown.escape="$set('keyWord','')"
                onfocus="this.select()" placeholder="Buscar en Bóveda...">
            @if($keyWord)
                <span wire:click="$set('keyWord','')" class="bot botNegro botChico" 
                style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); cursor: pointer;">X</span>
            @endif
        </div>
        <div class="d-flex gap-1">
            <a class="bot botBlanco botChico" title="Replegar todo" wire:click="replegarTodo">📁</a>
        </div>
    </div>
    <div class="cardSec-body" style="min-height: 20vh; max-height: 40vh; overflow-y: auto; overflow-x: hidden; background-color: #f8f9fa;">
        <ul class="list-unstyled mb-0">
            @foreach($arbol as $m)
                @include('livewire.arbolboveda.nodo', [
                    'tipo' => 'Material',
                    'nodo' => $m,
                    'texto' => $m->material,
                    'expanded' => $expandir['Material'][$m->id] ?? false,
                    'hijos' => $m->facimportsdets,
                    'icono' => '💎'
                ])
            @endforeach
        </ul>
    </div>
</div>