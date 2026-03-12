@if ($verModalNegocio)
    <div class="modal-overlay">
        <div x-data="{}" x-init="dragModal($el)" class="modal-dialog" style="width: 98%;" wire:ignore.self>
            <div class="modal-content">
                <div class="cardPrin" style="cursor: move;">
                    <div class="cardPrin-header d-flex justify-content-between align-items-center">
                        <h5 class="m-0">
                            {{ $selected_id ? 'Editar Negocio' : 'Crear Negocio' }}
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
                                    <label for="negocio" class="etiBase">Negocio</label>
                                    <input wire:model="negocio" type="text" class="inpBase" id="negocio">
                                    @error('negocio')
                                        <span class="error text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="razonSocial" class="etiBase">Razonsocial</label>
                                    <input wire:model="razonSocial" type="text" class="inpBase" id="razonSocial">
                                    @error('razonSocial')
                                        <span class="error text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="logo" class="etiBase">Logo</label>
                                    <input wire:model="logo" type="text" class="inpBase" id="logo">
                                    @error('logo')
                                        <span class="error text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="adicionales" class="etiBase">Adicionales</label>
                                    <input wire:model="adicionales" type="text" class="inpBase" id="adicionales">
                                    @error('adicionales')
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
