<div class="cardSec shadow-sm">
    <div class="cardSec-header p-2">
        <div class="row g-2 align-items-center">
            <div class="col-12 col-sm-4">
                <div class="position-relative">
                    <select wire:model="IdFactura" wire:change="$refresh" class="inpChico w-100">
                        <option value="">-- Factura --</option>
                        @foreach ($facturas as $key => $value)
                            <option value="{{ $key }}">{{ $value }}</option>
                        @endforeach
                    </select>
                    @error('IdFactura') <span class="text-danger small d-block">{{ $message }}</span> @enderror
                </div>
            </div>
            <div class="col-8 col-sm-5">
                <div class="position-relative">
                    <input wire:model.lazy="keyWord" class="inpChico w-100 pe-4" 
                        wire:keydown.escape="$set('keyWord','')"
                        onfocus="this.select()" placeholder="Buscar Orden/Lote/Estilo">
                    @if($keyWord)
                        <span wire:click="$set('keyWord','')" class="bot botNegro botChico" 
                            style="position: absolute; right: 5px; top: 50%; transform: translateY(-50%); cursor: pointer;">X</span>
                    @endif
                </div>
            </div>
            <div class="col-4 col-sm-3 d-flex justify-content-end gap-1">
                <button type="button" class="bot botAzul botChico" wire:click="imprimirFolios" 
                    wire:loading.attr="disabled" wire:target="imprimirFolios" title="Imprimir Folios">
                    <span wire:loading.remove wire:target="imprimirFolios"><i class="bi bi-printer"></i></span>
                    <span wire:loading wire:target="imprimirFolios">⏳</span>
                </button>
                <button wire:click="nuevaOrden" class="bot botBlanco botChico" title="Nueva Orden">➕</button>
            </div>
        </div>
    </div>
    @include('livewire.arbolfolios.modals')
    <div class="cardSec-body" style="min-height: 30vh; max-height: 75vh; overflow-y: auto; overflow-x: hidden;">
        <div class="d-flex justify-content-end mb-2">
            {{ $arbol->links() }}
        </div>
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