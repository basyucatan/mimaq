@if($verModalLotesfolio)
    <div class="modal-overlay">
        <div x-data="{}" x-init="dragModal($el)" class="modal-dialog" wire:ignore.self>            
            <div class="modal-content">
                <div class="cardPrin" style="cursor: move;">
                    <div class="cardPrin-header">
                        <span>{{ $selected_id ? 'Editar Lotesfolio' : 'Crear Lotesfolio' }}</span>
                    </div>
                    <div class="cardPrin-body" style="padding: 10px; max-height: 400px; overflow-y: auto;">
                        <form>
                            <div class="row">
                                @if ($selected_id)
                                    <input type="hidden" wire:model="selected_id">
                                @endif

                                <div class="col-md-6">
                                    <label for="IdLote" class="etiBase">Idlote</label>
                                    <input wire:model="IdLote" type="text" class="inpBase"  onfocus="this.select()" id="IdLote">@error('IdLote') <span class="error text-danger">{{ $message }}</span> @enderror
                                </div>                            
                                <div class="col-md-6">
                                    <label for="IdEstilo" class="etiBase">Idestilo</label>
                                    <input wire:model="IdEstilo" type="text" class="inpBase"  onfocus="this.select()" id="IdEstilo">@error('IdEstilo') <span class="error text-danger">{{ $message }}</span> @enderror
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
                                    <label for="peso" class="etiBase">Peso</label>
                                    <input wire:model="peso" type="text" class="inpBase"  onfocus="this.select()" id="peso">@error('peso') <span class="error text-danger">{{ $message }}</span> @enderror
                                </div>                            
                                <div class="col-md-6">
                                    <label for="jobStyle" class="etiBase">Jobstyle</label>
                                    <input wire:model="jobStyle" type="text" class="inpBase"  onfocus="this.select()" id="jobStyle">@error('jobStyle') <span class="error text-danger">{{ $message }}</span> @enderror
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