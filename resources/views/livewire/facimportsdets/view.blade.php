@section('title', __('Import Details'))
<div class="container-fluid p-0">
    <div class="row g-0 justify-content-center">
        <div class="col-12">
            <div class="cardPrin">
                <div class="cardPrin-header" style="cursor: move;">
                    <span>Invoice <span class="badge bg-danger fs-5 fw-bold">{{ $factura?->factura }} | {{ Util::dinero($factura?->total,2) }}</span></span>
                    <div class="me-2 position-relative" style="display:inline-block;">
                        <input wire:model.lazy="keyWord" class="inpSolo" 
                        wire:keydown.escape="$set('keyWord','')"
                        onfocus="this.select()" placeholder="Buscar...">
                        @if($keyWord)
                            <span wire:click="$set('keyWord','')" 
                                class="bot botNegro botChico"
                                style="position: absolute; right: 6px; top: 50%; transform: translateY(-50%); cursor: pointer;">
                                X
                            </span>
                        @endif
                    </div>
                    <div class="d-flex gap-2">
                        @if($factura->estatus === 'abierto')
                            <button class="bot botNaranja botChico" wire:click="crearEstilo" title="Create Style Set">
                                <i class="bi bi-file-earmark-plus"></i>
                            </button>
                            <button class="bot botVerde botChico" wire:click="create" title="New Material">
                                <i class="bi bi-file-earmark-plus"></i>
                            </button>
                        @endif
                        <button class="bot botVerde botChico" wire:click="imprimirPL" 
                            wire:loading.attr="disabled" wire:target="imprimirPL" title="Print Packing List">
                            <span wire:loading.remove wire:target="imprimirPL"><i class="bi bi-printer"></i></span>
                            <span wire:loading wire:target="imprimirPL">⏳</span>
                        </button>
                        <button class="bot botMorado botChico" wire:click="imprimirPLProduccion" 
                            wire:loading.attr="disabled" wire:target="imprimirPLProduccion" title="Print Packing List">
                            <span wire:loading.remove wire:target="imprimirPLProduccion"><i class="bi bi-printer"></i></span>
                            <span wire:loading wire:target="imprimirPLProduccion">⏳</span>
                        </button>
                        <button class="bot botAzul botChico" wire:click="imprimirFactura" 
                            wire:loading.attr="disabled" wire:target="imprimirFactura" title="Print Invoice">
                            <span wire:loading.remove wire:target="imprimirFactura"><i class="bi bi-printer"></i></span>
                            <span wire:loading wire:target="imprimirFactura">⏳</span>
                        </button>
                        imprimirPLProd
                    </div>
                </div>
                <div class="cardPrin-body">
                    @include('livewire.facimportsdets.porEstilo')
                    <div class="d-flex justify-content-end mb-2">
                        {{ $facimportsdets->links() }}
                    </div>
                    @include('livewire.facimportsdets.modals')
                    <div class="tablaCont d-none d-md-block">
                        <table class="table tabBase ch">
                            <thead>
                                <tr>
                                    <th>Index</th>
                                    <th>Origin</th>
                                    <th>Material</th>
                                    <th>Weight</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($facimportsdets as $row)
                                    <tr>
                                        <td>{{ $row->IdEntradaMex }}</td>
                                        <td>{{ $row->Origen->origen ?? ''}}</td>
                                        <td>
                                            <span class="badge bg-success">{{ Util::Miles($row->cantidad) }}</span>
                                            <strong>{{ $row->Material->materialI }}</strong> {{ $row->propsTot }}
                                            @if($row->ordenInfo)
                                                <br><small class="text-muted">{{ $row->ordenInfo }}</small>
                                            @endif
                                            <span class="badge bg-secondary">$ {{ Util::Miles($row->precioU,2) }}</span>
                                        </td>
                                        <td>{{ Util::Miles($row->pesoEnUMat,3) }} {{ $row->material->unidadP->unidad ?? '' }}</td>
                                        <td width="60">
                                            @if($factura->estatus === 'abierto')
                                                <div class="d-flex justify-content-around align-items-center gap-1">
                                                    <button wire:click="edit({{ $row->id }})"
                                                        class="bot botNaranja botChico" title="Edit">
                                                        <i class="bi-pencil-square"></i>
                                                    </button>
                                                    <button wire:click="destroy({{ $row->id }})"
                                                        class="bot botRojo botChico"
                                                        onclick="confirm('Are you sure you want to delete this record?') || event.stopImmediatePropagation()">
                                                        <i class="bi bi-trash3-fill"></i>
                                                    </button>
                                                </div>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="tablaCont d-block d-md-none">
                        @forelse($facimportsdets as $row)
                            <div class="cardSec mb-2 shadow-sm">
                                <div class="cardSec-header">
                                    <span>
                                        {{ Util::Miles($row->cantidad) }} {{ $row->Material->material }}
                                    </span>
                                    @if($factura->estatus === 'abierto')
                                        <div class="d-flex gap-1">
                                            <button wire:click="edit({{ $row->id }})"
                                                class="bot botNaranja botChico">
                                                <i class="bi-pencil-square"></i>
                                            </button>
                                            <button wire:click="destroy({{ $row->id }})"
                                                class="bot botRojo botChico"
                                                onclick="confirm('Are you sure you want to delete this record?') || event.stopImmediatePropagation()">
                                                <i class="bi bi-trash3-fill"></i>
                                            </button>
                                        </div>
                                    @endif
                                </div>
                                <div class="cardSec-body small">
                                    <span><strong>Ref. </strong> {{ $row->IdEntradaMex }} ({{ $row->Origen->origen ?? ''}})</span>
                                    <span><strong>Weight: </strong> {{  Util::Miles($row->pesoEnUMat,3) }}</span>
                                    <span><strong>$ </strong> {{ Util::Miles($row->precioU,2) }}</span><br>
                                    <div>{!! $row->propiedades !!}</div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center text-muted">
                                No records found
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>