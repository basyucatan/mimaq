
@if($verPrecaptura)
    <div class="cardSec">
        <div class="cardSec-header" style="cursor: move;">
            <span>Add Materials based on Style</span>
            <button wire:click.prevent="cancel()" class="bot botNegro botChico">X</button>
        </div>
        <div class="cardSec-body" style="padding: 10px; max-height: 400px; overflow-y: auto;">
            <div class="row gx-1">
                <div class="col-12 col-md-3 col-lg-2">
                    <label class="etiChico">Customer</label>
                    <select wire:model="IdCliente" wire:change="ultimoConsecutivo" class="inpBase inpChico">
                        <option value=""></option>
                        @foreach ($clientes as $key => $value)
                            <option value="{{ $key }}">{{ $value }}</option>
                        @endforeach
                    </select>
                    @error('IdCliente') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
                <div class="col-12 col-md-3 col-lg-2">
                    <label class="etiChico">Order</label>
                    <input wire:model="orden" class="inpBase inpChico" tabindex="{{ $consecutivoAuto ? -1 : 0 }}">
                    @error('orden') <span class="error text-danger">{{ $message }}</span> @enderror
                </div>
                <div class="col-12 col-md-3 col-lg-2">
                    <label class="etiChico">Lot</label>
                    <input wire:model="lote" class="inpBase inpChico" tabindex="{{ $consecutivoAuto ? -1 : 0 }}">
                    @error('lote') <span class="error text-danger">{{ $message }}</span> @enderror
                </div>
                <div class="col-12 col-md-3 col-lg-2">
                    <label class="etiChico">Quantity</label>
                    <input wire:model="cantidadEstilo" type="text" class="inpBase inpChico"  onfocus="this.select()">
                    @error('cantidadEstilo') <span class="error text-danger">{{ $message }}</span> @enderror
                </div>
                <div class="col-12 col-md-3 col-lg-2">
                    <label class="etiChico">Style</label>
                    <select wire:model="IdEstilo" wire:change="$refresh" class="inpBase inpChico">
                        <option value=""></option>
                        @foreach ($estilos as $key => $value)
                            <option value="{{ $key }}">{{ $value }}</option>
                        @endforeach
                    </select>
                    @error('IdEstilo') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
                <div class="col-12 col-md-3 col-lg-2 text-end">
                    <button
                        type="button" class="bot botVerde"
                        onclick="if ({{ !empty($precaptura) ? 'true' : 'false' }}) 
                            {if (!confirm('¿Deseas borrar la info actual?')) return;}
                            @this.call('generarConEstilo');">
                        Generate
                    </button>
                    <button type="button" class="bot botAzul" wire:click="nuevoComponente" title="Agregar material">
                        <i class="bi bi-plus-circle"></i>
                    </button>
                    <button class="bot botNaranja" wire:click="agregar" title="agregar a factura">
                        <i class="fas fa-save"></i>
                    </button>
                </div>
            </div>
        </div>
        @include('livewire.facimportsdets.precaptura')
    </div>
@endif