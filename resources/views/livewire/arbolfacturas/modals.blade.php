@if($verModalFacimport)
    <div class="modal-overlay">
        <div x-data="{}" x-init="dragModal($el)" class="modal-dialog" wire:ignore.self>            
            <div class="modal-content">
                <div class="cardPrin" style="cursor: move;">
                    <div class="cardPrin-header">
                        <span>{{ $selected_id ? 'Edit Invoice' : 'Create Invoice' }}</span>
                    </div>
                    <div class="cardPrin-body" style="padding: 10px; max-height: 400px; overflow-y: auto;">
                        <form>
                            <div class="row gy-2">
                                @if ($selected_id)
                                    <input type="hidden" wire:model="selected_id">
                                @endif
                                <div class="col-md-4">
                                    <label class="etiBase">Invoice #</label>
                                    <input wire:model="factura" type="text" class="inpBase"  onfocus="this.select()">
                                    @error('factura') <span class="error text-danger">{{ $message }}</span> @enderror
                                </div>
                                <div class="col-md-4">
                                    <label class="etiBase">Date</label>
                                    <input wire:model="fecha" type="date" class="inpBase"  onfocus="this.select()">
                                    @error('fecha') <span class="error text-danger">{{ $message }}</span> @enderror
                                </div>                            
                                <div class="col-md-4">
                                    <label class="etiBase">Exchange Rate (USD/MXN)</label>
                                    <input wire:model="tipoCambio" type="text" class="inpBase"  onfocus="this.select()">
                                    @error('tipoCambio') <span class="error text-danger">{{ $message }}</span> @enderror
                                </div>
                                <div class="col-md-4">
                                    <label class="etiBase">Shipping Method</label>
                                    <input wire:model="viadE" type="text" class="inpBase" onfocus="this.select()">
                                    @error('viadE') <span class="error text-danger">{{ $message }}</span> @enderror
                                </div>
                                <div class="col-md-4">
                                    <label class="etiBase">Air Waybill (AWB)</label>
                                    <input wire:model="guiaA" type="text" class="inpBase"  onfocus="this.select()">
                                    @error('guiaA') <span class="error text-danger">{{ $message }}</span> @enderror
                                </div>
                                <div class="col-md-4">
                                    <label class="etiBase">Packages</label>
                                    <input wire:model="nPaq" type="text" class="inpBase"  onfocus="this.select()">
                                    @error('nPaq') <span class="error text-danger">{{ $message }}</span> @enderror
                                </div>
                                <div class="col-md-4 d-flex align-items-center justify-content-center">
                                    <div class="form-check form-switch shadow-sm p-2 rounded border" style="background-color: #f8f9fa; min-width: 150px;">
                                        <input wire:model.live="cerrado" class="form-check-input ms-0" type="checkbox" role="switch" id="switchEstatus" style="cursor: pointer; width: 2.5em; height: 1.25em;">
                                        <label class="form-check-label fw-bold mb-0 ms-2" for="switchEstatus" style="cursor: pointer; font-size: 0.9rem;">
                                            {{ $cerrado ? 'CLOSED' : 'OPEN' }}
                                        </label>
                                    </div>
                                </div>                             
                            </div>
                        </form>
                    </div>
                    <div class="cardPrin-footer mt-3 d-flex justify-content-end gap-2">
                        <button wire:click.prevent="cancel()" class="bot botNegro">Close</button>
                        <button wire:click.prevent="saveFactura" class="bot botVerde">Save</button>
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
                        <span>{{ $selected_id ? 'Edit Customs Entry' : 'Create Customs Entry' }}</span>
                    </div>
                    <div class="cardPrin-body" style="padding: 10px; max-height: 400px; overflow-y: auto;">
                        <form>
                            <div class="row">
                                @if ($selected_id)
                                    <input type="hidden" wire:model="selected_id">
                                @endif
                                <div class="col-md-6">
                                    <label for="pedimento" class="etiBase">Entry Number</label>
                                    <input wire:model="pedimento" type="text" class="inpBase"  onfocus="this.select()" id="pedimento">@error('pedimento') <span class="error text-danger">{{ $message }}</span> @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="fecha" class="etiBase">Date</label>
                                    <input wire:model="fecha" type="date" class="inpBase"  onfocus="this.select()" id="fecha">@error('fecha') <span class="error text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="cardPrin-footer mt-3 d-flex justify-content-end gap-2">
                        <button wire:click.prevent="cancel()" class="bot botNegro">Close</button>
                        <button wire:click.prevent="savePedimento" class="bot botVerde">Save</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif