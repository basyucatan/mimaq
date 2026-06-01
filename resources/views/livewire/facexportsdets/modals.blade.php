@if($verModalFacexportsdet)
    <div class="modal-overlay">
        <div x-data="{}" x-init="dragModal($el)" class="modal-dialog" wire:ignore.self>            
            <div class="modal-content">
                <div class="cardPrin">
                    <div class="cardPrin-header" style="cursor: move;">
                        <span>{{ $selected_id ? 'Editar Facexportsdet' : 'Crear Facexportsdet' }}</span>
                    </div>
                    <div class="cardPrin-body" style="padding: 10px; max-height: 400px; overflow-y: auto;">
                        <form gy-2>
                            <div class="row">
                                @if ($selected_id)
                                    <input type="hidden" wire:model="selected_id">
                                @endif                         
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
                                    <label class="etiBase">Peso Total</label>
                                    <input wire:model="pesoG" type="text" class="inpBase"  onfocus="this.select()">
                                    @error('pesoG') <span class="error text-danger">{{ $message }}</span> @enderror
                                </div>                            
                                <div class="col-md-6">
                                    <label class="etiBase">Casting</label>
                                    <input wire:model="castingG" type="text" class="inpBase"  onfocus="this.select()">
                                    @error('castingG') <span class="error text-danger">{{ $message }}</span> @enderror
                                </div>                            
                                <div class="col-md-6">
                                    <label class="etiBase">Piedras</label>
                                    <input wire:model="piedrasG" type="text" class="inpBase"  onfocus="this.select()">
                                    @error('piedrasG') <span class="error text-danger">{{ $message }}</span> @enderror
                                </div>                            
                                <div class="col-md-6">
                                    <label class="etiBase">Diamantes</label>
                                    <input wire:model="diamantesG" type="text" class="inpBase"  onfocus="this.select()">
                                    @error('diamantesG') <span class="error text-danger">{{ $message }}</span> @enderror
                                </div>    
                                <div class="col-md-6">
                                    <label class="etiBase">Misceláneo</label>
                                    <input wire:model="miscG" type="text" class="inpBase"  onfocus="this.select()">
                                    @error('miscG') <span class="error text-danger">{{ $message }}</span> @enderror
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