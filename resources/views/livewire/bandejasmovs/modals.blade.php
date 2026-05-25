@if($verModalBandejasmov)
    <div class="modal-overlay">
        <div x-data="{}" x-init="dragModal($el)" class="modal-dialog" wire:ignore.self>            
            <div class="modal-content">
                <div class="cardPrin" style="cursor: move;">
                    <div class="cardPrin-header">
                        <span>{{ $selected_id ? 'Editar Bandejasmov' : 'Crear Bandejasmov' }}</span>
                    </div>
                    <div class="cardPrin-body" style="padding: 10px; max-height: 400px; overflow-y: auto;">
                        <form gy-2>
                            <div class="row">
                                @if ($selected_id)
                                    <input type="hidden" wire:model="selected_id">
                                @endif

                                <div class="col-md-6">
                                    <label class="etiBase">Idbandeja</label>
                                    <input wire:model="IdBandeja" type="text" class="inpBase"  onfocus="this.select()">
                                    @error('IdBandeja') <span class="error text-danger">{{ $message }}</span> @enderror
                                </div>                            
                                <div class="col-md-6">
                                    <label class="etiBase">Idproceso</label>
                                    <input wire:model="IdProceso" type="text" class="inpBase"  onfocus="this.select()">
                                    @error('IdProceso') <span class="error text-danger">{{ $message }}</span> @enderror
                                </div>                            
                                <div class="col-md-6">
                                    <label class="etiBase">Idempleado</label>
                                    <input wire:model="IdEmpleado" type="text" class="inpBase"  onfocus="this.select()">
                                    @error('IdEmpleado') <span class="error text-danger">{{ $message }}</span> @enderror
                                </div>                            
                                <div class="col-md-6">
                                    <label class="etiBase">Pesoentrada</label>
                                    <input wire:model="pesoEntrada" type="text" class="inpBase"  onfocus="this.select()">
                                    @error('pesoEntrada') <span class="error text-danger">{{ $message }}</span> @enderror
                                </div>                            
                                <div class="col-md-6">
                                    <label class="etiBase">Pesosalida</label>
                                    <input wire:model="pesoSalida" type="text" class="inpBase"  onfocus="this.select()">
                                    @error('pesoSalida') <span class="error text-danger">{{ $message }}</span> @enderror
                                </div>                            
                                <div class="col-md-6">
                                    <label class="etiBase">Fechahentrada</label>
                                    <input wire:model="fechaHEntrada" type="text" class="inpBase"  onfocus="this.select()">
                                    @error('fechaHEntrada') <span class="error text-danger">{{ $message }}</span> @enderror
                                </div>                            
                                <div class="col-md-6">
                                    <label class="etiBase">Fechahsalida</label>
                                    <input wire:model="fechaHSalida" type="text" class="inpBase"  onfocus="this.select()">
                                    @error('fechaHSalida') <span class="error text-danger">{{ $message }}</span> @enderror
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