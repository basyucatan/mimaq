@if($verModalFacimport)
    <div class="modal-overlay">
        <div x-data="{}" x-init="dragModal($el)" class="modal-dialog" wire:ignore.self>            
            <div class="modal-content">
                <div class="cardPrin" style="cursor: move;">
                    <div class="cardPrin-header">
                        <span>{{ $selected_id ? 'Editar Factura' : 'Crear Factura' }}</span>
                    </div>
                    <div class="cardPrin-body" style="padding: 10px; max-height: 400px; overflow-y: auto;">
                        <form>
                            <div class="row gy-2">
                                @if ($selected_id)
                                    <input type="hidden" wire:model="selected_id">
                                @endif
                                <div class="col-md-4">
                                    <label for="factura" class="etiBase">Factura</label>
                                    <input wire:model="factura" type="text" class="inpBase"  onfocus="this.select()">
                                    @error('factura') <span class="error text-danger">{{ $message }}</span> @enderror
                                </div>
                                <div class="col-md-4">
                                    <label for="fecha" class="etiBase">Fecha</label>
                                    <input wire:model="fecha" type="date" class="inpBase"  onfocus="this.select()">
                                    @error('fecha') <span class="error text-danger">{{ $message }}</span> @enderror
                                </div>                            
                                <div class="col-md-4">
                                    <label for="tipoCambio" class="etiBase">Tipo de cambio</label>
                                    <input wire:model="tipoCambio" type="text" class="inpBase"  onfocus="this.select()">
                                    @error('tipoCambio') <span class="error text-danger">{{ $message }}</span> @enderror
                                </div>
                                <div class="col-md-4">
                                    <label for="viadE" class="etiBase">Via de Embarque</label>
                                    <input wire:model="viadE" type="text" class="inpBase" onfocus="this.select()">
                                    @error('viadE') <span class="error text-danger">{{ $message }}</span> @enderror
                                </div>
                                <div class="col-md-4">
                                    <label for="guiaA" class="etiBase">Guia Aérea</label>
                                    <input wire:model="guiaA" type="text" class="inpBase"  onfocus="this.select()">
                                    @error('guiaA') <span class="error text-danger">{{ $message }}</span> @enderror
                                </div>
                                <div class="col-md-4">
                                    <label for="nPaq" class="etiBase"># Paq.</label>
                                    <input wire:model="nPaq" type="text" class="inpBase"  onfocus="this.select()">
                                    @error('nPaq') <span class="error text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="cardPrin-footer mt-3 d-flex justify-content-end gap-2">
                        <button wire:click.prevent="cancel()" class="bot botNegro">Cerrar</button>
                        <button wire:click.prevent="saveFactura" class="bot botVerde">Guardar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif

@if($verModalPedimento)
    <div class="modal-overlay">
        <div x-data="{}" x-init="dragModal($el)" class="modal-dialog" wire:ignore.self>            
            <div class="modal-content">
                <div class="cardPrin" style="cursor: move;">
                    <div class="cardPrin-header">
                        <span>{{ $selected_id ? 'Editar Pedimento' : 'Crear Pedimento' }}</span>
                    </div>
                    <div class="cardPrin-body" style="padding: 10px; max-height: 400px; overflow-y: auto;">
                        <form>
                            <div class="row">
                                @if ($selected_id)
                                    <input type="hidden" wire:model="selected_id">
                                @endif
                                <div class="col-md-6">
                                    <label for="pedimento" class="etiBase">Pedimento</label>
                                    <input wire:model="pedimento" type="text" class="inpBase"  onfocus="this.select()" id="pedimento">@error('pedimento') <span class="error text-danger">{{ $message }}</span> @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="fecha" class="etiBase">Fecha</label>
                                    <input wire:model="fecha" type="date" class="inpBase"  onfocus="this.select()" id="fecha">@error('fecha') <span class="error text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="cardPrin-footer mt-3 d-flex justify-content-end gap-2">
                        <button wire:click.prevent="cancel()" class="bot botNegro">Cerrar</button>
                        <button wire:click.prevent="savePedimento" class="bot botVerde">Guardar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif