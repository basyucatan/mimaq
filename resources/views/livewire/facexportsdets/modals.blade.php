@if($verModalFacexportsdet)
    <div class="modal-overlay">
        <div x-data="{}" x-init="dragModal($el)" class="modal-dialog" wire:ignore.self>            
            <div class="modal-content">
                <div class="cardPrin" style="cursor: move;">
                    <div class="cardPrin-header">
                        <span>{{ $selected_id ? 'Editar Facexportsdet' : 'Crear Facexportsdet' }}</span>
                    </div>
                    <div class="cardPrin-body" style="padding: 10px; max-height: 400px; overflow-y: auto;">
                        <form gy-2>
                            <div class="row">
                                @if ($selected_id)
                                    <input type="hidden" wire:model="selected_id">
                                @endif

                                <div class="col-md-6">
                                    <label class="etiBase">Idfactura</label>
                                    <input wire:model="IdFactura" type="text" class="inpBase"  onfocus="this.select()">
                                    @error('IdFactura') <span class="error text-danger">{{ $message }}</span> @enderror
                                </div>                            
                                <div class="col-md-6">
                                    <label class="etiBase">Idbandeja</label>
                                    <input wire:model="IdBandeja" type="text" class="inpBase"  onfocus="this.select()">
                                    @error('IdBandeja') <span class="error text-danger">{{ $message }}</span> @enderror
                                </div>                            
                                <div class="col-md-6">
                                    <label class="etiBase">Productofinal</label>
                                    <input wire:model="productoFinal" type="text" class="inpBase"  onfocus="this.select()">
                                    @error('productoFinal') <span class="error text-danger">{{ $message }}</span> @enderror
                                </div>                            
                                <div class="col-md-6">
                                    <label class="etiBase">Arancel</label>
                                    <input wire:model="arancel" type="text" class="inpBase"  onfocus="this.select()">
                                    @error('arancel') <span class="error text-danger">{{ $message }}</span> @enderror
                                </div>                            
                                <div class="col-md-6">
                                    <label class="etiBase">Cantidad</label>
                                    <input wire:model="cantidad" type="text" class="inpBase"  onfocus="this.select()">
                                    @error('cantidad') <span class="error text-danger">{{ $message }}</span> @enderror
                                </div>                            
                                <div class="col-md-6">
                                    <label class="etiBase">Preciou</label>
                                    <input wire:model="precioU" type="text" class="inpBase"  onfocus="this.select()">
                                    @error('precioU') <span class="error text-danger">{{ $message }}</span> @enderror
                                </div>                            
                                <div class="col-md-6">
                                    <label class="etiBase">Pesototalg</label>
                                    <input wire:model="pesoTotalG" type="text" class="inpBase"  onfocus="this.select()">
                                    @error('pesoTotalG') <span class="error text-danger">{{ $message }}</span> @enderror
                                </div>                            
                                <div class="col-md-6">
                                    <label class="etiBase">Totalcastingg</label>
                                    <input wire:model="totalCastingG" type="text" class="inpBase"  onfocus="this.select()">
                                    @error('totalCastingG') <span class="error text-danger">{{ $message }}</span> @enderror
                                </div>                            
                                <div class="col-md-6">
                                    <label class="etiBase">Totalpiedrasct</label>
                                    <input wire:model="totalPiedrasCt" type="text" class="inpBase"  onfocus="this.select()">
                                    @error('totalPiedrasCt') <span class="error text-danger">{{ $message }}</span> @enderror
                                </div>                            
                                <div class="col-md-6">
                                    <label class="etiBase">Totaldiamantesct</label>
                                    <input wire:model="totalDiamantesCt" type="text" class="inpBase"  onfocus="this.select()">
                                    @error('totalDiamantesCt') <span class="error text-danger">{{ $message }}</span> @enderror
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

@if($verModalMaterials)
    <div class="modal fade show d-block" tabindex="-1" style="background: rgba(0,0,0,0.5);">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content cardPrin">
                <div class="cardPrin-body">
                    @if($selected_id)
                        @livewire('facexportsmats', ['IdFacExportsDet' => $selected_id], key('facexportsmats-'.$selected_id))
                    @else
                        <div class="p-5 text-center text-muted">
                            <span>Sin materiales</span>
                        </div>
                    @endif
                </div>
                <div class="cardPrin-footer">
                    <button type="button" class="bot botNegro" wire:click="cancel">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
@endif