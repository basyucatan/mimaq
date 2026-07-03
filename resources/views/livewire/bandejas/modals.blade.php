@if($verModalBandeja)
    <div class="modal-overlay">
        <div x-data="{}" x-init="dragModal($el)" class="modal-dialog" wire:ignore.self>           
            <div class="modal-content">
                <div class="cardPrin">
                    <div class="cardPrin-header" style="cursor: move;">
                        <span>{{ $selected_id ? 'Editar Bandeja '.$codigoBandeja : 'Crear Bandeja' }} </span>
                    </div>
                    <div class="cardPrin-body" style="padding: 10px; max-height: 400px; overflow-y: auto;">
                        <form gy-2>
                            <div class="row">
                                @if ($selected_id)
                                    <input type="hidden" wire:model="selected_id">
                                @endif
                                <div class="col-md-6">
                                    <label class="etiBase">Peso Casting Inicial</label>
                                    <input wire:model="castingIni" type="text" class="inpBase" onfocus="this.select()">
                                    @error('castingIni') <span class="error text-danger">{{ $message }}</span> @enderror
                                </div>            
                                <div class="col-md-6">
                                    <label class="etiBase">Peso Casting final</label>
                                    <input wire:model="castingFin" type="text" class="inpBase" onfocus="this.select()">
                                    @error('castingFin') <span class="error text-danger">{{ $message }}</span> @enderror
                                </div>            
                                <div class="col-md-6">
                                    <label class="etiBase">Peso Piedras</label>
                                    <input wire:model="piedrasG" type="text" class="inpBase" onfocus="this.select()">
                                    @error('piedrasG') <span class="error text-danger">{{ $message }}</span> @enderror
                                </div>            
                                <div class="col-md-6">
                                    <label class="etiBase">Peso Diamantes</label>
                                    <input wire:model="diamantesG" type="text" class="inpBase" onfocus="this.select()">
                                    @error('diamantesG') <span class="error text-danger">{{ $message }}</span> @enderror
                                </div>            
                                <div class="col-md-6">
                                    <label class="etiBase">Peso Miscelaneo</label>
                                    <input wire:model="miscG" type="text" class="inpBase" onfocus="this.select()">
                                    @error('miscG') <span class="error text-danger">{{ $message }}</span> @enderror
                                </div>
                                <div class="col-md-3 d-flex align-items-center justify-content-center">
                                    <div class="form-check form-switch shadow-sm p-2 rounded border" style="background-color: #f8f9fa; min-width: 150px;">
                                        <input wire:model.live="enBoveda" class="form-check-input ms-0" type="checkbox" role="switch" id="switchEstatus" style="cursor: pointer; width: 2.5em; height: 1.25em;">
                                        <label class="form-check-label fw-bold mb-0 ms-2" for="switchEstatus" style="cursor: pointer; font-size: 0.9rem;">
                                            {{ $enBoveda ? 'Bóveda' : 'Producción' }}
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-3 d-flex align-items-center justify-content-center">
                                    <div class="form-check form-switch shadow-sm p-2 rounded border" style="background-color: #f8f9fa; min-width: 150px;">
                                        <input wire:model.live="habilitada" class="form-check-input ms-0" type="checkbox" role="switch" id="switchEstatus" style="cursor: pointer; width: 2.5em; height: 1.25em;">
                                        <label class="form-check-label fw-bold mb-0 ms-2" for="switchEstatus" style="cursor: pointer; font-size: 0.9rem;">
                                            {{ $habilitada ? 'Habilitada' : 'Inhabilitada' }}
                                        </label>
                                    </div>
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
@if($verModalDividir)
    <div class="modal fade show d-block" tabindex="-1" style="background-color: rgba(0,0,0,0.5);">
        <div x-data="{}" x-init="dragModal($el)" class="modal-dialog modal-dialog-centered" wire:ignore.self>
            <div class="modal-content border-0 bg-transparent">
                <div class="cardPrin">
                    <div class="cardPrin-header" style="cursor: move;">
                        <span>Dividir Bandeja {{$codigoBandeja ?? ''}}</span>
                    </div>
                    <div class="cardPrin-body p-3">
                        <p class="text-muted mb-3">
                            Indica cuántas piezas se asignarán a la **nueva bandeja**..
                        </p>
                        <div class="mb-3">
                            <label class="etiBase">Piezas Totales Actuales</label>
                            <input type="text" class="inpBase bg-light text-center fw-bold" value="{{ $cantidad }}" disabled>
                        </div>
                        <div class="mb-3">
                            <label class="etiBase">Piezas para la Nueva Bandeja</label>
                            <input type="number" wire:model.defer="piezasADividir" class="inpBase text-center" min="1" max="{{ $cantidad - 1 }}">
                            @error('piezasADividir') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="cardPrin-footer d-flex justify-content-end gap-2">
                        <button type="button" wire:click="cancel" class="bot botNegro botChico">Cancelar</button>
                        <button type="button" wire:click="procesarDivision" class="bot botAzul botChico">Confirmar División</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif
