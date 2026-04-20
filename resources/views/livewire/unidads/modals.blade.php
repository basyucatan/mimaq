@if($verModalUnidad)
    <div class="modal-overlay">
        <div x-data="{}" x-init="dragModal($el)" class="modal-dialog" wire:ignore.self>            
            <div class="modal-content">
                <div class="cardPrin" style="cursor: move;">
                    <div class="cardPrin-header">
                        <span>{{ $selected_id ? 'Editar Unidad' : 'Crear Unidad' }}</span>
                    </div>
                    <div class="cardPrin-body" style="padding: 10px; max-height: 400px; overflow-y: auto;">
                        <form>
                            <div class="row">
                                @if ($selected_id)
                                    <input type="hidden" wire:model="selected_id">
                                @endif

                                <div class="col-md-6">
                                    <label for="unidad" class="etiBase">Unidad</label>
                                    <input wire:model="unidad" type="text" class="inpBase"  onfocus="this.select()" id="unidad">@error('unidad') <span class="error text-danger">{{ $message }}</span> @enderror
                                </div>                            
                                <div class="col-md-6">
                                    <label for="unidadI" class="etiBase">Unidadi</label>
                                    <input wire:model="unidadI" type="text" class="inpBase"  onfocus="this.select()" id="unidadI">@error('unidadI') <span class="error text-danger">{{ $message }}</span> @enderror
                                </div>                            
                                <div class="col-md-6">
                                    <label for="factorC" class="etiBase">Factorc</label>
                                    <input wire:model="factorC" type="text" class="inpBase"  onfocus="this.select()" id="factorC">@error('factorC') <span class="error text-danger">{{ $message }}</span> @enderror
                                </div>                            
                                <div class="col-md-6">
                                    <label for="grupo" class="etiBase">Grupo</label>
                                    <input wire:model="grupo" type="text" class="inpBase"  onfocus="this.select()" id="grupo">@error('grupo') <span class="error text-danger">{{ $message }}</span> @enderror
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