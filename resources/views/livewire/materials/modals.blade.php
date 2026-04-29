@if($verModalMaterial)
    <div class="modal-overlay">
        <div x-data="{}" x-init="dragModal($el)" class="modal-dialog" wire:ignore.self>            
            <div class="modal-content">
                <div class="cardPrin" style="cursor: move;">
                    <div class="cardPrin-header">
                        <span>{{ $selected_id ? 'Editar Material' : 'Crear Material' }}</span>
                    </div>
                    <div class="cardPrin-body" style="padding: 10px; max-height: 400px; overflow-y: auto;">
                        <form>
                            <div class="row">
                                @if ($selected_id)
                                    <input type="hidden" wire:model="selected_id">
                                @endif                          
                                <div class="col-md-6">
                                    <label for="material" class="etiBase">Material</label>
                                    <input wire:model="material" type="text" class="inpBase"  onfocus="this.select()" id="material">@error('material') <span class="error text-danger">{{ $message }}</span> @enderror
                                </div>                            
                                <div class="col-md-6">
                                    <label for="materialI" class="etiBase">Material (Inglés)</label>
                                    <input wire:model="materialI" type="text" class="inpBase"  onfocus="this.select()" id="materialI">@error('materialI') <span class="error text-danger">{{ $message }}</span> @enderror
                                </div>                            
                                <div class="col-md-6">
                                    <label for="materialFiscal" class="etiBase">Material (Fiscal)</label>
                                    <input wire:model="materialFiscal" type="text" class="inpBase"  onfocus="this.select()" id="materialFiscal">@error('materialFiscal') <span class="error text-danger">{{ $message }}</span> @enderror
                                </div>                            
                                <div class="col-md-6">
                                    <label for="abreviatura" class="etiBase">Abreviatura</label>
                                    <input wire:model="abreviatura" type="text" class="inpBase"  onfocus="this.select()" id="abreviatura">@error('abreviatura') <span class="error text-danger">{{ $message }}</span> @enderror
                                </div>                            
                                <div class="col-md-6">
                                    <label class="etiBase">Clase</label>
                                    <select wire:model="IdClase" class="inpBase">
                                        <option value=""></option>
                                        @foreach ($clases as $key => $value)
                                            <option value="{{ $key }}">{{ $value }}</option>
                                        @endforeach
                                    </select>
                                    @error('IdClase') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>                            
                                <div class="col-md-6">
                                    <label class="etiBase">Unidad</label>
                                    <select wire:model="IdUnidad" class="inpBase">
                                        <option value=""></option>
                                        @foreach ($unidads as $key => $value)
                                            <option value="{{ $key }}">{{ $value }}</option>
                                        @endforeach
                                    </select>
                                    @error('IdUnidad') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="etiBase">Unidad de Peso</label>
                                    <select wire:model="IdUnidadP" class="inpBase">
                                        <option value=""></option>
                                        @foreach ($unidads as $key => $value)
                                            <option value="{{ $key }}">{{ $value }}</option>
                                        @endforeach
                                    </select>
                                    @error('IdUnidadP') <span class="text-danger">{{ $message }}</span> @enderror
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