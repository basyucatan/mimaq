@if($verModalFactura)
    <div class="modal-overlay">
        <div x-data="{}" x-init="dragModal($el)" class="modal-dialog" wire:ignore.self>            
            <div class="modal-content">
                <div class="cardPrin">
                    <div class="cardPrin-header" style="cursor: move;">
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
                                    <label class="etiBase">Shipping Via</label>
                                    <input wire:model="adicionales.viadE" type="text" class="inpBase" onfocus="this.select()">
                                    @error('adicionales.viadE') <span class="error text-danger">{{ $message }}</span> @enderror
                                </div>
                                <div class="col-md-4">
                                    <label class="etiBase">Packages</label>
                                    <input wire:model="adicionales.nPaq" type="text" class="inpBase"  onfocus="this.select()">
                                    @error('adicionales.nPaq') <span class="error text-danger">{{ $message }}</span> @enderror
                                </div>
                                @if(auth()->user()->nivelMinimo < 4)
                                    <div class="col-md-4 d-flex align-items-center justify-content-center">
                                        <div class="form-check form-switch shadow-sm p-2 rounded border" style="background-color: #f8f9fa; min-width: 150px;">
                                            <input wire:model.live="cerrado" class="form-check-input ms-0" type="checkbox" role="switch" id="switchEstatus" style="cursor: pointer; width: 2.5em; height: 1.25em;">
                                            <label class="form-check-label fw-bold mb-0 ms-2" for="switchEstatus" style="cursor: pointer; font-size: 0.9rem;">
                                                {{ $cerrado ? 'Closed (edits not allowed)' : 'Open (allow edits)' }}
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="etiBase">Invoice Amount Limit</label>
                                        <input wire:model="adicionales.limiteFactura" type="text" class="inpBase"  onfocus="this.select()">
                                        @error('adicionales.limiteFactura') <span class="error text-danger">{{ $message }}</span> @enderror
                                    </div>
                                @endif
                                <div class="col-12 mt-3">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <label class="etiBase mb-0">Air Waybills (AWB)</label>
                                        <button type="button" wire:click="agregarGuia" class="btn btn-sm btn-secondary py-0 px-2">+</button>
                                    </div>
                                    <div class="row g-2">
                                        @foreach($guias as $indice => $guia)
                                            <div class="col-md-4 d-flex gap-1">
                                                <div class="w-100">
                                                    <input wire:model="guias.{{ $indice }}" type="text" class="inpBase" onfocus="this.select()" placeholder="AWB #{{ $indice + 1 }}">
                                                    @error("guias.$indice") <span class="error text-danger small">{{ $message }}</span> @enderror
                                                </div>
                                                <button type="button" wire:click="removerGuia({{ $indice }})" class="btn btn-sm btn-outline-danger px-2" style="height: 31px;">&times;</button>
                                            </div>
                                        @endforeach
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

@if($verModalPedimento && !auth()->user()?->hasRole('adminUSA'))
    <div class="modal-overlay">
        <div x-data="{}" x-init="dragModal($el)" class="modal-dialog" wire:ignore.self>            
            <div class="modal-content">
                <div class="cardPrin">
                    <div class="cardPrin-header" style="cursor: move;">
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
                                <div class="col-md-4">
                                    <label class="etiBase">Exchange Rate (USD/MXN)</label>
                                    <input wire:model="tipoCambio" type="text" class="inpBase"  onfocus="this.select()">
                                    @error('tipoCambio') <span class="error text-danger">{{ $message }}</span> @enderror
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

@if($verModalSecuencias)
    <div class="modal-overlay">
        <div x-data="{}" x-init="dragModal($el)" class="modal-dialog" wire:ignore.self>
            <div class="modal-content">
                <div class="cardPrin">
                    <div class="cardPrin-header" style="cursor: move;">
                        <span>Assign Sequences by Tariff Heading</span>
                    </div>
                    <div class="cardPrin-body" style="padding: 10px; max-height: 400px; overflow-y: auto;">
                        <table class="table table-sm table-bordered align-middle m-0">
                            <thead>
                                <tr>
                                    <th class="bg-light">Tariff Heading</th>
                                    <th class="bg-light" style="width: 150px;">Sequence</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($secuencias as $index => $item)
                                    <tr>
                                        <td>
                                            <strong class="etiBase">{{ $item['arancel'] }}</strong>
                                        </td>
                                        <td>
                                            <input type="text" 
                                                   wire:model.lazy="secuencias.{{ $index }}.secuencia" 
                                                   class="inpBase" 
                                                   onfocus="this.select()">
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="cardPrin-footer d-flex justify-content-end gap-2 p-2">
                        <button type="button" wire:click="cancel()" class="bot botGris">Cancel</button>
                        <button type="button" wire:click="saveSecuencias()" class="bot botVerde">Save</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif