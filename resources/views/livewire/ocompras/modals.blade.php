@if ($verModalOcompra)
    <div class="modal-overlay">
        <div x-data="{}" x-init="dragModal($el)" class="modal-dialog" style="width: 98%;" wire:ignore.self>
            <div class="modal-content">
                <div class="cardPrin" style="cursor: move;">
                    <div class="cardPrin-header d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center gap-3">
                            <h5 class="m-1">{{ $selected_id ? 'Editar #'.$selected_id : 'Crear Orden' }}</h5>
                            <div class="badge bg-white text-dark border px-3 py-1 shadow-sm">
                                <small class="text-muted d-block text-start" style="font-size: 0.6rem; line-height: 1;">
                                    TOTAL (MXN)
                                </small>
                                <span class="h5 m-0 fw-bold text-success">
                                    ${{ number_format($this->total, 2) }}
                                </span>
                            </div>
                        </div>
                        <button wire:click="cancel" type="button" class="btn-close" aria-label="Cerrar"></button>
                    </div>
                    <div class="cardPrin-body" style="padding: 0 20px; max-height: 450px; overflow-y: auto;">
                        @include('livewire.ocompras.modalOCompra')
                        @include('livewire.ocompras.modalElegirMats')
                        @include('livewire.ocompras.modalDetalles')                       
                    </div>
                    <div class="cardPrin-footer mt-3 d-flex justify-content-end gap-2">
                        <a wire:click.prevent="cancel()" class="bot botNegro">Cerrar</a>
                        <a wire:click.prevent="save()" class="bot botVerde">Guardar</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif
