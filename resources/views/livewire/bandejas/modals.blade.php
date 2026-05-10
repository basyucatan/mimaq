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
                                    <label class="etiBase">Idfolio</label>
                                    <input wire:model="IdFolio" type="text" class="inpBase"  onfocus="this.select()">
                                    @error('IdFolio') <span class="error text-danger">{{ $message }}</span> @enderror
                                </div>                            
                                <div class="col-md-6">
                                    <label class="etiBase">Idfacturaexport</label>
                                    <input wire:model="IdFacturaExport" type="text" class="inpBase"  onfocus="this.select()">
                                    @error('IdFacturaExport') <span class="error text-danger">{{ $message }}</span> @enderror
                                </div>                            
                                <div class="col-md-6">
                                    <label class="etiBase">Cantidad</label>
                                    <input wire:model="cantidad" type="text" class="inpBase"  onfocus="this.select()">
                                    @error('cantidad') <span class="error text-danger">{{ $message }}</span> @enderror
                                </div>                            
                                <div class="col-md-6">
                                    <label class="etiBase">Pesometalinicial</label>
                                    <input wire:model="pesoMetalInicial" type="text" class="inpBase"  onfocus="this.select()">
                                    @error('pesoMetalInicial') <span class="error text-danger">{{ $message }}</span> @enderror
                                </div>                            
                                <div class="col-md-6">
                                    <label class="etiBase">Pesometalactual</label>
                                    <input wire:model="pesoMetalActual" type="text" class="inpBase"  onfocus="this.select()">
                                    @error('pesoMetalActual') <span class="error text-danger">{{ $message }}</span> @enderror
                                </div>                            
                                <div class="col-md-6">
                                    <label class="etiBase">Pesopiedrasconstante</label>
                                    <input wire:model="pesoPiedrasConstante" type="text" class="inpBase"  onfocus="this.select()">
                                    @error('pesoPiedrasConstante') <span class="error text-danger">{{ $message }}</span> @enderror
                                </div>                            
                                <div class="col-md-6">
                                    <label class="etiBase">Mermametalacumulada</label>
                                    <input wire:model="mermaMetalAcumulada" type="text" class="inpBase"  onfocus="this.select()">
                                    @error('mermaMetalAcumulada') <span class="error text-danger">{{ $message }}</span> @enderror
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