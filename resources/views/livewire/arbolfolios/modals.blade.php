@if($tipoModal)
<div class="modal-overlay">
    <div x-data="{}" x-init="dragModal($el)" class="modal-dialog" wire:ignore.self>
        <div class="modal-content">
            <div class="cardPrin" style="cursor: move;">
                <div class="cardPrin-header">
                    <span>{{ $selected_id ? 'Editar' : 'Nuevo' }} {{ $tipoModal }}</span>
                </div>
                <div class="cardPrin-body" style="padding: 10px; max-height: 500px; overflow-y: auto;">
                    <form>
                        <div class="row g-2">
                            @if($tipoModal == 'Orden')
                                <div class="col-md-12">
                                    <label class="etiBase">Número de Orden</label>
                                    <input wire:model="orden" type="text" class="inpBase" onfocus="this.select()">
                                    @error('orden') <span class="inpBase">{{ $message }}</span> @enderror
                                </div>
                            @elseif($tipoModal == 'Lote')
                                <div class="col-md-12">
                                    <label class="etiBase">Nombre del Lote</label>
                                    <input wire:model="lote" type="text" class="inpBase" onfocus="this.select()">
                                    @error('lote') <span class="inpBase">{{ $message }}</span> @enderror
                                </div>
                            @elseif($tipoModal == 'Folio')
                                <div class="col-md-3">
                                    <label class="etiBase">Estilo</label>
                                    <select wire:model="IdEstilo" class="inpBase">
                                        <option value=""></option>
                                        @foreach ($estilos as $key => $value)
                                            <option value="{{ $key }}">{{ $value }}</option>
                                        @endforeach
                                    </select>
                                    @error('IdEstilo') <span class="inpBase">{{ $message }}</span> @enderror
                                </div>                               
                                <div class="col-md-3">
                                    <label class="etiBase">Piezas</label>
                                    <input wire:model="cantidad" type="number" class="inpBase">
                                    @error('cantidad') <span class="inpBase">{{ $message }}</span> @enderror
                                </div>
                                <div class="col-md-3">
                                    <label class="etiBase">Vencimiento</label>
                                    <input wire:model="fechaVen" type="date" class="inpBase">
                                    @error('fechaVen') <span class="inpBase">{{ $message }}</span> @enderror
                                </div>
                                <div class="col-md-3">
                                    <label class="etiBase">Bandejas</label>
                                    <input wire:model="totalBandejas" type="number" class="inpBase">
                                    @error('totalBandejas') <span class="inpBase">{{ $message }}</span> @enderror
                                </div>
                                <div class="col-md-3">
                                    <label class="etiBase">Job Style</label>
                                    <input wire:model="jobStyle" type="text" class="inpBase">
                                    @error('jobStyle') <span class="inpBase">{{ $message }}</span> @enderror
                                </div> 
                            @endif
                        </div>
                    </form>
                </div>
                <div class="cardPrin-footer mt-3 d-flex justify-content-end gap-2">
                    <button wire:click.prevent="cancel()" class="bot botNegro">Cerrar</button>
                    <button wire:click.prevent="guardar" class="bot botVerde">Guardar {{ $tipoModal }}</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endif