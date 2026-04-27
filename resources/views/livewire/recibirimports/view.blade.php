@section('title', __('import'))
<div class="container-fluid p-2">
    <div class="cardPrin shadow-sm border-0">
        <div class="card-header bg-primary text-white fs-5 ps-3 py-2 d-flex align-items-center">
            <span class="me-2">📦</span> Recepción de Imports
        </div>
        <div class="cardPrin-body p-3">
            <div class="row g-3">
                <div class="col-12 col-md-3 border-end">
                    <div class="p-1" style="max-height: 80vh; overflow-y: auto;">
                        @livewire('arbolfacturas', ['Regimen' => 'IN'])
                    </div>
                </div>

                <div class="col-12 col-md-9">
                    @if($selected_id)
                        <div class="d-flex justify-content-between align-items-center bg-light border rounded p-2 mb-3 shadow-sm">
                            <div class="btn-toolbar" role="toolbar">
                                <div class="btn-group me-2 shadow-sm">
                                    <button type="button" class="bot botBlanco botChico" wire:click="recibirFactura"
                                        title="Descargar datos de la factura">
                                        <span class="text-primary">⏬</span> 
                                        <small class="fw-bold">Bajar materiales de la Factura</small>
                                    </button>
                                    <button type="button" class="bot botBlanco botChico" wire:click="limpiar"
                                        title="Limpiar datos">
                                        <span class="text-primary">🗑️</span> 
                                        <small class="fw-bold">Borrar todo</small>
                                    </button>
                                </div>

                                <div class="btn-group me-2 shadow-sm">
                                    <button type="button" class="bot botBlanco botChico" wire:click="confirmarIngreso"
                                        title="Procesar ingreso a almacén de seguridad">
                                        <span class="text-success">🔐</span> 
                                        <small class="fw-bold">Operar entradas a bóveda</small>
                                    </button>
                                </div>
                            </div>
                            <div class="d-none d-lg-block">
                                <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-3 py-2">
                                    Factura: <strong>#{{ $factura->factura }}</strong>
                                </span>
                            </div>
                        </div>
                        <div class="rounded border shadow-sm bg-white">
                            @if($selected_id)
                                @livewire('referenciasmovs', ['IdDoc' => $selected_id, 
                                    'tipoDoc' => 'import', 'estatus' => 'import'], key('movs-import-'.$selected_id))
                            @endif
                        </div>
                    @else
                        <div class="h-100 d-flex flex-column align-items-center justify-content-center border rounded bg-light text-muted p-5 shadow-inner" style="min-height: 400px; border-style: dashed !important;">
                            <div style="font-size: 4rem; opacity: 0.5;">📄</div>
                            <h5 class="mt-3">No invoice selected</h5>
                            <p class="text-center">Select an item from the tree on the left<br>to view and manage import details.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>