@section('title', __('Import Details'))
<div class="container-fluid p-0">
    <div class="row g-0 justify-content-center">
        <div class="col-12">
            <div class="cardPrin">
                <div class="cardPrin-header headerAmplio">
                    <span>Invoice <span class="badge bg-danger fs-5 fw-bold">{{ $factura?->factura }} |
                            {{ Util::dinero($factura?->total, 2) }}</span></span>
                    <div class="me-2 position-relative" style="display:inline-block;">
                        <input wire:model.lazy="keyWord" class="inpSolo" wire:keydown.escape="$set('keyWord','')"
                            onfocus="this.select()" placeholder="Buscar...">
                        @if ($keyWord)
                            <span wire:click="$set('keyWord','')" class="bot botNegro botChico"
                                style="position: absolute; right: 6px; top: 50%; transform: translateY(-50%); cursor: pointer;">
                                X
                            </span>
                        @endif
                    </div>
                    <div class="d-flex gap-2">
                        @if ($factura->estatus === 'abierto')
                            <button class="bot botVerde botChico" wire:click="crearEstilo" title="Create Style Set">
                                <i class="bi bi-file-earmark-plus"></i>
                            </button>
                            <button class="bot botAzul botChico" wire:click="create" title="New Material">
                                <i class="bi bi-file-earmark-plus"></i>
                            </button>
                            @role('SuperAdmin')
                            <button class="bot botNegro botChico" wire:click="migrar"
                                wire:loading.attr="disabled" wire:target="migrar" title="Migrar">
                                <span wire:loading.remove wire:target="migrar"><i
                                        class="bi bi-clock"></i></span>
                                <span wire:loading wire:target="migrar">⏳</span>
                            </button>
                            @endrole
                        @endif
                        <button class="bot botMorado botChico" wire:click="imprimirPLProduccion"
                            wire:loading.attr="disabled" wire:target="imprimirPLProduccion" title="Packing List">
                            <span wire:loading.remove wire:target="imprimirPLProduccion"><i
                                    class="bi bi-printer"></i></span>
                            <span wire:loading wire:target="imprimirPLProduccion">⏳</span>
                        </button>
                        @unlessrole('adminUSA')
                            <button class="bot botNaranja botChico" wire:click="imprimirPL" wire:loading.attr="disabled"
                                wire:target="imprimirPL" title="Packing List Español">
                                <span wire:loading.remove wire:target="imprimirPL"><i class="bi bi-printer"></i></span>
                                <span wire:loading wire:target="imprimirPL">⏳</span>
                            </button>
                        @endunlessrole
                        <button class="bot botNegro botChico" wire:click="imprimirFactura" wire:loading.attr="disabled"
                            wire:target="imprimirFactura" title="Print Invoice">
                            <span wire:loading.remove wire:target="imprimirFactura"><i class="bi bi-printer"></i></span>
                            <span wire:loading wire:target="imprimirFactura">⏳</span>
                        </button>
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
                                        <td>{{ $row->Origen->origen ?? '' }}</td>
                                        <td>
                                            <span class="badge {{ data_get($row->adicionales, 'ordenInfo.orden') ? 'bg-success' : 'bg-primary' }}" style="font-size: 0.8rem;">
                                                {{ Util::Miles($row->cantidad) }}
                                            </span>
                                            <strong>{{ $row->material?->materialI }}</strong> {{ $row->propsTot }}
                                            <span class="badge bg-dark" style="font-size: 0.8rem;">
                                                $ {{ Util::Miles($row->precioU, 2) }}
                                            </span>
                                            @if ($row->ordenInfo)
                                                <br><small class="text-muted">{{ $row->ordenInfo }}</small>
                                            @endif
                                            @if(!empty($row->adicionales['secuencia']))
                                                <span class="badge bg-secondary" style="font-size: 0.8rem;">
                                                    {{ $row->adicionales['secuencia'] }}
                                                </span>
                                            @endif
                                        </td>
                                        <td>{{ Util::Miles($row->pesoEnUMat, 3) }}
                                            {{ $row->material->unidadP->unidad ?? '' }}</td>
                                        <td width="90">
                                            @if ($factura->estatus === 'abierto')
                                                <div class="d-flex justify-content-around align-items-center gap-1">
                                                    @if(!auth()->user()->hasRole('adminUSA') && (data_get($row->adicionales, 'ordenInfo.esProduccion') === true))
                                                        <button wire:click="editProduccion({{ $row->id }})" 
                                                            class="bot botAzul botChico" title="Edit Production Data">
                                                            <i class="bi bi-gear-fill"></i>
                                                        </button>
                                                    @endif
                                                    <button wire:click="edit({{ $row->id }})" class="bot botNaranja botChico" title="Edit">
                                                        <i class="bi-pencil-square"></i>
                                                    </button>
                                                    <button wire:click="destroy({{ $row->id }})" class="bot botRojo botChico" onclick="confirm('Are you sure you want to delete this record?') || event.stopImmediatePropagation()">
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
                                    @if ($factura->estatus === 'abierto')
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
                                    <span><strong>Ref. </strong> {{ $row->IdEntradaMex }}
                                        ({{ $row->Origen->origen ?? '' }})</span>
                                    <span><strong>Weight: </strong> {{ Util::Miles($row->pesoEnUMat, 3) }}</span>
                                    <span><strong>$ </strong> {{ Util::Miles($row->precioU, 2) }}</span><br>
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
