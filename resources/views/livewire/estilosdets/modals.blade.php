@if($verModalEstilosdet)
    <div class="modal-overlay">
        <div x-data="{}" x-init="dragModal($el)" class="modal-dialog" wire:ignore.self>            
            <div class="modal-content">
                <div class="cardPrin" style="cursor: move;">
                    <div class="cardPrin-header">
                        <span>{{ $selected_id ? 'Editar Estilosdet' : 'Crear Estilosdet' }}</span>
                    </div>
                    <div class="cardPrin-body" style="padding: 10px; max-height: 400px; overflow-y: auto;">
                        <form gy-2>
                            <div class="row">
                                @if ($selected_id)
                                    <input type="hidden" wire:model="selected_id">
                                @endif                           
                                <div class="col-md-6">
                                    <label class="etiBase">Cantidad</label>
                                    <input wire:model="cantidad" type="text" class="inpBase"  onfocus="this.select()">
                                    @error('cantidad') <span class="error text-danger">{{ $message }}</span> @enderror
                                </div>                            
                                <div class="col-md-6">
                                    <label class="etiBase">Material</label>
                                    <select wire:model="IdMaterial" class="inpBase">
                                        <option value=""></option>
                                        @foreach ($materials as $key => $value)
                                            <option value="{{ $key }}">{{ $value }}</option>
                                        @endforeach
                                    </select>
                                    @error('IdMaterial') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>                           
                                <div class="col-md-6">
                                    <label class="etiBase">Size</label>
                                    <select wire:model="IdSize" class="inpBase">
                                        <option value=""></option>
                                        @foreach ($sizes as $key => $value)
                                            <option value="{{ $key }}">{{ $value }}</option>
                                        @endforeach
                                    </select>
                                    @error('IdSize') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="etiBase">Forma</label>
                                    <select wire:model="IdForma" class="inpBase">
                                        <option value=""></option>
                                        @foreach ($formas as $key => $value)
                                            <option value="{{ $key }}">{{ $value }}</option>
                                        @endforeach
                                    </select>
                                    @error('IdForma') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="etiBase">Estilo Ensamble</label>
                                    <input wire:model="estiloY" type="text" class="inpBase"  onfocus="this.select()">
                                    @error('estiloY') <span class="error text-danger">{{ $message }}</span> @enderror
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