@if ($verDetalles)
<div class="modal-overlay">
    <div x-data="{}" x-init="dragModal($el)" class="modal-dialog" 
         style="width: 98%; max-width: 1000px; height: 92vh; margin: 2vh auto;" wire:ignore.self>
        
        <div class="modal-content border-0 shadow-lg h-100">
            <div class="cardPrin d-flex flex-column h-100" style="cursor: move;">
                
                <div class="cardPrin-header d-flex justify-content-between align-items-center bg-white border-bottom p-3 flex-shrink-0">
                    <div>
                        <h5 class="m-0 fw-bold text-dark">Partidas de la Orden</h5>
                        <span class="badge bg-light text-muted border">Modo Edición de Detalles</span>
                    </div>
                    <div class="d-flex align-items-center gap-3">
                        <div class="text-end">
                            <small class="text-muted d-block" style="font-size: 0.6rem;">TOTAL NETO</small>
                            <span class="h5 m-0 fw-bold text-success">${{ number_format($this->subtotal * $factorIva, 2) }}</span>
                        </div>
                        <button type="button" class="btn-close" wire:click="cerrarDetalles"></button>
                    </div>
                </div>

                <div class="cardPrin-body p-0 flex-grow-1 d-flex flex-column overflow-hidden">
                    
                    <div class="p-3 bg-white border-bottom flex-shrink-0 shadow-sm" style="z-index: 1030;">
                        <div class="row g-2">
                            <div class="col-3 col-md-2">
                                <input type="number" step="1" wire:model="cantidadMat" class="form-control fw-bold text-center border-2 border-primary" placeholder="Cant.">
                            </div>
                            <div class="col-9 col-md-10 position-relative">
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0"><i class="bi bi-search"></i></span>
                                    <input wire:model.live="keyWordMat" type="text" class="form-control border-start-0" placeholder="Buscar material por nombre o referencia...">
                                    <button wire:click="toggleNuevoMaterial" class="btn {{ $verNuevoMat ? 'btn-danger' : 'btn-primary' }}">
                                        <i class="bi {{ $verNuevoMat ? 'bi-x-lg' : 'bi-plus-lg' }}"></i>
                                    </button>
                                </div>

                                @if (count($mats) > 0)
                                    <div class="position-absolute bg-white border shadow-lg w-100 mt-1 rounded-3 overflow-auto" style="z-index: 2000; max-height: 250px;">
                                        @foreach ($mats as $m)
                                            <a href="javascript:void(0)" wire:click="elegirMaterial({{ $m->id }})" class="d-block p-2 border-bottom text-decoration-none list-group-item-action">
                                                <div class="d-flex align-items-center gap-2">
                                                    @if ($m->color)
                                                        <span style="background-color: {{ $m->color->colorRgba }}; width: 10px; height: 10px; border-radius: 2px;"></span>
                                                    @endif
                                                    <span class="fw-bold text-dark small">{{ $m->material->material }}</span>
                                                    <span class="badge bg-light text-muted border ms-auto" style="font-size: 0.6rem;">{{ $m->referencia }}</span>
                                                </div>
                                            </a>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </div>
                        @if ($verNuevoMat)
                            <div class="mt-2 p-2 border rounded bg-light">
                                @include('livewire.ocompras.nuevoMaterial')
                            </div>
                        @endif
                    </div>

                    <div class="flex-grow-1 overflow-auto bg-light p-2">
                        <table class="table table-borderless align-middle mb-0">
                            <tbody>
                                @foreach ($detalles as $id => $det)
                                <tr class="bg-white shadow-sm" style="border-radius: 8px 8px 0 0;">
                                    <td width="70" class="ps-3 pt-3">
                                        @php $campoCant = ($oCompra?->estatus === 'ordenado') ? 'cantidadRec' : 'cantidad'; @endphp
                                        <input type="number" step="1" onfocus="this.select()" 
                                               wire:model.blur="detalles.{{ $id }}.{{ $campoCant }}" 
                                               class="form-control form-control-sm fw-bold text-center bg-primary text-white border-0">
                                    </td>
                                    <td colspan="3" class="pt-3">
                                        <div class="d-flex align-items-center gap-2">
                                            @if ($det['colorRgba'])
                                                <span style="background-color: {{ $det['colorRgba'] }}; width: 12px; height: 12px; border-radius: 3px;" class="shadow-sm"></span>
                                            @endif
                                            <span class="fw-bold text-dark">{{ $det['nombre'] }}</span>
                                        </div>
                                    </td>
                                    <td width="50" class="text-end pe-3 pt-3">
                                        <button wire:click="removeDetalle({{ $id }})" class="btn btn-sm text-danger border-0 p-0" 
                                                onclick="confirm('¿Eliminar?') || event.stopImmediatePropagation()">
                                            <i class="bi bi-x-circle-fill fs-5"></i>
                                        </button>
                                    </td>
                                </tr>
                                <tr class="bg-white shadow-sm border-top" style="border-radius: 0 0 8px 8px;">
                                    <td class="pb-3 ms-3"></td>
                                    <td class="pb-3">
                                        <label class="d-block text-muted mb-0" style="font-size: 0.6rem;">PRECIO +IVA</label>
                                        <div class="input-group input-group-sm w-auto">
                                            <span class="input-group-text border-0 bg-transparent ps-0">$</span>
                                            <input type="number" wire:model.blur="detalles.{{ $id }}.costoU" onfocus="this.select()" class="form-control form-control-sm border-0 bg-light rounded shadow-sm text-end" style="max-width: 100px;">
                                        </div>
                                    </td>
                                    <td class="pb-3">
                                        <label class="d-block text-muted mb-0" style="font-size: 0.6rem;">PRECIO NETO</label>
                                        <div class="input-group input-group-sm w-auto">
                                            <span class="input-group-text border-0 bg-transparent ps-0">$</span>
                                            <input type="number" wire:model.blur="detalles.{{ $id }}.costoN" onfocus="this.select()" class="form-control form-control-sm border-0 bg-light rounded shadow-sm text-end fw-bold" style="max-width: 100px;">
                                        </div>
                                    </td>
                                    <td class="pb-3 text-end pe-3" colspan="2">
                                        <label class="d-block text-muted mb-0" style="font-size: 0.6rem;">SUBTOTAL ITEM</label>
                                        <span class="fw-bold text-dark fs-6">
                                            ${{ number_format((float)($det['cantidad'] ?? 0) * (float)($det['costoN'] ?? 0), 2) }}
                                        </span>
                                    </td>
                                </tr>
                                <tr style="height: 10px;"></tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="cardPrin-footer bg-white border-top p-3 d-flex justify-content-between align-items-center flex-shrink-0">
                    <div>
                        @error('detalles')
                            <small class="text-danger fw-bold"><i class="bi bi-info-circle"></i> {{ $message }}</small>
                        @enderror
                    </div>
                    <div class="d-flex gap-2">
                        <button type="button" class="bot botNegro" wire:click="cerrarDetalles">Cerrar</button>
                        <button type="button" class="bot botVerde px-4" wire:click="guardarDetalles" wire:loading.attr="disabled">
                            <span wire:loading.remove>Guardar Cambios</span>
                            <span wire:loading><span class="spinner-border spinner-border-sm me-1"></span>Procesando...</span>
                        </button>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@endif