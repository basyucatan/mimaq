@if($verModalBandeja)
    <div class="modal-overlay">
        <div x-data="{}" x-init="dragModal($el)" class="modal-dialog" wire:ignore.self>            
            <div class="modal-content">
                <div class="cardPrin" style="cursor: move;">
                    <div class="cardPrin-header">
                        <span>{{ $selected_id ? 'Editar Bandeja' : 'Crear Bandeja' }}</span>
                    </div>
                    <div class="cardPrin-body" style="padding: 10px; max-height: 400px; overflow-y: auto;">
                        <form gy-2>
                            <div class="row">
                                @if ($selected_id)
                                    <input type="hidden" wire:model="selected_id">
                                @endif
                                <div class="col-md-6">
                                    <label class="etiBase">Cantidad</label>
                                    <input wire:model="cantidad" type="text" class="inpBase"  onfocus="this.select()">
                                    @error('cantidad') <span class="error text-danger">{{ $message }}</span> @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="etiBase">Castinginicial</label>
                                    <input wire:model="castingInicial" type="text" class="inpBase"  onfocus="this.select()">
                                    @error('castingInicial') <span class="error text-danger">{{ $message }}</span> @enderror
                                </div>            
                                <div class="col-md-6">
                                    <label class="etiBase">Castingfinal</label>
                                    <input wire:model="castingFinal" type="text" class="inpBase"  onfocus="this.select()">
                                    @error('castingFinal') <span class="error text-danger">{{ $message }}</span> @enderror
                                </div>            
                                <div class="col-md-6">
                                    <label class="etiBase">Piedras</label>
                                    <input wire:model="piedras" type="text" class="inpBase"  onfocus="this.select()">
                                    @error('piedras') <span class="error text-danger">{{ $message }}</span> @enderror
                                </div>            
                                <div class="col-md-6">
                                    <label class="etiBase">Diamantes</label>
                                    <input wire:model="diamantes" type="text" class="inpBase"  onfocus="this.select()">
                                    @error('diamantes') <span class="error text-danger">{{ $message }}</span> @enderror
                                </div>            
                                <div class="col-md-6">
                                    <label class="etiBase">Miscelaneo</label>
                                    <input wire:model="miscelaneo" type="text" class="inpBase"  onfocus="this.select()">
                                    @error('miscelaneo') <span class="error text-danger">{{ $message }}</span> @enderror
                                </div>            
                                <div class="col-md-6">
                                    <label class="etiBase">Idprocesoactual</label>
                                    <input wire:model="IdProcesoActual" type="text" class="inpBase"  onfocus="this.select()">
                                    @error('IdProcesoActual') <span class="error text-danger">{{ $message }}</span> @enderror
                                </div>            
                                <div class="col-md-6">
                                    <label class="etiBase">Enboveda</label>
                                    <input wire:model="enBoveda" type="text" class="inpBase"  onfocus="this.select()">
                                    @error('enBoveda') <span class="error text-danger">{{ $message }}</span> @enderror
                                </div>            
                                <div class="col-md-6">
                                    <label class="etiBase">Habilitada</label>
                                    <input wire:model="habilitada" type="text" class="inpBase"  onfocus="this.select()">
                                    @error('habilitada') <span class="error text-danger">{{ $message }}</span> @enderror
                                </div>            
                                <div class="col-md-6">
                                    <label class="etiBase">Estatus</label>
                                    <input wire:model="estatus" type="text" class="inpBase"  onfocus="this.select()">
                                    @error('estatus') <span class="error text-danger">{{ $message }}</span> @enderror
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
            <div class="modal-content border-0 shadow-lg cardPrin" style="cursor: move;">
                <div class="cardPrin-header bg-dark text-white py-2">
                    <h5 class="modal-title fs-6 m-0">Dividir Bandeja (ID: {{ $selected_id }})</h5>
                </div>
                <div class="modal-content-body p-3">
                    <p class="text-muted small mb-3">
                        Indica cuántas piezas se asignarán a la **nueva bandeja**. Los pesos e historial de movimientos previos se recalcularán de forma proporcional y equitativa.
                    </p>
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-secondary">Piezas Totales Actuales:</label>
                        <input type="text" class="form-control bg-light text-center fw-bold" value="{{ $cantidad }}" disabled>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-dark">Piezas para la Nueva Bandeja:</label>
                        <input type="number" wire:model.defer="piezasADividir" class="form-control text-center" min="1" max="{{ $cantidad - 1 }}">
                        @error('piezasADividir') <span class="text-danger small">{{ $message }}</span> @enderror
                    </div>
                    <div class="d-flex justify-content-end gap-2 mt-4">
                        <button type="button" wire:click="cancel" class="bot botNegro">Cancelar</button>
                        <button type="button" wire:click="procesarDivision" class="bot botAzul">Confirmar División</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif
@if($verModalUnir)
    <div class="modal fade show d-block" tabindex="-1" style="background-color: rgba(0,0,0,0.5);">
        <div x-data="{}" x-init="dragModal($el)" class="modal-dialog modal-dialog-centered" wire:ignore.self>
            <div class="modal-content border-0 shadow-lg cardPrin" style="cursor: move;">
                <div class="cardPrin-header bg-dark text-white py-2">
                    <h5 class="modal-title fs-6 m-0">Unir Bandeja Actual</h5>
                </div>
                <div class="modal-content-body p-3">
                    <p class="text-muted small mb-3">
                        La bandeja actual se fusionará con la que elijas abajo. El ID seleccionado absorberá todos los pesos, piezas e historiales, eliminando el registro de origen de manera controlada.
                    </p>
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-dark">Selecciona la Bandeja Destino:</label>
                        <select wire:model.defer="IdBandejaDestino" class="form-select">
                            <option value="">-- Seleccionar bandeja compatible --</option>
                            @foreach($bandejasCompatibles as $b)
                                <option value="{{ $b->id }}">
                                    ID: {{ $b->id }} | {{ $b->cantidad }} pzs | Casting F: {{ $b->castingFinal }}g
                                </option>
                            @endforeach
                        </select>
                        @error('IdBandejaDestino') <span class="text-danger small">{{ $message }}</span> @enderror
                    </div>
                    <div class="d-flex justify-content-end gap-2 mt-4">
                        <button type="button" wire:click="cancel" class="bot botNegro">Cancelar</button>
                        <button type="button" wire:click="procesarUnion" class="bot botVerde">Confirmar Fusión</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif
@if($verModalTraspaso)
    <div class="modal fade show d-block" tabindex="-1" style="background: rgba(0,0,0,0.5);">
        <div x-data="{}" x-init="dragModal($el)" class="modal-dialog modal-dialog-centered" wire:ignore.self>
            <div class="modal-content cardPrin" style="cursor: move;">
                <div class="cardPrin-header d-flex justify-content-between align-items-center">
                    <span>Traspasar Bandeja {{ $codigoBandeja }}</span>
                    <button type="button" class="btn-close btn-close-white" wire:click="cerrarModalTraspaso()"></button>
                </div>
                <div class="modal-body p-3">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="etiBase">Proceso Destino</label>
                            <select wire:model="idProcesoDestino" class="inpBase">
                                <option value=""></option>
                                @foreach ($procesos as $key => $value)
                                    <option value="{{ $key }}">{{ $value }}</option>
                                @endforeach
                            </select>
                            @error('idProcesoDestino') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="etiBase">Empleado</label>
                            <select wire:model="idEmpleadoTraspaso" class="inpBase">
                                <option value=""></option>
                                @foreach ($empleados as $key => $value)
                                    <option value="{{ $key }}">{{ $value }}</option>
                                @endforeach
                            </select>
                            @error('idEmpleadoTraspaso') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="col-6">
                            <label class="etiBase">Peso Entrada (g)</label>
                            <input type="number" step="0.0001" wire:model="pesoEntradaTraspaso" class="inpSolo">
                        </div>
                        <div class="col-6">
                            <label class="etiBase">Peso Salida Proceso Anterior (g)</label>
                            <input type="number" step="0.0001" wire:model="pesoSalidaTraspaso" class="inpSolo">
                        </div>
                    </div>
                </div>
                <div class="modal-footer d-flex justify-content-end gap-2 p-2 border-0">
                    <button type="button" class="bot botNegro" wire:click="cerrarModalTraspaso()">Cancelar</button>
                    <button type="button" class="bot botVerde" wire:click="guardarTraspaso()">Confirmar Traspaso</button>
                </div>
            </div>
        </div>
    </div>
@endif
@if($verModalHistorial)
    <div class="modal fade show d-block" tabindex="-1" style="background: rgba(0,0,0,0.5);">
        <div x-data="{}" x-init="dragModal($el)" class="modal-dialog modal-xl modal-dialog-centered" wire:ignore.self>
            <div class="modal-content cardPrin" style="cursor: move;">
                <div class="cardPrin-body">
                    @if($selected_id)
                        @livewire('bandejasmovs', ['IdBandeja' => $selected_id], key('movs-'.$selected_id))
                    @else
                        <div class="p-5 text-center text-muted">
                            <span>No se ha seleccionado ninguna bandeja</span>
                        </div>
                    @endif
                </div>
                <div class="cardPrin-footer">
                    <button type="button" class="bot botNegro" wire:click="cerrarModalHistorial()">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
@endif