@if($verModalUnir)
    <div class="modal fade show d-block" tabindex="-1" style="background-color: rgba(0,0,0,0.5);">
        <div x-data="{}" x-init="dragModal($el)" class="modal-dialog modal-dialog-centered" wire:ignore.self>
            <div class="modal-content border-0 bg-transparent">
                <div class="cardPrin">
                    <div class="cardPrin-header" style="cursor: move;">
                        <span>Unir Bandeja Actual {{$codigoBandeja ?? ''}}</span>
                    </div>
                    <div class="cardPrin-body p-3">
                        <p class="text-muted small mb-3">
                            La bandeja actual se fusionará con la que elijas abajo.
                        </p>
                        <div class="mb-3">
                            <label class="etiBase">Selecciona la Bandeja Destino</label>
                            <select wire:model.defer="IdBandejaDestino" class="inpBase">
                                <option value="">-- Seleccionar bandeja compatible --</option>
                                @foreach($bandejasCompatibles as $b)
                                    <option value="{{ $b->id }}">
                                        ID: {{ $b->id }} | {{ $b->cantidad }} pzs | Casting F: {{ $b->castingFinal }}g
                                    </option>
                                @endforeach
                            </select>
                            @error('IdBandejaDestino') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="cardPrin-footer d-flex justify-content-end gap-2">
                        <button type="button" wire:click="cancel" class="bot botNegro botChico">Cancelar</button>
                        <button type="button" wire:click="procesarUnion" class="bot botVerde botChico">Confirmar Fusión</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif
@if($verModalTraspaso)
    <div class="modal fade show d-block" tabindex="-1" style="background: rgba(0,0,0,0.5);">
        <div x-data="{}" x-init="dragModal($el)" class="modal-dialog modal-dialog-centered" wire:ignore.self>
            <div class="modal-content border-0 bg-transparent">
                <div class="cardPrin">
                    <div class="cardPrin-header d-flex justify-content-between align-items-center" style="cursor: move;">
                        <span>Traspasar Bandeja {{ $codigoBandeja }}</span>
                        <button type="button" class="btn-close btn-close-white" wire:click="cerrarModalTraspaso()"></button>
                    </div>
                    <div class="cardPrin-body p-3">
                        <div class="row g-3">
<div class="col-md-6">
    <label class="{{ $esSalida ? 'etiLectura' : 'etiBase' }}">Proceso</label>
    <select wire:model="idProcesoDestino" class="inpBase" @disabled($esSalida)>
        <option value=""></option>
        @foreach ($procesos as $key => $value)
            <option value="{{ $key }}">{{ $value }}</option>
        @endforeach
    </select>
    @error('idProcesoDestino') <span class="text-danger">{{ $message }}</span> @enderror
</div>

<div class="col-md-3">
    <label class="etiBase">Empleado</label>
    <input type="number" wire:model="empTraspaso" class="inpBase">
    @error('empTraspaso') <span class="text-danger">{{ $message }}</span> @enderror
</div>

<div class="col-md-3">
    <label class="etiBase">Registrador</label>
    <input type="number" wire:model="regTraspaso" class="inpBase">
    @error('regTraspaso') <span class="text-danger">{{ $message }}</span> @enderror
</div>

<div class="col-6">
    <label class="{{ $esSalida ? 'etiLectura' : 'etiBase' }}">Peso Entrada (g)</label>
    <input type="number" wire:model="pesoEntrada" class="inpBase" @readonly($esSalida)>
</div>

<div class="col-6">
    <label class="{{ !$esSalida ? 'etiLectura' : 'etiBase' }}">Peso Salida (g)</label>
    <input type="number" wire:model="pesoSalida" class="inpBase" @readonly(!$esSalida)>
    @error('pesoSalida') <span class="text-danger">{{ $message }}</span> @enderror
</div>
                        </div>
                    </div>
                    <div class="cardPrin-footer d-flex justify-content-end gap-2">
                        <button type="button" class="bot botNegro botChico" wire:click="cerrarModalTraspaso()">Cancelar</button>
                        <button type="button" class="bot botVerde botChico" wire:click="guardarTraspaso()">Confirmar Traspaso</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif
@if($verModalHistorial)
    <div class="modal fade show d-block" tabindex="-1" style="background: rgba(0,0,0,0.5);">
        <div x-data="{}" x-init="dragModal($el)" class="modal-dialog modal-xl modal-dialog-centered" wire:ignore.self>
            <div class="modal-content border-0 bg-transparent">
                <div class="cardPrin">
                    <div class="cardPrin-header" style="cursor: move;">
                        <span>Historial de Movimientos</span>
                    </div>
                    <div class="cardPrin-body p-3">
                        @if($selected_id)
                            @livewire('bandejasmovs', ['IdBandeja' => $selected_id], key('movs-'.$selected_id))
                        @else
                            <div class="p-5 text-center text-muted">
                                <span>No se ha seleccionado ninguna bandeja</span>
                            </div>
                        @endif
                    </div>
                    <div class="cardPrin-footer d-flex justify-content-end">
                        <button type="button" class="bot botNegro botChico" wire:click="cerrarModalHistorial()">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif