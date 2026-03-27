@if ($verModalOcompra)
    <div class="modal-overlay">
        <div x-data="{ cabeceraVisible: true }" x-init="dragModal($el)" class="modal-dialog" 
            style="width: 98%; max-width: 1200px; height: 80vh; margin: 2vh auto;" wire:ignore.self>
            <div class="modal-content border-0 shadow-lg h-100">
                <div class="cardPrin" style="cursor: move; max-height: 85vh;">
                    <div class="cardPrin-header">
                        <h5 class="m-0">{{ $selected_id ? '#'.$selected_id : 'Nueva Orden' }}</h5>
                        <div class="badge bg-white shadow-sm">
                            <small class="text-muted d-block" style="font-size: 0.5rem;">TOTAL</small>
                            <span class="fw-bold text-success" style="font-size: 0.9rem;">
                                ${{ number_format($this->subtotal * $factorIva, 2) }}
                            </span>
                        </div>
                        <button class="bot botNegro" x-on:click="cabeceraVisible = !cabeceraVisible">
                            <span x-text="cabeceraVisible ? '🔼' : '🔽'"></span>
                        </button>                            
                        <button wire:click="cancel" type="button" class="btn-close"></button>
                    </div>
                    <div class="cardPrin-body p-0 flex-grow-1 overflow-auto" style="background-color: #fcfcfc;">
                        <div x-show="cabeceraVisible" x-transition class="bg-light border-bottom p-3">
                        <div class="row g-1">
                            @include('livewire.ocompras.modals.ocompra')
                        </div>
                        </div>
                        <div class="row g-1">
                            @include('livewire.ocompras.modals.bodyMateriales')
                        </div>
                    </div>
                    <div class="cardPrin-footer">
                        <button wire:click="cancel" type="button" class="bot botNegro">Cerrar</button>
                        <button type="button" class="bot botVerde" wire:click="save" wire:loading.attr="disabled">
                            <span wire:loading.remove wire:target="save">Guardar</span>
                            <span wire:loading wire:target="save"><span class="spinner-border spinner-border-sm me-2"></span>...</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif