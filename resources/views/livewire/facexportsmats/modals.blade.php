@if($verModalFacexportsmat)
    <div class="modal-overlay">
        <div x-data="{}" x-init="dragModal($el)" class="modal-dialog" wire:ignore.self>            
            <div class="modal-content">
                <div class="cardPrin">
                    <div class="cardPrin-header" style="cursor: move;">
                        <span>{{ $selected_id ? 'Editar Facexportsmat' : 'Crear Facexportsmat' }}</span>
                    </div>
                    <div class="cardPrin-body" style="padding: 10px; max-height: 400px; overflow-y: auto;">
                        <form gy-2>
                            <div class="row">
                                @if ($selected_id)
                                    <input type="hidden" wire:model="selected_id">
                                @endif

                                <div class="col-md-6">
                                    <label class="etiBase">Idfacexportsdet</label>
                                    <input wire:model="IdFacExportsDet" type="text" class="inpBase"  onfocus="this.select()">
                                    @error('IdFacExportsDet') <span class="error text-danger">{{ $message }}</span> @enderror
                                </div>                            
                                <div class="col-md-6">
                                    <label class="etiBase">Idfacimportsdet</label>
                                    <input wire:model="IdFacImportsDet" type="text" class="inpBase"  onfocus="this.select()">
                                    @error('IdFacImportsDet') <span class="error text-danger">{{ $message }}</span> @enderror
                                </div>                            
                                <div class="col-md-6">
                                    <label class="etiBase">Idmaterial</label>
                                    <input wire:model="IdMaterial" type="text" class="inpBase"  onfocus="this.select()">
                                    @error('IdMaterial') <span class="error text-danger">{{ $message }}</span> @enderror
                                </div>                            
                                <div class="col-md-6">
                                    <label class="etiBase">Idtipo</label>
                                    <input wire:model="IdTipo" type="text" class="inpBase"  onfocus="this.select()">
                                    @error('IdTipo') <span class="error text-danger">{{ $message }}</span> @enderror
                                </div>                            
                                <div class="col-md-6">
                                    <label class="etiBase">Cantidaddescargada</label>
                                    <input wire:model="cantidadDescargada" type="text" class="inpBase"  onfocus="this.select()">
                                    @error('cantidadDescargada') <span class="error text-danger">{{ $message }}</span> @enderror
                                </div>                            
                                <div class="col-md-6">
                                    <label class="etiBase">Pesodescargadog</label>
                                    <input wire:model="pesoDescargadoG" type="text" class="inpBase"  onfocus="this.select()">
                                    @error('pesoDescargadoG') <span class="error text-danger">{{ $message }}</span> @enderror
                                </div>                            

                            </div>
                        </form>
                    </div>
                    <div class="cardPrin-footer mt-3 d-flex justify-content-end gap-2">
                        <button wire:click.prevent="cancel()" class="bot botNegro botChico">Cerrar</button>
                        <button wire:click.prevent="save()" class="bot botVerde botChico">Guardar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif