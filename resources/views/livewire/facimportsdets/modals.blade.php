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
                                    <label class="etiBase">Origen</label>
                                    <select wire:model="IdOrigen" class="inpBase">
                                        <option value=""></option>
                                        @foreach ($origens as $key => $value)
                                            <option value="{{ $key }}">{{ $value }}</option>
                                        @endforeach
                                    </select>
                                    @error('IdOrigen') <span class="text-danger">{{ $message }}</span> @enderror
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
                                    <label class="etiBase">Cantidad</label>
                                    <input wire:model="cantidad" type="text" class="inpBase"  onfocus="this.select()">
                                    @error('cantidad') <span class="error text-danger">{{ $message }}</span> @enderror
                                </div>                            
                                <div class="col-md-6">
                                    <label class="etiBase">Precio U</label>
                                    <input wire:model="precioU" type="text" class="inpBase"  onfocus="this.select()">
                                    @error('precioU') <span class="error text-danger">{{ $message }}</span> @enderror
                                </div>                            
                                <div class="col-md-6">
                                    <label class="etiBase">Peso</label>
                                    <input wire:model="pesoEnUMat" type="text" class="inpBase"  onfocus="this.select()">
                                    @error('pesoEnUMat') <span class="error text-danger">{{ $message }}</span> @enderror
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
                                    <label class="etiBase">Estilo</label>
                                    <select wire:model="IdEstilo" class="inpBase">
                                        <option value=""></option>
                                        @foreach ($estilos as $key => $value)
                                            <option value="{{ $key }}">{{ $value }}</option>
                                        @endforeach
                                    </select>
                                    @error('IdEstilo') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="etiBase">Ensamble</label>
                                    <input wire:model="estiloY" type="text" class="inpBase"  onfocus="this.select()">
                                    @error('estiloY') <span class="error text-danger">{{ $message }}</span> @enderror
                                </div>                                
                                <div class="col-md-6">
                                    <label class="etiBase">Orden</label>
                                    <input wire:model="orden" type="text" class="inpBase"  onfocus="this.select()">
                                    @error('orden') <span class="error text-danger">{{ $message }}</span> @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="etiBase">Lote</label>
                                    <input wire:model="lote" type="text" class="inpBase"  onfocus="this.select()">
                                    @error('lote') <span class="error text-danger">{{ $message }}</span> @enderror
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

@if($verModalEstilos)
    <div class="modal-overlay">
        <div x-data="{}" x-init="dragModal($el)" class="modal-dialog" wire:ignore.self>            
            <div class="modal-content">
                <div class="cardPrin" style="cursor: move;">
                    <div class="cardPrin-header">
                        <span>Añadir materiales con base a un Estilo</span>
                    </div>
                    <div class="cardPrin-body" style="padding: 10px; max-height: 400px; overflow-y: auto;">
                        <form>
                            <div class="row">
                                <div class="col-md-6">
                                    <label class="etiBase">Cantidad</label>
                                    <input wire:model="cantidadEstilo" type="text" class="inpBase"  onfocus="this.select()">
                                    @error('cantidadEstilo') <span class="error text-danger">{{ $message }}</span> @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="etiBase">Estilo</label>
                                    <select wire:model="IdEstilo" class="inpBase">
                                        <option value=""></option>
                                        @foreach ($estilos as $key => $value)
                                            <option value="{{ $key }}">{{ $value }}</option>
                                        @endforeach
                                    </select>
                                    @error('IdEstilo') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>                             
                                <div class="col-md-6">
                                    <label class="etiBase">Orden</label>
                                    <input wire:model="orden" type="text" class="inpBase"  onfocus="this.select()">
                                    @error('orden') <span class="error text-danger">{{ $message }}</span> @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="etiBase">Lote</label>
                                    <input wire:model="lote" type="text" class="inpBase"  onfocus="this.select()">
                                    @error('lote') <span class="error text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="cardPrin-footer mt-3 d-flex justify-content-end gap-2">
                        <button wire:click.prevent="cancel()" class="bot botNegro">Cerrar</button>
                        <button wire:click.prevent="generarConEstilo()" class="bot botVerde">Generar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif

@if($verModalImpresiones)
    <div class="modal-overlay">
        <div x-data="{}" x-init="dragModal($el)" class="modal-dialog" wire:ignore.self>            
            <div class="modal-content">
                <div class="cardPrin" style="cursor: move;">
                    <div class="cardPrin-header">
                        <span>Impresiones</span>
                    </div>
                    <div class="cardPrin-body" style="padding: 10px; max-height: 400px; overflow-y: auto;">
                        <form>
                            <div class="row">
                                <div class="col-md-6">
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="cardPrin-footer mt-3 d-flex justify-content-end gap-2">
                        <button wire:click.prevent="cancel()" class="bot botNegro">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif