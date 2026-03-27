@if ($verDetalles)
<div class="modal-overlay">
    <div x-data="{}" x-init="dragModal($el)" class="modal-dialog" style="width: 98%; max-width: 1200px;" wire:ignore.self>
        <div class="modal-content">
            <div class="cardPrin" style="cursor: move;">
                <div class="cardPrin-header d-flex justify-content-between align-items-center">
                    <h5 class="m-0"># {{ $this->oCompra->id ?? ''}}</h5>
                    <h5>Materiales OC</h5>
                    <div class="d-flex align-items-center gap-3">
                        <div class="badge bg-white text-dark border px-2 py-1 shadow-sm">
                            <small class="text-muted d-block d-md-none" style="font-size: 0.5rem;">TOTAL</small>
                            <span class="fw-bold text-success" style="font-size: 0.9rem;">
                                ${{ number_format($this->subtotal * $factorIva, 2) }}
                            </span>
                        </div>
                        <button type="button" class="btn-close" wire:click="cerrarDetalles"></button>
                    </div>
                </div>            
                @include('livewire.ocompras.modals.bodyMateriales')
                <div class="cardPrin-footer">
                    <button type="button" class="bot botNegro" wire:click="cerrarDetalles">Cerrar</button>
                    <button type="button" class="bot botVerde" wire:click="guardarDetalles" wire:loading.attr="disabled">
                        <span wire:loading.remove wire:target="guardarDetalles">Guardar</span>
                        <span wire:loading wire:target="guardarDetalles"><span class="spinner-border spinner-border-sm me-2"></span>...</span>
                    </button>
                </div>                
        </div>
    </div>
</div>
@endif

