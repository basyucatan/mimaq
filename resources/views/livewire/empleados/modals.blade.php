@if($verModalEmpleado)
    <div class="modal-overlay">
        <div x-data="{}" x-init="dragModal($el)" class="modal-dialog" wire:ignore.self>            
            <div class="modal-content">
                <div class="cardPrin" style="cursor: move;">
                    <div class="cardPrin-header">
                        <span>{{ $selected_id ? 'Editar Empleado' : 'Crear Empleado' }}</span>
                    </div>
                    <div class="cardPrin-body" style="padding: 10px; max-height: 400px; overflow-y: auto;">
                        <form gy-2>
                            <div class="row">
                                @if ($selected_id)
                                    <input type="hidden" wire:model="selected_id">
                                @endif

                                <div class="col-md-6">
                                    <label class="etiBase">Empleado</label>
                                    <input wire:model="empleado" type="text" class="inpBase"  onfocus="this.select()">
                                    @error('empleado') <span class="error text-danger">{{ $message }}</span> @enderror
                                </div>                            
                                <div class="col-md-6">
                                    <label class="etiBase">Depto</label>
                                    <select wire:model="IdDepto" class="inpBase">
                                        <option value=""></option>
                                        @foreach ($deptos as $key => $value)
                                            <option value="{{ $key }}">{{ $value }}</option>
                                        @endforeach
                                    </select>
                                    @error('IdDepto') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>                                                     
                                <div class="col-md-4 d-flex align-items-center justify-content-center">
                                    <div class="form-check form-switch shadow-sm p-2 rounded border" style="background-color: #f8f9fa; min-width: 150px;">
                                        <input wire:model.live="vigente" class="form-check-input ms-0" type="checkbox" role="switch" id="switchEstatus" style="cursor: pointer; width: 2.5em; height: 1.25em;">
                                        <label class="form-check-label fw-bold mb-0 ms-2" for="switchEstatus" style="cursor: pointer; font-size: 0.9rem;">
                                            {{ $vigente ? 'VIGENTE' : 'BAJA' }}
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