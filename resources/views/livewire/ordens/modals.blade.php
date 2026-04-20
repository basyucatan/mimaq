@if($verModalOrden)
    <div class="modal-overlay">
        <div x-data="{}" x-init="dragModal($el)" class="modal-dialog" wire:ignore.self>            
            <div class="modal-content">
                <div class="cardPrin" style="cursor: move;">
                    <div class="cardPrin-header">
                        <span>{{ $selected_id ? 'Editar Orden' : 'Crear Orden' }}</span>
                    </div>
                    <div class="cardPrin-body" style="padding: 10px; max-height: 400px; overflow-y: auto;">
                        <form>
                            <div class="row">
                                @if ($selected_id)
                                    <input type="hidden" wire:model="selected_id">
                                @endif

                                <div class="col-md-6">
                                    <label for="IdCliente" class="etiBase">Idcliente</label>
                                    <input wire:model="IdCliente" type="text" class="inpBase"  onfocus="this.select()" id="IdCliente">@error('IdCliente') <span class="error text-danger">{{ $message }}</span> @enderror
                                </div>                            
                                <div class="col-md-6">
                                    <label for="orden" class="etiBase">Orden</label>
                                    <input wire:model="orden" type="text" class="inpBase"  onfocus="this.select()" id="orden">@error('orden') <span class="error text-danger">{{ $message }}</span> @enderror
                                </div>                            
                                <div class="col-md-6">
                                    <label for="estatus" class="etiBase">Estatus</label>
                                    <input wire:model="estatus" type="text" class="inpBase"  onfocus="this.select()" id="estatus">@error('estatus') <span class="error text-danger">{{ $message }}</span> @enderror
                                </div>                            
                                <div class="col-md-6">
                                    <label for="fechaVen" class="etiBase">Fechaven</label>
                                    <input wire:model="fechaVen" type="text" class="inpBase"  onfocus="this.select()" id="fechaVen">@error('fechaVen') <span class="error text-danger">{{ $message }}</span> @enderror
                                </div>                            

                            </div>
                        </form>
                    </div>
                    <div class="cardPrin-footer mt-3 d-flex justify-content-end gap-2">
                        <button wire:click.prevent="cancel()" class="bot botNegro">Cerrar</button>
                        <button wire:click.prevent="save()" class="bot botVerde">Guardar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif