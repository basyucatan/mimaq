@if ($verModalEmpresascuenta)
    <div class="modal-overlay">
        <div x-data="{}" x-init="dragModal($el)" class="modal-dialog" style="width: 80%;" wire:ignore.self>
            <div class="modal-content">
                <div class="cardPrin" style="cursor: move;">
                    <div class="cardPrin-header d-flex justify-content-between align-items-center">
                        <h5 class="m-0">
                            {{ $selected_id ? 'Editar Cuenta' : 'Crear Cuenta' }}
                        </h5>
                        <button wire:click="cancel" type="button" class="btn-close" aria-label="Cerrar"></button>
                    </div>
                    <div class="cardPrin-body" style="padding: 0 20px; max-height: 400px; overflow-y: auto;">
                        <form>
                            <div class="row">
                                @if ($selected_id)
                                    <input type="hidden" wire:model="selected_id">
                                @endif
                                <div class="col-md-6">
                                    <label for="banco" class="etiBase">Banco</label>
                                    <input wire:model="banco" type="text" class="inpBase" id="banco">
                                    @error('banco')
                                        <span class="error text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="cuenta" class="etiBase">Cuenta</label>
                                    <input wire:model="cuenta" type="text" class="inpBase" id="cuenta">
                                    @error('cuenta')
                                        <span class="error text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="cuentaClabe" class="etiBase">Cuentaclabe</label>
                                    <input wire:model="cuentaClabe" type="text" class="inpBase" id="cuentaClabe">
                                    @error('cuentaClabe')
                                        <span class="error text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </form>
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
