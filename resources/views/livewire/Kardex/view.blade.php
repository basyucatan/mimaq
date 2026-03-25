@section('title', __('Kardex'))
<div>
    <div class="container-fluid">
        <div class="cardPrin border-0 shadow-sm">
            <div class="cardPrin-header py-2 px-3">
                <div class="d-flex flex-wrap flex-lg-nowrap w-100 justify-content-lg-between">
                    <div class="d-flex gap-1 w-100 w-lg-auto">
                        <span class="mb-0 fw-bold fs-5 text-white lh-1">Kardex</span>
                        @if($matCosto)
                            <span class="badge bg-white text-dark fw-bold shadow-sm">{{ $matCosto->referencia }}</span>
                            <span class="text-white small text-truncate d-sm-inline" style="max-width: 150px;">
                                {{ $matCosto->material->material }}
                            </span>
                        @endif
                    </div>
                    <div class="d-flex flex-wrap flex-lg-nowrap w-100 justify-content-end">
                        <div class="d-flex gap-1 w-100">
                            <input type="date" wire:model.live="fechaIni" class="inpSolo flex-fill" style="max-width: 130px;">
                            <input type="date" wire:model.live="fechaFin" class="inpSolo flex-fill" style="max-width: 130px;">
                        </div>
                        <div class="d-flex gap-1 w-100">
                            <select wire:model="IdBodega" wire:change="calcularMovs" class="inpSolo flex-fill" style="min-width: 100px;">
                                <option value="">-- Bodega --</option>
                                @foreach ($bodegas as $id => $nombre)
                                    <option value="{{ $id }}">{{ $nombre }}</option>
                                @endforeach
                            </select>
                            <select wire:model="IdDepto" wire:change="calcularMovs" class="inpSolo flex-fill" style="min-width: 100px;">
                                <option value="">-- Depto --</option>
                                @foreach ($deptos as $id => $nombre)
                                    <option value="{{ $id }}">{{ $nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="d-flex gap-1 w-100 justify-content-end">
                            <div class="bg-light px-2 py-1 rounded-pill border fw-bold {{ $existencia >= 0 ? 'text-dark' : 'text-danger' }} shadow-sm white-space-nowrap text-center">
                                <span style="font-size: 0.75rem;" class="text-muted me-1">Stock:</span>{{ number_format($existencia, 3) }}
                            </div>
                            <button class="bot botNaranja" data-bs-toggle="offcanvas" data-bs-target="#menuMateriales">
                                <i class="bi bi-list-ul"></i>
                                <span class="fw-bold d-md-inline">Materiales</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="cardPrin-body p-0">
                <div class="cardSec" style="overflow-y: auto; min-height: 500px;">
                    @if($movsPaginados && $movsPaginados->count() > 0)
                        <div class="table-responsive">
                            <table class="table tabBase ch mb-0" style="table-layout: fixed; width: 100%;">
                                <thead class="bg-dark text-white sticky-top">
                                    <tr>
                                        <th class="border-0 ps-3" style="width: 25%;">Fecha</th>
                                        <th class="border-0" style="width: 50%;">Origen / Destino</th>
                                        <th class="text-end border-0" style="width: 15%;">Cant.</th>
                                        <th class="text-end border-0 pe-3" style="width: 15%;">Saldo</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white">
                                    @foreach($movsPaginados as $row)
                                        @php $esEntrada = $row->cantidad >= 0; @endphp
                                        <tr style="border-bottom: 1px">
                                            <td class="ps-1 small">
                                                <div class="fw-bold text-dark">{{ App\Models\Util::formatFecha($row->fechaH,'DDMMM HH:mm') }}</div>
                                                <span class="badge bg-light text-dark border-0 small text-uppercase" style="font-size: 0.65rem;">{{ $row->tipo }}</span>
                                            </td>
                                            <td class="small text-muted">
                                                <div class="d-flex flex-column" style="line-height: 1.1;">
                                                    <div class="d-flex align-items-center gap-1">
                                                        <span class="fw-bold text-dark text-truncate" title="{{ $row->txtOri }}">{{ $row->txtOri }}</span>
                                                        <i class="bi bi-arrow-right text-primary mx-1"></i>
                                                        <span class="fw-bold text-dark text-truncate" title="{{ $row->txtDes }}">{{ $row->txtDes }}</span>
                                                    </div>
                                                    <div class="mt-1 opacity-75" style="font-size: 0.65rem;">
                                                        <i class="bi bi-person me-1"></i>{{ $row->userOri?->name ?? 'Sist.' }}
                                                        @if($row->userDes) <i class="bi bi-chevron-right mx-1"></i> {{ $row->userDes->name }} @endif
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="text-end fw-bold {{ $esEntrada ? 'text-success' : 'text-danger' }}">
                                                {{ number_format($row->cantidad, 2) }}
                                            </td>
                                            <td class="text-end fw-bold text-dark pe-3">
                                                {{ number_format($row->saldoCalculado, 2) }}
                                            </td>
                                        </tr>
                                    @endforeach
                                    <div class="float-end">
                                        {{ $movsPaginados->links() }}
                                    </div>
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="h-100 d-flex flex-column justify-content-center align-items-center text-muted opacity-25" style="min-height: 450px;">
                            <i class="bi bi-box-seam" style="font-size: 4rem;"></i>
                            <h5 class="mt-3">Sin movimientos o selecciona un material</h5>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="offcanvas offcanvas-start cardSec" tabindex="-1" id="menuMateriales" wire:ignore.self>
        <div class="cardSec-header">
            <span class="fs-5">Elegir Material</span>
            <button type="button" class="btn-close btn-close-black" data-bs-dismiss="offcanvas"></button>
        </div>
        <div class="cardSec-body">
            <livewire:arbolclasesmats />
        </div>
    </div>
</div>