@section('title', __('Kardex'))
<div class="d-flex flex-column vh-100 p-3 bg-light">
    <div class="card shadow-sm border-0 d-flex flex-column flex-grow-1 overflow-hidden">
        <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center py-2">
            <h6 class="mb-0"><i class="bi bi-box-seam me-2"></i>Trazabilidad de Inventario</h6>
            <div class="d-flex gap-2">
                <select wire:model.live="filtroDepto" class="form-select form-select-sm w-auto bg-dark text-white border-secondary">
                    <option value="">Ubicación: Todas</option>
                    @foreach($deptos as $depto)
                        <option value="{{ $depto->id }}">{{ $depto->depto }}</option>
                    @endforeach
                </select>
                <select wire:model.live="filtroTipoDoc" class="form-select form-select-sm w-auto bg-dark text-white border-secondary">
                    <option value="">Tipo: Todos</option>
                    <option value="import">Import</option>
                    <option value="traspaso">Traspaso</option>
                    <option value="folio">Producción</option>
                    <option value="export">Export</option>
                </select>
            </div>
        </div>

        <div class="p-2 bg-white border-bottom">
            <div class="input-group input-group-sm">
                <span class="input-group-text bg-transparent border-end-0"><i class="bi bi-search"></i></span>
                <input type="text" wire:model.live="buscar" class="form-control border-start-0" placeholder="Filtrar movimientos...">
            </div>
        </div>

        <div class="table-responsive flex-grow-1 overflow-auto" style="font-size: 0.85rem;">
            <table class="table table-sm table-hover align-middle mb-0">
                <thead class="table-light sticky-top shadow-sm">
                    <tr>
                        <th class="ps-3" style="width: 120px;">Fecha / Ref</th>
                        <th>Material Recibido</th>
                        <th class="text-center" style="width: 180px;">Ruta de Depto.</th>
                        <th class="text-center" style="width: 100px;">Operación</th>
                        <th class="text-end" style="width: 110px;">Entrada</th>
                        <th class="text-end" style="width: 110px;">Salida</th>
                        <th class="text-center" style="width: 60px;">Estatus</th>
                    </tr>
                </thead>
                <tbody class="border-top-0">
                    @foreach($this->movimientos as $mov)
                    <tr>
                        <td class="ps-3">
                            <div class="fw-bold text-dark">{{ $mov->created_at->format('d/m/y H:i') }}</div>
                            <div class="text-muted" style="font-size: 0.75rem;">{{ $mov->Referencia->IdEntradaMex ?? 'S/R' }}</div>
                        </td>
                        <td>
                            <div class="fw-bold text-primary">{{ $mov->Material->material ?? 'S/N' }}</div>
                            @if($mov->glosa)
                                <div class="text-muted x-small italic text-truncate" style="max-width: 300px;">{{ $mov->glosa }}</div>
                            @endif
                            @if($mov->diferencias)
                                <div class="mt-1 px-2 py-1 rounded bg-warning-subtle border-start border-3 border-warning" style="font-size: 0.75rem;">
                                    <span class="text-dark"><i class="bi bi-info-circle me-1"></i>{{ $mov->difsFormat }}</span>
                                </div>
                            @endif
                        </td>
                        <td class="text-center">
                            <div class="d-flex align-items-center justify-content-center gap-1">
                                <span class="badge bg-light text-dark border fw-normal">{{ $mov->DeptoOri->depto ?? 'EXT' }}</span>
                                <i class="bi bi-arrow-right text-muted" style="font-size: 0.7rem;"></i>
                                <span class="badge bg-primary-subtle text-primary border border-primary-subtle fw-normal">{{ $mov->DeptoDes->depto ?? 'PEND' }}</span>
                            </div>
                        </td>
                        <td class="text-center">
                            <span class="badge bg-{{ $mov->tipo == 'entrada' ? 'success' : ($mov->tipo == 'salida' ? 'danger' : 'info') }} py-1 w-100" style="font-size: 0.65rem;">
                                {{ strtoupper($mov->tipo) }}
                            </span>
                        </td>
                        <td class="text-end fw-bold text-success">
                            @if($mov->tipo == 'entrada' || ($mov->tipo == 'traspaso' && $mov->IdDeptoDes))
                                {{ number_format($mov->cantidad, 0) }} <span class="small fw-normal text-muted">{{ number_format($mov->pesoG, 2) }}g</span>
                            @else - @endif
                        </td>
                        <td class="text-end fw-bold text-danger">
                            @if($mov->tipo == 'salida' || ($mov->tipo == 'traspaso' && $mov->IdDeptoOri))
                                {{ number_format($mov->cantidad, 0) }} <span class="small fw-normal text-muted">{{ number_format($mov->pesoG, 2) }}g</span>
                            @else - @endif
                        </td>
                        <td class="text-center">
                            @if($mov->estatus == 'cerrado')
                                <i class="bi bi-check-circle-fill text-success"></i>
                            @else
                                <div class="spinner-grow spinner-grow-sm text-warning"></div>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="card-footer bg-white py-1">
            <div class="d-flex justify-content-between align-items-center">
                <div class="small text-muted">Mostrando {{ $this->movimientos->count() }} registros</div>
                <div>{{ $this->movimientos->links() }}</div>
            </div>
        </div>
    </div>
</div>