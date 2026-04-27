@if($verModalEstilosdet)
    <div class="modal-overlay">
        <div x-data="{}" x-init="dragModal($el)" class="modal-dialog" wire:ignore.self>            
            <div class="modal-content">
                <div class="cardPrin" style="cursor: move;">
                    <div class="cardPrin-header">
                        <span>{{ $selected_id ? 'Editar Estilosdet' : 'Crear Estilosdet' }}</span>
                    </div>
                    <div class="cardPrin-body" style="padding: 10px; max-height: 400px; overflow-y: auto;">
                        <form gy-2>
                            <div class="row">
                                @if ($selected_id)
                                    <input type="hidden" wire:model="selected_id">
                                @endif

                                <div class="col-md-6">
                                    <label class="etiBase">Idestilo</label>
                                    <input wire:model="IdEstilo" type="text" class="inpBase"  onfocus="this.select()">
                                    @error('IdEstilo') <span class="error text-danger">{{ $message }}</span> @enderror
                                </div>                            
                                <div class="col-md-6">
                                    <label class="etiBase">Cantidad</label>
                                    <input wire:model="cantidad" type="text" class="inpBase"  onfocus="this.select()">
                                    @error('cantidad') <span class="error text-danger">{{ $message }}</span> @enderror
                                </div>                            
                                <div class="col-md-6">
                                    <label class="etiBase">Idmaterial</label>
                                    <input wire:model="IdMaterial" type="text" class="inpBase"  onfocus="this.select()">
                                    @error('IdMaterial') <span class="error text-danger">{{ $message }}</span> @enderror
                                </div>                            
                                <div class="col-md-6">
                                    <label class="etiBase">Idsize</label>
                                    <input wire:model="IdSize" type="text" class="inpBase"  onfocus="this.select()">
                                    @error('IdSize') <span class="error text-danger">{{ $message }}</span> @enderror
                                </div>                            
                                <div class="col-md-6">
                                    <label class="etiBase">Idforma</label>
                                    <input wire:model="IdForma" type="text" class="inpBase"  onfocus="this.select()">
                                    @error('IdForma') <span class="error text-danger">{{ $message }}</span> @enderror
                                </div>                            
                                <div class="col-md-6">
                                    <label class="etiBase">Estiloy</label>
                                    <input wire:model="estiloY" type="text" class="inpBase"  onfocus="this.select()">
                                    @error('estiloY') <span class="error text-danger">{{ $message }}</span> @enderror
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