@if($verModalMoneda)
    <div class="modal-overlay">
        <div class="modal-dialog" style="width: 80%;">
            <div class="modal-content">
                <div class="cardPrin">
                    <div class="cardPrin-header d-flex justify-content-between align-items-center">
                        <h5 class="m-0">
                            {{ $selected_id ? 'Editar Moneda' : 'Crear Moneda' }}
                        </h5>
                        <button wire:click="cancel" type="button" class="btn-close" aria-label="Cerrar"></button>
                    </div>
                    <div class="cardPrin-body" style="padding: 0 20px; max-height: 400px; overflow-y: auto;">
                        <form>
                            @if ($selected_id)
                                <input type="hidden" wire:model="selected_id">
                            @endif
                    <div class="form-group">
                        <label for="moneda" class="etiBase">Moneda</label>
                        <input wire:model.live="moneda" type="text" class="inpBase" id="moneda">@error('moneda') <span class="error text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group">
                        <label for="centavos" class="etiBase">Centavos</label>
                        <input wire:model.live="centavos" type="text" class="inpBase" id="centavos">@error('centavos') <span class="error text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group">
                        <label for="simbolo" class="etiBase">Simbolo</label>
                        <input wire:model.live="simbolo" type="text" class="inpBase" id="simbolo">@error('simbolo') <span class="error text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group">
                        <label for="abreviatura" class="etiBase">Abreviatura</label>
                        <input wire:model.live="abreviatura" type="text" class="inpBase" id="abreviatura">@error('abreviatura') <span class="error text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group">
                        <label for="tipoCambio" class="etiBase">Tipocambio</label>
                        <input wire:model.live="tipoCambio" type="text" class="inpBase" id="tipoCambio">@error('tipoCambio') <span class="error text-danger">{{ $message }}</span> @enderror
                    </div>
</form>
                    </div>
                    <div class="cardPrin-footer mt-3 d-flex justify-content-end gap-2">
                        <a wire:click.prevent="cancel()" class="bot botCancel">Cerrar</a>
                        <a wire:click.prevent="save()" class="bot botVerde">Guardar</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif
