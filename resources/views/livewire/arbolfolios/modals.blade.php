@if ($tipoModal)
    <div class="modal-overlay">
        <div x-data="{}" x-init="dragModal($el)" class="modal-dialog" wire:ignore.self>
            <div class="modal-content">
                <div class="cardPrin">
                    <div class="cardPrin-header" style="cursor: move;">
                        <span>{{ $selected_id ? 'Editar' : 'Nuevo' }} {{ $tipoModal }}</span>
                    </div>
                    <div class="cardPrin-body" style="padding: 10px; max-height: 500px; overflow-y: auto;">
                        <form>
                            <div class="row g-2">
                                @if ($tipoModal == 'Orden')
                                    <div class="col-md-6">
                                        <label class="etiBase">Customer</label>
                                        <input wire:model.live="cliente" list="listaClientes" type="text"
                                            class="inpBase" onfocus="this.select()" autocomplete="off">
                                        <datalist id="listaClientes">
                                            @foreach ($clientes as $id => $nombre)
                                                <option value="{{ $nombre }}">
                                            @endforeach
                                        </datalist>
                                        @error('IdCliente')
                                            <span class="error text-danger">Seleccione un cliente válido</span>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label class="etiBase">Número de Orden</label>
                                        <input wire:model="orden" type="text" class="inpBase"
                                            onfocus="this.select()">
                                        @error('orden')
                                            <span class="inpBase">{{ $message }}</span>
                                        @enderror
                                    </div>
                                @elseif($tipoModal == 'Lote')
                                    <div class="col-md-12">
                                        <label class="etiBase">Número de Lote</label>
                                        <input wire:model="lote" type="text" class="inpBase" onfocus="this.select()">
                                        @error('lote')
                                            <span class="inpBase">{{ $message }}</span>
                                        @enderror
                                    </div>
                                @elseif($tipoModal == 'Folio')
                                    <div class="col-md-2">
                                        <label class="etiLectura">Folio</label>
                                        <input wire:model="selected_id" type="number" class="inpBase" disabled>
                                    </div>
                                    <div class="col-md-2">
                                        <label class="etiBase">Piezas</label>
                                        <input wire:model="cantidad" wire:change="generarDef"type="number"
                                            class="inpBase">
                                        @error('cantidad')
                                            <span class="inpBase">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-md-3">
                                        <label class="etiBase">Estilo</label>
                                        <select wire:model="IdEstilo" wire:change="generarDef" class="inpBase">
                                            <option value=""></option>
                                            @foreach ($estilos as $key => $value)
                                                <option value="{{ $key }}">{{ $value }}</option>
                                            @endforeach
                                        </select>
                                        @error('IdEstilo')
                                            <span class="inpBase">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-md-2">
                                        <label class="etiBase">Bandejas</label>
                                        <input wire:model="totalBandejas" type="number" class="inpBase">
                                        @error('totalBandejas')
                                            <span class="inpBase">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-md-3">
                                        <label class="etiBase">Vencimiento</label>
                                        <input wire:model="fechaVen" type="date" class="inpBase">
                                        @error('fechaVen')
                                            <span class="inpBase">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-md-3">
                                        <label class="etiBase d-block">Kilataje</label>
                                        <div class="btn-group w-100" role="group">
                                            @foreach ($kts as $k)
                                                <input type="radio" class="btn-check" wire:model="kt"
                                                    wire:change="generarDef" value="{{ $k }}"
                                                    id="kt{{ $k }}" name="radioKt" autocomplete="off">
                                                <label class="btn btn-outline-secondary btn-sm pt-1"
                                                    for="kt{{ $k }}" style="font-size: 11px;">
                                                    {{ $k }}
                                                </label>
                                            @endforeach
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <label class="etiBase d-block">Color</label>
                                        <div class="btn-group w-100" role="group">
                                            @foreach ($colors as $c)
                                                <input type="radio" class="btn-check" wire:model="color"
                                                    wire:change="generarDef" value="{{ $c }}"
                                                    id="col{{ $c }}" name="radioColor" autocomplete="off">
                                                <label class="btn btn-outline-secondary btn-sm pt-1"
                                                    for="col{{ $c }}" style="font-size: 11px;">
                                                    {{ $c }}
                                                </label>
                                            @endforeach
                                        </div>
                                    </div>
                                    <div class="col-md-7">
                                        <label class="etiBase">Alerta General</label>
                                        <input wire:model="alertas.alertaGeneral" type="text" class="inpBase" onfocus="this.select()">
                                        @error('alertas.alertaGeneral') <span class="error text-danger">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="col-md-2">
                                        <label class="etiBase">Penalty</label>
                                        <div class="form-check form-switch mt-1">
                                            <input wire:model="alertas.penalty" class="form-check-input {{ ($alertas['penalty'] ?? false) ? 'bg-success' : 'bg-primary' }} border-0" type="checkbox" role="switch" id="swPenalty" style="cursor: pointer;">
                                        </div>
                                        @error('alertas.penalty') <span class="error text-danger">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="col-md-2">
                                        <label class="etiBase">Rush</label>
                                        <div class="form-check form-switch mt-1">
                                            <input wire:model="alertas.rush" class="form-check-input {{ ($alertas['rush'] ?? false) ? 'bg-success' : 'bg-primary' }} border-0" type="checkbox" role="switch" id="swRush" style="cursor: pointer;">
                                        </div>
                                        @error('alertas.rush') <span class="error text-danger">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="col-md-3">
                                        <label class="etiLectura">Job Style</label>
                                        <input wire:model="jobStyle" type="text" class="inpBase">
                                    </div>
                                    <div class="col-md-5">
                                        <label class="etiLectura">Producto Final</label>
                                        <input wire:model="productoFinal" type="text" class="inpBase" disabled>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="etiLectura">Descripción</label>
                                        <input wire:model="abreviatura" type="text" class="inpBase" disabled>
                                    </div>
                                    <div class="col-md-8">
                                        <label class="etiLectura">Composición Simplificada</label>
                                        <div class="inpBase"
                                            style="background: #f8f9fa; min-height: 31px; height: auto; padding: 5px;">
                                            @if (isset($adicionales['composicion']) && is_array($adicionales['composicion']))
                                                @foreach ($adicionales['composicion'] as $idMat => $detalle)
                                                    <span class="badge bg-warning text-black mb-1"
                                                        title="ID Material: {{ $idMat }}">
                                                        {{ $detalle['cantidad'] }} - {{ $detalle['tipo'] }}
                                                    </span>
                                                    @if (!$loop->last)
                                                        |
                                                    @endif
                                                @endforeach
                                            @else
                                                <span class="text-muted small">Sin materiales adicionales</span>
                                            @endif
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </form>
                        @if ($IdEstilo)
                            <div class="cardSec">
                                <div class="cardSec-body">
                                    @livewire('estilosdets', ['IdEstilo' => $IdEstilo], key('estilosdets-' . $IdEstilo))
                                </div>
                            </div>
                        @endif
                    </div>
                    <div class="cardPrin-footer mt-3 d-flex justify-content-end gap-2">
                        <button wire:click.prevent="cancel()" class="bot botNegro">Cerrar</button>
                        <button wire:click.prevent="guardar" class="bot botVerde">Guardar
                            {{ $tipoModal }}</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif
