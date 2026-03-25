@if($verModalDestino)
    <div class="modal-overlay">
        <div x-data="{}" x-init="dragModal($el)" class="modal-dialog" style="width: 80%;" wire:ignore.self>
            <div class="modal-content">
                <div class="cardPrin" style="cursor: move;">
                    <div class="cardPrin-header d-flex justify-content-between align-items-center">
                    <h5 class="m-0">Confirmar Destino</h5>
                    <button wire:click="$set('verModalDestino', false)" type="button" class="btn-close"></button>
                </div>
                <div class="cardPrin-body" style="padding: 20px;">
                    <div class="form-group mb-3">
                        <label class="etiBase">Bodega de Destino</label>
                        <select wire:model="IdBodega" class="inpBase">
                            @foreach($bodegas as $id => $nombre)
                                <option value="{{ $id }}">{{ $nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="etiBase">Departamento</label>
                        <select wire:model="IdDepto" class="inpBase">
                            @foreach($deptos as $id => $nombre)
                                <option value="{{ $id }}">{{ $nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="cardPrin-footer d-flex justify-content-end gap-2">
                    <a wire:click="$set('verModalDestino', false)" class="bot botCancel">Cancelar</a>
                    <a wire:click.prevent="confirmarDestino()" class="bot botVerde">Guardar</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endif