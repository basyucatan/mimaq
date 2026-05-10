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
                                    <label class="etiBase">Pesometalentrada</label>
                                    <input wire:model="pesoMetalEntrada" type="text" class="inpBase"  onfocus="this.select()">
                                    @error('pesoMetalEntrada') <span class="error text-danger">{{ $message }}</span> @enderror
                                </div>                            
                                <div class="col-md-6">
                                    <label class="etiBase">Pesometalsalida</label>
                                    <input wire:model="pesoMetalSalida" type="text" class="inpBase"  onfocus="this.select()">
                                    @error('pesoMetalSalida') <span class="error text-danger">{{ $message }}</span> @enderror
                                </div>                            
                                <div class="col-md-6">
                                    <label class="etiBase">Mermametal</label>
                                    <input wire:model="mermaMetal" type="text" class="inpBase"  onfocus="this.select()">
                                    @error('mermaMetal') <span class="error text-danger">{{ $message }}</span> @enderror
                                </div>                            
                                <div class="col-md-6">
                                    <label class="etiBase">Fechah</label>
                                    <input wire:model="fechaH" type="text" class="inpBase"  onfocus="this.select()">
                                    @error('fechaH') <span class="error text-danger">{{ $message }}</span> @enderror
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