@if($verModalEstilo)
<div class="modal-overlay">
    <div x-data="{}" x-init="dragModal($el)" class="modal-dialog" wire:ignore.self>
        <div class="modal-content">
            <div class="cardPrin" style="cursor: move;">
                <div class="cardPrin-header">
                    <span>{{ $selected_id ? 'Editar Estilo' : 'Crear Estilo' }}</span>
                </div>
                <div class="cardPrin-body" style="padding: 10px; max-height: 400px; overflow-y: auto;">
                    <form>
                        <div class="row">
                            @if ($selected_id)
                                <input type="hidden" wire:model="selected_id">
                            @endif
                            <div class="col-md-6">
                                <label for="estilo" class="etiBase">Estilo</label>
                                <input wire:model="estilo" type="text" class="inpBase" onfocus="this.select()" id="estilo">
                                @error('estilo') <span class="error text-danger">{{ $message }}</span> @enderror
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
<div class="col-12 mt-3" x-data="{ dragging: false }">
    <div class="d-flex align-items-center gap-3 border p-2 rounded bg-light" @paste.window="const items = $event.clipboardData.items; for (let i = 0; i < items.length; i++) { if (items[i].type.indexOf('image') !== -1) { const blob = items[i].getAsFile(); @this.upload('fotoSubida', blob); } }">
        <div class="border rounded d-flex align-items-center justify-content-center bg-white" style="width: 100px; height: 100px; overflow: hidden; flex-shrink: 0;">
            @if ($fotoSubida)
                <img src="{{ $fotoSubida->temporaryUrl() }}" class="img-fluid">
            @elseif ($foto)
                <img src="{{ asset('storage/estilos/' . $foto) }}?v={{ time() }}" class="img-fluid">
            @else
                <i class="bi bi-image text-muted" style="font-size: 2rem;"></i>
            @endif
        </div>
        <div class="flex-grow-1">
            <label class="etiBase">Foto del Estilo (JPG/PNG)</label>
            <input type="file" wire:model="fotoSubida" class="form-control form-control-sm" accept="image/*" id="inputFoto">
            <div wire:loading wire:target="fotoSubida" class="text-primary small">Procesando imagen...</div>
            <div class="mt-1">
                @if ($fotoSubida)
                    <span class="badge bg-success">Nueva imagen lista</span>
                    <button type="button" class="btn btn-sm text-danger" wire:click="$set('fotoSubida', null)">
                        <i class="bi bi-x-circle"></i> Cancelar
                    </button>
                @elseif ($foto)
                    <span class="text-muted small">Imagen actual: {{ $foto }}</span>
                    <button type="button" class="btn btn-sm text-danger ms-2" wire:click="borrarFoto" onclick="confirm('¿Eliminar esta imagen permanentemente?') || event.stopImmediatePropagation()">
                        <i class="bi bi-trash"></i> Quitar
                    </button>
                @else
                    <span class="text-muted small">Puedes pegar (Ctrl+V) o seleccionar</span>
                @endif
            </div>
        </div>
    </div>
    @error('fotoSubida') <span class="text-danger small">{{ $message }}</span> @enderror
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