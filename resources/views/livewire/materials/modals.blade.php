@if ($verModalMaterial)
    <div class="modal-overlay"> 
        <div x-data="{
                manejarPegado(event) {
                    const items = (event.clipboardData || event.originalEvent.clipboardData).items;
                    for (let index in items) {
                        const item = items[index];
                        if (item.kind === 'file' && item.type.indexOf('image/') !== -1) {
                            const blob = item.getAsFile();
                            @this.upload('fotoSubida', blob, (uploadedFilename) => {
                                // Éxito
                            }, () => {
                                // Error
                            }, (event) => {
                                // Progreso
                            });
                        }
                    }
                }
            }" 
            @paste.window="manejarPegado($event)"
            x-init="dragModal($el)" 
            class="modal-dialog" 
            style="width: 80%;" 
            wire:ignore.self>
            <div class="modal-content">
                <div class="cardPrin" style="cursor: move;">
                    <div class="cardPrin-header d-flex justify-content-between align-items-center">
                        <h5 class="m-0">
                            {{ $selected_id ? 'Editar Material' : 'Crear Material' }}
                        </h5>
                        <button wire:click="cancel" type="button" class="btn-close" aria-label="Cerrar"></button>
                    </div>
                    <div class="cardPrin-body" style="padding: 0 20px; max-height: 500px; overflow-y: auto;">
                        <form>
                            <div class="row g-1">
                                <div class="col-6 col-md-4">
                                    <label class="etiBase" for="referencia">Referencia</label>
                                    <input wire:model="referencia" type="text" class="inpBase" id="referencia">
                                    @error('referencia') <span class="error text-danger">{{ $message }}</span> @enderror
                                </div> 
                                <div class="col-6 col-md-4">
                                    <label class="etiBase">Clase</label>
                                    <select wire:model="IdClase" class="inpBase">
                                        <option value=""></option>
                                        @foreach ($clases as $key => $value)
                                        <option value="{{ $key }}">{{ $value }}</option>
                                        @endforeach
                                    </select>
                                    @error('IdClase') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>                                                              
                                <div class="col-12">
                                    <label class="etiBase" for="material">material</label>
                                    <input wire:model="material" type="text" class="inpBase" id="material">
                                    @error('material') <span class="error text-danger">{{ $message }}</span> @enderror
                                </div>  
                                <div class="col-6 col-md-4">
                                    <label class="etiBase">Marca</label>
                                    <select wire:model="IdMarca" class="inpBase">
                                        <option value=""></option>
                                        @foreach ($marcas as $key => $value)
                                        <option value="{{ $key }}">{{ $value }}</option>
                                        @endforeach
                                    </select>
                                    @error('IdMarca') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>  
                                <div class="col-6 col-md-4">
                                    <label class="etiBase">Linea</label>
                                    <select wire:model="IdLinea" class="inpBase">
                                        <option value=""></option>
                                        @foreach ($lineas as $key => $value)
                                        <option value="{{ $key }}">{{ $value }}</option>
                                        @endforeach
                                    </select>
                                    @error('IdLinea') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>                                                                                             
                                <div class="col-6 col-md-4">
                                    <label for="IdUnidad" class="etiBase">Unidad</label>
                                    <select id="IdUnidad" class="inpBase" wire:model="IdUnidad">
                                        <option value=""></option>
                                        @foreach ($unidads as $key => $value)
                                            <option value="{{ $key }}" {{ $IdUnidad == $key ? 'selected' : '' }}>
                                                {{ $value }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('IdUnidad') <span class="error text-danger">{{ $message }}</span> @enderror
                                </div>
                                <div class="col-6 col-md-4">
                                    <label for="IdTipo" class="etiBase">Tipo</label>
                                    <select id="IdTipo" class="inpBase{{ $errors->has('IdTipo') ? ' is-invalid' : '' }}" wire:model="IdTipo">
                                        <option value=""></option>
                                        @foreach ($tipos as $key => $value)
                                            <option value="{{ $key }}" {{ $IdTipo == $key ? 'selected' : '' }}>{{ $value }}</option>
                                        @endforeach
                                    </select>
                                    @error('IdTipo') <span class="error text-danger">{{ $message }}</span> @enderror
                                </div>
                                <div class="col-6 col-md-4">
                                    <label class="etiBase" for="anchoLama">Ancho de Lama</label>
                                    <input wire:model="anchoLama" type="text" class="inpBase" id="anchoLama">
                                    @error('anchoLama') <span class="error text-danger">{{ $message }}</span> @enderror
                                </div>
                                <div class="col-6 col-md-4">
                                    <label class="etiBase" for="KgxMetro">Kg por Metro</label>
                                    <input wire:model="KgxMetro" type="text" class="inpBase" id="KgxMetro">
                                    @error('KgxMetro') <span class="error text-danger">{{ $message }}</span> @enderror
                                </div>
                                <div class="col-6 col-md-4">
                                    <label class="etiBase" for="rendimiento">Rendimiento</label>
                                    <input wire:model="rendimiento" type="text" class="inpBase" id="rendimiento">
                                    @error('rendimiento') <span class="error text-danger">{{ $message }}</span> @enderror
                                </div>
                                <div class="col-6 col-md-4">
                                    <label for="IdUnidadRend" class="etiBase">Unidad (Rend.)</label>
                                    <select id="IdUnidadRend" class="inpBase{{ $errors->has('IdUnidadRend') ? ' is-invalid' : '' }}" wire:model="IdUnidadRend">
                                        <option value=""></option>
                                        @foreach ($unidads as $key => $value)
                                            <option value="{{ $key }}" {{ $IdUnidadRend == $key ? 'selected' : '' }}>{{ $value }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-12">
                                    <label class="etiBase">Cargar Foto (Ctrl+V captura)</label>
                                </div>
                                <div class="col-md-12 mt-2">
                                    <div class="d-flex align-items-center gap-3 border p-2 rounded bg-light">
                                        <img 
                                            src="{{ $fotoSubida ? $fotoSubida->temporaryUrl() : ($fotoUrl ?? asset('img/no-image.png')) }}"
                                            class="border rounded bg-white"
                                            style="width:100px;height:100px;object-fit:contain;">
                                        <div class="flex-grow-1">
                                            <input type="file" wire:model="fotoSubida" class="form-control form-control-sm">
                                            <div wire:loading wire:target="fotoSubida" class="text-primary small">
                                                Cargando...
                                            </div>
                                            <div x-show="subiendo" class="text-primary small">
                                                Pegando imagen...
                                            </div>
                                            @error('fotoSubida')
                                                <div class="text-danger small">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="cardPrin-footer mt-3 d-flex justify-content-end gap-2">
                        <a wire:click.prevent="cancel()" class="bot botCancel">Cerrar</a>
                        <a wire:click.prevent="save()" class="bot botVerde">Guardar</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif