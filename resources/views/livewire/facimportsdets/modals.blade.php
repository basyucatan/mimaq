@if($verModalFacimportsdet)
    <div class="modal-overlay">
        <div x-data="{}" x-init="dragModal($el)" class="modal-dialog" wire:ignore.self>            
            <div class="modal-content">
                <div class="cardPrin" style="cursor: move;">
                    <div class="cardPrin-header">
                        <span>{{ $selected_id ? 'Editar Facimportsdet' : 'Crear Facimportsdet' }}</span>
                    </div>
                    <div class="cardPrin-body" style="padding: 10px; max-height: 400px; overflow-y: auto;">
                        <form>
                            <div class="row">
                                @if ($selected_id)
                                    <input type="hidden" wire:model="selected_id">
                                @endif

                                <div class="col-md-6">
                                    <label for="IdFacImport" class="etiBase">Idfacimport</label>
                                    <input wire:model="IdFacImport" type="text" class="inpBase"  onfocus="this.select()" id="IdFacImport">@error('IdFacImport') <span class="error text-danger">{{ $message }}</span> @enderror
                                </div>                            
                                <div class="col-md-6">
                                    <label for="IdEntradaMex" class="etiBase">Identradamex</label>
                                    <input wire:model="IdEntradaMex" type="text" class="inpBase"  onfocus="this.select()" id="IdEntradaMex">@error('IdEntradaMex') <span class="error text-danger">{{ $message }}</span> @enderror
                                </div>                            
                                <div class="col-md-6">
                                    <label for="IdOrigen" class="etiBase">Idorigen</label>
                                    <input wire:model="IdOrigen" type="text" class="inpBase"  onfocus="this.select()" id="IdOrigen">@error('IdOrigen') <span class="error text-danger">{{ $message }}</span> @enderror
                                </div>                            
                                <div class="col-md-6">
                                    <label for="IdMaterial" class="etiBase">Idmaterial</label>
                                    <input wire:model="IdMaterial" type="text" class="inpBase"  onfocus="this.select()" id="IdMaterial">@error('IdMaterial') <span class="error text-danger">{{ $message }}</span> @enderror
                                </div>                            
                                <div class="col-md-6">
                                    <label for="cantidad" class="etiBase">Cantidad</label>
                                    <input wire:model="cantidad" type="text" class="inpBase"  onfocus="this.select()" id="cantidad">@error('cantidad') <span class="error text-danger">{{ $message }}</span> @enderror
                                </div>                            
                                <div class="col-md-6">
                                    <label for="precioU" class="etiBase">Preciou</label>
                                    <input wire:model="precioU" type="text" class="inpBase"  onfocus="this.select()" id="precioU">@error('precioU') <span class="error text-danger">{{ $message }}</span> @enderror
                                </div>                            
                                <div class="col-md-6">
                                    <label for="pesoEnUMat" class="etiBase">Pesoenumat</label>
                                    <input wire:model="pesoEnUMat" type="text" class="inpBase"  onfocus="this.select()" id="pesoEnUMat">@error('pesoEnUMat') <span class="error text-danger">{{ $message }}</span> @enderror
                                </div>                            
                                <div class="col-md-6">
                                    <label for="pesoG" class="etiBase">Pesog</label>
                                    <input wire:model="pesoG" type="text" class="inpBase"  onfocus="this.select()" id="pesoG">@error('pesoG') <span class="error text-danger">{{ $message }}</span> @enderror
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