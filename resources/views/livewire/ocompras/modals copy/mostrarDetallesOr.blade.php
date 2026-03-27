@if ($verDetalles)
    <div class="modal-overlay">
        <div x-data="{}" x-init="dragModal($el)" class="modal-dialog" style="width: 98%;" wire:ignore.self>
            <div class="modal-content">
                <div class="cardPrin" style="cursor: move;">
                    <div class="cardPrin-header d-flex justify-content-between align-items-center">
                        <h5>Materiales de la OC</h5>
                        <button type="button" class="btn-close" wire:click="cerrarDetalles"></button>
                    </div>
                    <div class="cardPrin-body">
                        <div class="card mb-3 border-0 bg-light">
                            <div class="card-body p-2">
                                <div class="row g-2">
                                    <div class="col-2">
                                        <input type="number" step="1" wire:model="cantidadMat" class="inpSolo"
                                            placeholder="Cant.">
                                    </div>
                                    <div class="col-10 position-relative">
                                        <div class="input-group">
                                            <input wire:model.live="keyWordMat" type="text"
                                                class="form-control inpBase"
                                                placeholder="🔍 Buscar material o referencia...">
                                            <button type="button" wire:click="toggleNuevoMaterial"
                                                class="bot {{ $verNuevoMat ? 'botRojo' : 'botAzul' }}">✚</button>
                                        </div>
                                        @if (count($mats) > 0)
                                            <div class="position-absolute bg-white border shadow-lg w-100 mt-1 rounded-3"
                                                style="z-index: 2000; max-height: 300px; overflow-y: auto;">
                                                @foreach ($mats as $m)
                                                    <a href="javascript:void(0)"
                                                        wire:click="elegirMaterial({{ $m->id }})"
                                                        class="d-block p-2 border-bottom text-decoration-none list-group-item-action small">
                                                        <div class="d-flex gap-2 align-items-center">
                                                            @if ($m->color)
                                                                <span class="cuadroColor"
                                                                    style="background-color: {{ $m->color->colorRgba }}; width: 12px; height: 12px; border-radius: 2px;"></span>
                                                            @endif
                                                            <span
                                                                class="fw-bold text-dark small">{{ $m->material->material }}
                                                                ({{ $m->unidad }})</span>
                                                            <span class="badge bg-secondary ms-auto"
                                                                style="font-size: 0.6rem;">{{ $m->referencia }}</span>
                                                        </div>
                                                    </a>
                                                @endforeach
                                            </div>
                                        @elseif(strlen($keyWordMat) > 2)
                                            <div class="position-absolute bg-white border shadow-lg w-100 p-2 text-center rounded-3 mt-1"
                                                style="z-index: 2000;">
                                                <small class="d-block mb-1 text-muted small">No se encontró
                                                    "{{ $keyWordMat }}"</small>
                                                <button type="button" wire:click="toggleNuevoMaterial"
                                                    class="bot botNaranja botSm">Dar de Alta</button>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                @if ($verNuevoMat)
                                    @include('livewire.ocompras.nuevoMaterial')
                                @endif
                            </div>
                        </div>

                        <div class="table-responsive overflow-auto" style="max-height: 60vh;">
                            <table class="table tabBase ch">
                                <thead>
                                    <tr>
                                        <th width="70"class="text-center">Cant.</th>
                                        <th>Descripción</th>
                                        <th width="70" class="text-end">+Iva</th>
                                        <th width="70" class="text-end">Neto</th>
                                        <th width="80" class="text-end">Importe</th>
                                        <th width="20"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($detalles as $id => $det)
                                        <tr class="align-middle">
                                            <td>
                                                @if ($oCompra?->estatus === 'ordenado')
                                                    <input type="number" step="1" class="inpSolo"
                                                        onfocus="this.select()"
                                                        wire:model.blur="detalles.{{ $id }}.cantidadRec">
                                                @else
                                                    <input type="number" step="1" class="inpSolo"
                                                        onfocus="this.select()"
                                                        wire:model.blur="detalles.{{ $id }}.cantidad">
                                                @endif
                                            </td>
                                            <td colspan="5">
                                                <div class="d-flex gap-2">
                                                    @if ($det['colorRgba'])
                                                        <span class="cuadroColor"
                                                            style="background-color: {{ $det['colorRgba'] }}; width: 12px; height: 12px; border-radius: 2px;"></span>
                                                    @endif
                                                    <span class="fw-bold small">{{ $det['nombre'] }}</span>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr class="align-middle">
                                            <td></td>
                                            <td></td>
                                            <td><input type="number"
                                                    wire:model.blur="detalles.{{ $id }}.costoU"
                                                    onfocus="this.select()" class="inpSolo text-end p-1"></td>
                                            <td><input type="number"
                                                    wire:model.blur="detalles.{{ $id }}.costoN"
                                                    onfocus="this.select()" class="inpSolo text-end p-1"></td>
                                            <td class="text-end fw-bold">
                                                ${{ number_format((float) ($det['cantidad'] ?? 0) * (float) ($det['costoN'] ?? 0), 2) }}
                                            </td>
                                            <td class="text-center">
                                                <button type="button" wire:click="removeDetalle({{ $id }})"
                                                    class="bot botRojo"
                                                    onclick="confirm('¿Estás seguro?') || event.stopImmediatePropagation()">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        @error('detalles')
                            <span class="text-danger d-block mt-2">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="cardPrin-footer">
                        <button type="button" class="bot botNegro" wire:click="cerrarDetalles">Cerrar</button>
                        <button type="button" class="bot botVerde" wire:click="guardarDetalles"
                            wire:loading.attr="disabled">
                            <span wire:loading.remove wire:target="guardarDetalles">Guardar</span>
                            <span wire:loading wire:target="guardarDetalles">Guardando...</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif
