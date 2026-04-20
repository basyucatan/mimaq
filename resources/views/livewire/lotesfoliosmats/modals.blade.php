@if($verModalLotesfoliosmat)
    <div class="modal-overlay">
        <div x-data="{}" x-init="dragModal($el)" class="modal-dialog" wire:ignore.self>            
            <div class="modal-content">
                <div class="cardPrin" style="cursor: move;">
                    <div class="cardPrin-header">
                        <span>{{ $selected_id ? 'Editar Lotesfoliosmat' : 'Crear Lotesfoliosmat' }}</span>
                    </div>
                    <div class="cardPrin-body" style="padding: 10px; max-height: 400px; overflow-y: auto;">
                        <form>
                            <div class="row">
                                @if ($selected_id)
                                    <input type="hidden" wire:model="selected_id">
                                @endif

                                <div class="col-md-6">
                                    <label for="IdLotesFolio" class="etiBase">Idlotesfolio</label>
                                    <input wire:model="IdLotesFolio" type="text" class="inpBase"  onfocus="this.select()" id="IdLotesFolio">@error('IdLotesFolio') <span class="error text-danger">{{ $message }}</span> @enderror
                                </div>                            
                                <div class="col-md-6">
                                    <label for="IdFacImportsDet" class="etiBase">Idfacimportsdet</label>
                                    <input wire:model="IdFacImportsDet" type="text" class="inpBase"  onfocus="this.select()" id="IdFacImportsDet">@error('IdFacImportsDet') <span class="error text-danger">{{ $message }}</span> @enderror
                                </div>                            
                                <div class="col-md-6">
                                    <label for="cantidad" class="etiBase">Cantidad</label>
                                    <input wire:model="cantidad" type="text" class="inpBase"  onfocus="this.select()" id="cantidad">@error('cantidad') <span class="error text-danger">{{ $message }}</span> @enderror
                                </div>                            
                                <div class="col-md-6">
                                    <label for="IdMaterial" class="etiBase">Idmaterial</label>
                                    <input wire:model="IdMaterial" type="text" class="inpBase"  onfocus="this.select()" id="IdMaterial">@error('IdMaterial') <span class="error text-danger">{{ $message }}</span> @enderror
                                </div>                            
                                <div class="col-md-6">
                                    <label for="pesoEnUMat" class="etiBase">Pesoenumat</label>
                                    <input wire:model="pesoEnUMat" type="text" class="inpBase"  onfocus="this.select()" id="pesoEnUMat">@error('pesoEnUMat') <span class="error text-danger">{{ $message }}</span> @enderror
                                </div>                            
                                <div class="col-md-6">
                                    <label for="IdSize" class="etiBase">Idsize</label>
                                    <input wire:model="IdSize" type="text" class="inpBase"  onfocus="this.select()" id="IdSize">@error('IdSize') <span class="error text-danger">{{ $message }}</span> @enderror
                                </div>                            
                                <div class="col-md-6">
                                    <label for="IdForma" class="etiBase">Idforma</label>
                                    <input wire:model="IdForma" type="text" class="inpBase"  onfocus="this.select()" id="IdForma">@error('IdForma') <span class="error text-danger">{{ $message }}</span> @enderror
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