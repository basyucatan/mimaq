@if($verModalArancel)
    <div class="modal-overlay">
        <div x-data="{}" x-init="dragModal($el)" class="modal-dialog" wire:ignore.self>            
            <div class="modal-content">
                <div class="cardPrin" style="cursor: move;">
                    <div class="cardPrin-header">
                        <span>{{ $selected_id ? 'Editar Arancel' : 'Crear Arancel' }}</span>
                    </div>
                    <div class="cardPrin-body" style="padding: 10px; max-height: 400px; overflow-y: auto;">
                        <form>
                            <div class="row">
                                @if ($selected_id)
                                    <input type="hidden" wire:model="selected_id">
                                @endif

                                <div class="col-md-6">
                                    <label for="arancel" class="etiBase">Arancel</label>
                                    <input wire:model="arancel" type="text" class="inpBase"  onfocus="this.select()" id="arancel">@error('arancel') <span class="error text-danger">{{ $message }}</span> @enderror
                                </div>                            
                                <div class="col-md-6">
                                    <label for="arancelUSA" class="etiBase">Arancelusa</label>
                                    <input wire:model="arancelUSA" type="text" class="inpBase"  onfocus="this.select()" id="arancelUSA">@error('arancelUSA') <span class="error text-danger">{{ $message }}</span> @enderror
                                </div>                            
                                <div class="col-md-6">
                                    <label for="descripcion" class="etiBase">Descripcion</label>
                                    <input wire:model="descripcion" type="text" class="inpBase"  onfocus="this.select()" id="descripcion">@error('descripcion') <span class="error text-danger">{{ $message }}</span> @enderror
                                </div>                            
                                <div class="col-md-6">
                                    <label for="IdPermiso" class="etiBase">Idpermiso</label>
                                    <input wire:model="IdPermiso" type="text" class="inpBase"  onfocus="this.select()" id="IdPermiso">@error('IdPermiso') <span class="error text-danger">{{ $message }}</span> @enderror
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