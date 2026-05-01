@if($verModalFoliosmat)
    <div class="modal-overlay">
        <div x-data="{}" x-init="dragModal($el)" class="modal-dialog" wire:ignore.self>            
            <div class="modal-content">
                <div class="cardPrin" style="cursor: move;">
                    <div class="cardPrin-header">
                        <span>{{ $selected_id ? 'Editar Foliosmat' : 'Crear Foliosmat' }}</span>
                    </div>
                    <div class="cardPrin-body" style="padding: 10px; max-height: 400px; overflow-y: auto;">
                        <form gy-2>
                            <div class="row">
                                @if ($selected_id)
                                    <input type="hidden" wire:model="selected_id">
                                @endif
                                <div class="col-md-6">
                                    <label class="etiBase">Material</label>
                                    <select wire:model="IdMaterial" wire:change="elegirMaterial" class="inpBase">
                                        <option value=""></option>
                                        @foreach ($materials as $key => $value)
                                            <option value="{{ $key }}">{{ $value }}</option>
                                        @endforeach
                                    </select>
                                    @error('IdMaterial') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
<div class="col-md-6">
    <label class="etiBase">Referencia</label>
    <select wire:model="IdFacImportsDet" wire:change="validarDisponibilidad" class="inpBase">
        <option value=""></option>
        @foreach ($referencias as $key => $value)
            <option value="{{ $key }}">{{ $value }}</option>
        @endforeach
    </select>
    @error('IdFacImportsDet') <span class="text-danger">{{ $message }}</span> @enderror
</div>
<div class="col-md-6">
    <label class="etiBase">Cantidad</label>
    <input wire:model="cantidad" wire:change="validarDisponibilidad" type="text" class="inpBase" onfocus="this.select()">
    @error('cantidad') <span class="error text-danger">{{ $message }}</span> @enderror
</div>                          
                                <div class="col-md-6">
                                    <label class="etiBase">Pesog</label>
                                    <input wire:model="pesoG" type="text" class="inpBase" readonly>
                                    @error('pesoG') <span class="error text-danger">{{ $message }}</span> @enderror
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