@if ($verModalOcompra)
<div class="modal-overlay">
    <div x-data="{ cabeceraVisible: true }" x-init="dragModal($el)" class="modal-dialog" 
         style="width: 98%; max-width: 1200px; height: 80vh; margin: 2vh auto;" wire:ignore.self>
        
        <div class="modal-content border-0 shadow-lg h-100">
            <div class="cardPrin d-flex flex-column h-100" style="cursor: move;">
                
                <div class="cardPrin-header d-flex justify-content-between align-items-center bg-white border-bottom p-2 p-md-3 flex-shrink-0">
                    <div class="d-flex align-items-center gap-2 gap-md-3">
                        <button x-on:click="cabeceraVisible = !cabeceraVisible" class="btn btn-sm btn-light border">
                            <i class="bi" x-bind:class="cabeceraVisible ? 'bi-chevron-up' : 'bi-chevron-down'"></i>
                        </button>
                        <h6 class="m-0 fw-bold text-dark d-none d-sm-block">
                            {{ $selected_id ? '#'.$selected_id : 'Nueva Orden' }}
                        </h6>
                        <div class="badge bg-white text-dark border px-2 py-1 shadow-sm">
                            <small class="text-muted d-block d-md-none" style="font-size: 0.5rem;">TOTAL</small>
                            <span class="fw-bold text-success" style="font-size: 0.9rem;">
                                ${{ number_format($this->subtotal * $factorIva, 2) }}
                            </span>
                        </div>
                    </div>
                    <button wire:click="cancel" type="button" class="btn-close"></button>
                </div>

                <div class="cardPrin-body p-0 flex-grow-1 overflow-auto" style="background-color: #fcfcfc;">
                    
                    <div x-show="cabeceraVisible" x-transition class="bg-light border-bottom p-3">
                        <div class="row g-2">
                            <div class="col-12 col-md-4">
                                <label class="small fw-bold text-muted">Proveedor</label>
                                <select class="form-select form-select-sm border-0 shadow-sm">
                                    <option selected>Proveedor Fake S.A de C.V</option>
                                </select>
                            </div>
                            <div class="col-12 col-md-4">
                                <label class="small fw-bold text-muted">Obra</label>
                                <select class="form-select form-select-sm border-0 shadow-sm">
                                    <option selected>Residencial "El Éxito"</option>
                                </select>
                            </div>
                            <div class="col-12 col-md-4">
                                <label class="small fw-bold text-muted">Concepto</label>
                                <input type="text" class="form-control form-control-sm border-0 shadow-sm" placeholder="Concepto...">
                            </div>
                        </div>
                    </div>

                    <div class="p-2 border-bottom bg-white sticky-top shadow-sm" style="top: -1px; z-index: 1020;">
                        <div class="row g-1">
                            <div class="col-3 col-md-1">
                                <input type="number" class="form-control form-control-sm text-center fw-bold" value="1">
                            </div>
                            <div class="col-9 col-md-11">
                                <div class="input-group input-group-sm shadow-sm">
                                    <input type="text" class="form-control" placeholder="Buscar material...">
                                    <button class="btn btn-primary px-2 px-md-3">✚</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0" style="min-width: 600px;">
                            <thead class="bg-light sticky-top" style="top: 45px; z-index: 1010;">
                                <tr style="font-size: 0.8rem;">
                                    <th width="70" class="text-center border-0">Cant.</th>
                                    <th class="border-0">Descripción</th>
                                    <th width="100" class="text-end border-0">Unit.</th>
                                    <th width="100" class="text-end border-0">Total</th>
                                    <th width="40" class="border-0"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @for ($i = 1; $i <= 20; $i++)
                                <tr style="font-size: 0.85rem;">
                                    <td class="px-1">
                                        <input type="number" class="form-control form-control-sm text-center border-0 bg-light p-1" value="{{ rand(1, 10) }}">
                                    </td>
                                    <td>
                                        <div class="fw-bold small">Material #{{ $i }}</div>
                                        <div class="text-muted" style="font-size: 0.7rem;">REF-00{{ $i }}</div>
                                    </td>
                                    <td><input type="number" class="form-control form-control-sm text-end border-0 bg-light p-1" value="150.00"></td>
                                    <td class="text-end fw-bold">$1,500.00</td>
                                    <td class="text-center">
                                        <button class="btn btn-sm text-danger p-0"><i class="bi bi-trash"></i></button>
                                    </td>
                                </tr>
                                @endfor
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="cardPrin-footer bg-light border-top p-2 p-md-3 d-flex justify-content-end gap-2 flex-shrink-0">
                    <button wire:click="cancel" type="button" class="bot botNegro py-2">Cerrar</button>
                    <button wire:click="save" type="button" class="bot botVerde py-2 flex-grow-1 flex-md-grow-0">
                        <i class="bi bi-check-lg"></i> Guardar
                    </button>
                </div>

            </div>
        </div>
    </div>
</div>
@endif