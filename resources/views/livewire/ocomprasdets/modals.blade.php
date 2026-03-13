@if($verModalOcomprasdet)
    <div class="modal-overlay"> 
        <div x-data="{}" x-init="dragModal($el)" class="modal-dialog" style="width: 80%;" wire:ignore.self>
            <div class="modal-content">
                <div class="cardPrin" style="cursor: move;">
                    <div class="cardPrin-header d-flex justify-content-between align-items-center">
                        <h5 class="m-0">
                            {{ $selected_id ? 'Editar Ocomprasdet' : 'Crear Ocomprasdet' }}
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
                                    <label for="IdOCompra" class="etiBase">Idocompra</label>
                                    <input wire:model="IdOCompra" type="text" class="inpBase" id="IdOCompra">@error('IdOCompra') <span class="error text-danger">{{ $message }}</span> @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="IdMatCosto" class="etiBase">Idmatcosto</label>
                                    <input wire:model="IdMatCosto" type="text" class="inpBase" id="IdMatCosto">@error('IdMatCosto') <span class="error text-danger">{{ $message }}</span> @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="cantidad" class="etiBase">Cantidad</label>
                                    <input wire:model="cantidad" type="text" class="inpBase" id="cantidad">@error('cantidad') <span class="error text-danger">{{ $message }}</span> @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="costoU" class="etiBase">Costou</label>
                                    <input wire:model="costoU" type="text" class="inpBase" id="costoU">@error('costoU') <span class="error text-danger">{{ $message }}</span> @enderror
                                </div>
</div></form>
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