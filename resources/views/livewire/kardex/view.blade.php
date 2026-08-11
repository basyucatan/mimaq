@section('title', __('Kardex'))
<div class="container-fluid p-3">
    <div class="cardSec">
        <div class="cardSec-header">
            <div class="d-flex align-items-center gap-2">
                <i class="bi bi-box-seam"></i>
                <span>Kardex</span>
            </div>
            <div class="d-flex gap-2">
                <select wire:model.live="filtroDepto" class="inpSolo w-auto">
                    <option value="">Ubicación: Todas</option>
                    @foreach ($deptos as $depto)
                        <option value="{{ $depto->id }}">{{ $depto->depto }}</option>
                    @endforeach
                </select>

                <select wire:model.live="filtroTipoDoc" class="inpSolo w-auto">
                    <option value="">Tipo: Todos</option>
                    <option value="import">Import</option>
                    <option value="traspaso">Traspaso</option>
                    <option value="folio">Producción</option>
                    <option value="export">Export</option>
                </select>
                <div class="me-2 position-relative" style="display:inline-block;">
                    <input wire:model.lazy="buscar" class="inpSolo" 
                    wire:keydown.escape="$set('buscar','')"
                    onfocus="this.select()" placeholder="Buscar...">
                    @if($buscar)
                        <span wire:click="$set('buscar','')" 
                            class="bot botNegro botChico"
                            style="position: absolute; right: 6px; top: 50%; transform: translateY(-50%); cursor: pointer;">
                            X
                        </span>
                    @endif
                </div> 
            </div>
        </div>
        <div class="cardSec-body">
            <div class="d-flex justify-content-end mb-2">
                {{ $this->movimientos->links() }}
            </div>
            <div class="tablaCont">
                <table class="table tabBase ch align-middle">
                    <thead>
                        <tr>
                            <th class="ps-3" style="width: 120px;">Ref/Fecha</th>
                            <th>Material</th>
                            <th class="text-center" style="width: 180px;">Ruta Depto.</th>
                            <th class="text-center" style="width: 100px;">Operación</th>
                            <th class="text-end" style="width: 110px;">Entrada</th>
                            <th class="text-end" style="width: 110px;">Salida</th>
                            <th class="text-center" style="width: 60px;">Est.</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($this->movimientos as $mov)
                            <tr>
                                <td class="ps-3">
                                    <div class="fw-bold text-dark">{{ $mov->Referencia->IdEntradaMex ?? 'S/R' }}</div>
                                    <div class="text-muted small">{{ $mov->created_at->format('d/m/y H:i') }}</div>
                                </td>
                                <td>
                                    <div class="fw-bold text-primary">
                                        {{ $mov->Material->material ?? '' }}
                                        <span class="text-muted">
                                            {{ $mov->Referencia->propsTot }}
                                        </span>
                                    </div>
                                    <div class="text-muted x-small">{{ $mov->Referencia->ordenInfo ?? '' }}</div>
                                    @if ($mov->Referencia && $mov->Referencia->diferencias)
                                        <div class="mt-1 px-2 py-1 rounded bg-warning-subtle border-start border-3 border-warning"
                                            style="font-size: 0.7rem;">
                                            <span class="text-dark">
                                                <i class="bi bi-info-circle me-1"></i>
                                                {{ $mov->Referencia->difsFormat }}
                                            </span>
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex align-items-center justify-content-center gap-1">
                                        <span
                                            class="badge bg-light text-dark border fw-normal">{{ $mov->DeptoOri->depto ?? 'EXT' }}</span>
                                        <i class="bi bi-arrow-right text-muted" style="font-size: 0.7rem;"></i>
                                        <span
                                            class="badge bg-primary-subtle text-primary border border-primary-subtle fw-normal">{{ $mov->DeptoDes->depto ?? 'PEND' }}</span>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <span
                                        class="badge bg-{{ $mov->tipo == 'entrada' ? 'success' : ($mov->tipo == 'salida' ? 'danger' : 'info') }} py-1 w-100"
                                        style="font-size: 0.65rem;">
                                        {{ strtoupper($mov->tipo) }}
                                    </span>
                                </td>
                                <td class="text-end fw-bold text-success">
                                    @if ($mov->tipo == 'entrada' || ($mov->tipo == 'traspaso' && $mov->IdDeptoDes))
                                        {{ number_format($mov->cantidad, 0) }} <span
                                            class="small fw-normal text-muted">{{ number_format($mov->pesoG, 2) }}g</span>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="text-end fw-bold text-danger">
                                    @if ($mov->tipo == 'salida' || ($mov->tipo == 'traspaso' && $mov->IdDeptoOri))
                                        {{ number_format($mov->cantidad, 0) }} <span
                                            class="small fw-normal text-muted">{{ number_format($mov->pesoG, 2) }}g</span>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if ($mov->estatus == 'cerrado')
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
        </div>
    </div>
</div>
