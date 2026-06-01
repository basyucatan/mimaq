@if ($verModalFoliosmat)
    <div class="modal-overlay">
        <div x-data="{}" x-init="dragModal($el)" class="modal-dialog" wire:ignore.self>
            <div class="modal-content">
                <div class="cardPrin">
                    <div class="cardPrin-header" style="cursor: move;">
                        <span>{{ $selected_id ? 'Editar Material' : 'Crear Material' }}</span>
                    </div>
                    <div class="cardPrin-body" style="padding: 10px; max-height: 400px; overflow-y: auto;">
                        <form gy-2>
                            <div class="row">
                                @if ($errors->any())
                                    <div class="col-12">
                                        <div class="alert alert-danger py-2 px-3 mb-3 shadow-sm" style="font-size: 0.85rem; border-left: 4px solid #dc3545;">
                                            <ul class="mb-0 ps-2">
                                                @foreach ($errors->all() as $error)
                                                    <li>{{ $error }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                @endif
                                <div class="col-md-4">
                                    <label class="etiBase">Tipo</label>
                                    <select wire:model.live="IdTipo" class="inpBase">
                                        <option value=""></option>
                                        @foreach ($tipos as $key => $value)
                                            <option value="{{ $key }}">{{ $value }}</option>
                                        @endforeach
                                    </select>
                                </div>                                                               
                                <div class="col-md-8">
                                    <label class="etiBase">Material</label>
                                    <select wire:model.live="IdMaterial" class="inpBase @error('IdMaterial') is-invalid @enderror">
                                        <option value=""></option>
                                        @foreach ($materials as $key => $value)
                                            <option value="{{ $key }}">{{ $value }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="etiBase">Referencia</label>
                                    <select wire:model="IdFacImportsDet" wire:change="validarDisponibilidad" class="inpBase @error('IdFacImportsDet') is-invalid @enderror">
                                        <option value=""></option>
                                        @foreach ($referencias as $key => $value)
                                            <option value="{{ $key }}">{{ $value }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="etiBase">Cantidad</label>
                                    <input wire:model="cantidad" wire:change="validarDisponibilidad" type="number" class="inpBase @error('cantidad') is-invalid @enderror" onfocus="this.select()">
                                </div>
                                <div class="col-md-3">
                                    <label class="etiLectura">Peso (g)</label>
                                    <input wire:model="pesoG" type="number" class="inpBase" disabled>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="cardPrin-footer mt-3 d-flex justify-content-end gap-2">
                        <button wire:click.prevent="cancel()" class="bot botNegro botChico">Cerrar</button>
                        <button wire:click.prevent="save()" 
                                class="bot botVerde botChico {{ $errors->any() ? 'opacity-50' : '' }}" 
                                @if($errors->any()) disabled @endif>
                            Guardar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif