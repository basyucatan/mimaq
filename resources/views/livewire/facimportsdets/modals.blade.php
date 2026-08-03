@if($verModalFacimportsdet)
    <div class="modal-overlay">
        <div x-data="{}" x-init="dragModal($el)" class="modal-dialog" wire:ignore.self>            
            <div class="modal-content">
                <div class="cardPrin">
                    <div class="cardPrin-header" style="cursor: move;">
                        <span>{{ $selected_id ? 'Edit Import Item' : 'Create Import Item' }}</span>
                    </div>
                    <div class="cardPrin-body" style="padding: 10px; max-height: 400px; overflow-y: auto;">
                        <form>
                            <div class="row g-1">
                                @if ($selected_id)
                                    <input type="hidden" wire:model="selected_id">
                                @endif
                                <div class="col-md-3">
                                    <label class="etiBase">Type</label>
                                    <select wire:model="IdTipo" wire:change="cambiarTipo" class="inpBase">
                                        <option value=""></option>
                                        @foreach ($tipos as $key => $value)
                                            <option value="{{ $key }}">{{ $value }}</option>
                                        @endforeach
                                    </select>
                                </div>                                 
                                <div class="col-md-5">
                                    <label class="etiBase">Material</label>
                                    <select wire:model="IdMaterial" wire:change="elegirMaterial" class="inpBase">
                                        <option value=""></option>
                                        @foreach ($materials as $key => $value)
                                            <option value="{{ $key }}">{{ $value }}</option>
                                        @endforeach
                                    </select>
                                    @error('IdMaterial') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                                <div class="col-md-2">
                                    <label class="etiBase">Quantity</label>
                                    <input wire:model="cantidad" type="text" class="inpBase">
                                    @error('cantidad') <span class="error text-danger">{{ $message }}</span> @enderror
                                </div>                            
                                <div class="col-md-2">
                                    <label class="etiBase">Unit Price</label>
                                    <input wire:model="precioU" type="text" class="inpBase">
                                    @error('precioU') <span class="error text-danger">{{ $message }}</span> @enderror
                                </div>                            
                                <div class="col-md-2"> 
                                    <label class="etiBase">Weight ({{ $unidadP ?? '' }})</label>
                                    <input wire:model="pesoEnUMat" type="text" class="inpBase">
                                    @error('pesoEnUMat') <span class="error text-danger">{{ $message }}</span> @enderror
                                </div>
                                <div class="col-md-2">
                                    <label class="etiBase">Style</label>
                                    <select wire:model="IdEstilo" class="inpBase">
                                        <option value=""></option>
                                        @foreach ($estilos as $key => $value)
                                            <option value="{{ $key }}">{{ $value }}</option>
                                        @endforeach
                                    </select>
                                    @error('IdEstilo') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                                <div class="col-md-2">
                                    <label class="etiBase">Aro</label>
                                    <select wire:model="aro" class="inpBase">
                                        <option value=""></option>
                                        @foreach ($aros as $key => $value)
                                            <option value="{{ $value }}">{{ $value }}</option>
                                        @endforeach
                                    </select>
                                    @error('aro') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                                <div class="col-md-2">
                                    <label class="etiBase">Kt</label>
                                    <select wire:model="kt" class="inpBase">
                                        <option value=""></option>
                                        @foreach ($kts as $key => $value)
                                            <option value="{{ $value }}">{{ $value }}</option>
                                        @endforeach
                                    </select>
                                    @error('kt') <span class="text-danger">{{ $message }}</span> @enderror
                                </div> 
                                <div class="col-md-2">
                                    <label class="etiBase">Color</label>
                                    <select wire:model="color" class="inpBase">
                                        <option value=""></option>
                                        @foreach ($colors as $key => $value)
                                            <option value="{{ $value }}">{{ $value }}</option>
                                        @endforeach
                                    </select>
                                    @error('color') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                                <div class="col-md-2">
                                    <label class="etiBase">Size</label>
                                    <select wire:model="IdSize" class="inpBase">
                                        <option value=""></option>
                                        @foreach ($sizes as $key => $value)
                                            <option value="{{ $key }}">{{ $value }}</option>
                                        @endforeach
                                    </select>
                                    @error('IdSize') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                                <div class="col-md-2">
                                    <label class="etiBase">Shape</label>
                                    <select wire:model="IdForma" class="inpBase">
                                        <option value=""></option>
                                        @foreach ($formas as $key => $value)
                                            <option value="{{ $key }}">{{ $value }}</option>
                                        @endforeach
                                    </select>
                                    @error('IdForma') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>                                                                                                                                                                                                                                           
                                <div class="col-md-2">
                                    <label class="etiBase">Assembly</label>
                                    <input wire:model="estiloY" type="text" class="inpBase">
                                    @error('estiloY') <span class="error text-danger">{{ $message }}</span> @enderror
                                </div>
                                <div class="col-md-2">
                                    <label class="etiBase">Origin</label>
                                    <select wire:model="IdOrigen" class="inpBase">
                                        <option value=""></option>
                                        @foreach ($origens as $key => $value)
                                            <option value="{{ $key }}">{{ $value }}</option>
                                        @endforeach
                                    </select>
                                    @error('IdOrigen') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>  
                                @unless($infoProduccion)
                                    <div class="col-md-6">
                                        <label class="etiBase">Order-Lot | Ticket (Customer)</label>
                                        <div class="d-flex gap-1">
                                            <select wire:model="IdFolio" class="inpBase" wire:change="actualizarDatosFolio">
                                                <option value="">--</option>
                                                @foreach ($folios as $f)
                                                    <option value="{{ $f->id }}">
                                                        {{ $f->lote->orden->orden }}-{{ $f->lote->lote }} | {{ $f->id }} ({{ $f->lote->orden->cliente->cliente }})
                                                    </option>
                                                @endforeach
                                            </select>
                                            @if($IdFolio)
                                                <button type="button" wire:click="limpiarFolio" class="bot botRojo" title="Desvincular">
                                                    <i class="bi bi-link-45deg"></i>
                                                </button>
                                            @endif
                                        </div>
                                    </div>
                                @endunless
                                <div class="col-md-2">
                                    <label class="etiLectura">MX Tariff</label>
                                    <input wire:model="arancel" type="text" class="inpBase" disabled onfocus="this.select()">
                                    @error('arancel') <span class="error text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="cardPrin-footer mt-3 d-flex justify-content-end gap-2">
                        <button wire:click.prevent="cancel()" class="bot botNegro">Close</button>
                        <button wire:click.prevent="save()" class="bot botVerde">Save</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif

@if($verModalProduccion)
    <div class="modal-overlay">
        <div x-data="{}" x-init="dragModal($el)" class="modal-dialog" wire:ignore.self>
            <div class="modal-content">
                <div class="cardPrin">
                    <div class="cardPrin-header" style="cursor: move;">
                        <span>Edit Production Data</span>
                    </div>
                    <div class="cardPrin-body" style="padding: 10px; max-height: 400px; overflow-y: auto;">
                        <form>
                            <div class="row g-2">
                                <div class="col-md-6">
                                    <label class="etiBase">Client</label>
                                    <select wire:model="IdCliente" class="inpBase">
                                        <option value=""></option>
                                        @foreach ($clientes as $key => $value)
                                            <option value="{{ $key }}">{{ $value }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="etiBase">Style</label>
                                    <select wire:model="IdEstilo" class="inpBase">
                                        <option value=""></option>
                                        @foreach ($estilos as $key => $value)
                                            <option value="{{ $key }}">{{ $value }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="etiBase">Order</label>
                                    <input type="text" wire:model="orden" class="inpBase">
                                </div>
                                <div class="col-md-4">
                                    <label class="etiBase">Lot</label>
                                    <input type="text" wire:model="lote" class="inpBase">
                                </div>
                                <div class="col-md-4">
                                    <label class="etiBase">Style Quantity</label>
                                    <input type="number" wire:model="cantidadEstilo" class="inpBase">
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="cardPrin-footer d-flex justify-content-end gap-2 p-2">
                        <button type="button" wire:click="cancel()" class="bot botGris">Cancel</button>
                        <button type="button" wire:click="saveProduccion()" class="bot botVerde">Save</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif