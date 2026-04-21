@section('title', __('Facimportsdets'))
<div class="container-fluid p-0">
    <div class="row g-0 justify-content-center">
        <div class="col-12">
            <div class="cardPrin">
                <div class="cardPrin-header">
                    <span>Factura <span class="badge bg-danger">{{ $Factura?->factura }}</span></span>
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
                        <button class="bot botNaranja botChico" wire:click="crearEstilo">
                            <i class="bi bi-file-earmark-plus"></i>
                        </button>
                        <button class="bot botVerde botChico" wire:click="create" title="Nuevo Material">
                            <i class="bi bi-file-earmark-plus"></i>
                        </button>
                        <button class="bot botAzul botChico" wire:click="impresiones">
                            <i class="bi bi-printer"></i>
                        </button>
                    </div>
                </div>
                <div class="cardPrin-body">
                    <div class="d-flex justify-content-end mb-2">
                        {{ $facimportsdets->links() }}
                    </div>
                    @include('livewire.facimportsdets.modals')
                    <div class="tablaCont d-none d-md-block">
                        <table class="table tabBase ch">
                            <thead>
                                <tr>
                                    <th>Referencia</th>
                                    <th>Origen</th>
                                    <th>Material</th>
                                    <th>Cantidad</th>
                                    <th>Precio U</th>
                                    <th>Peso</th>
                                    <th>Propiedades</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($facimportsdets as $row)
                                    <tr>
                                        <td>{{ $row->IdEntradaMex }}</td>
                                        <td>{{ $row->Origen->origen ?? ''}}</td>
                                        <td>{{ $row->Material->material }}</td>
                                        <td>{{ Util::Miles($row->cantidad) }}</td>
                                        <td>{{ Util::Miles($row->precioU,2) }}</td>
                                        <td>{{ Util::Miles($row->pesoEnUMat,3) }}</td>
                                        <td>{!! $row->propiedades !!}</td>
                                        <td width="60">
                                            <div class="d-flex justify-content-around align-items-center gap-1">
                                                <button wire:click="edit({{ $row->id }})"
                                                    class="bot botNaranja botChico" title="Editar">
                                                    <i class="bi-pencil-square"></i>
                                                </button>
                                                <button wire:click="destroy({{ $row->id }})"
                                                    class="bot botRojo botChico"
                                                    onclick="confirm('¿Estás seguro de eliminar este registro?') || event.stopImmediatePropagation()">
                                                    <i class="bi bi-trash3-fill"></i>
                                                </button>
                                            </div>
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
                                    <div class="d-flex gap-1">
                                        <button wire:click="edit({{ $row->id }})"
                                            class="bot botNaranja botChico">
                                            <i class="bi-pencil-square"></i>
                                        </button>
                                        <button wire:click="destroy({{ $row->id }})"
                                            class="bot botRojo botChico"
                                            onclick="confirm('¿Estás seguro de eliminar este registro?') || event.stopImmediatePropagation()">
                                            <i class="bi bi-trash3-fill"></i>
                                        </button>
                                    </div>
                                </div>

                                <div class="cardSec-body small">
                                    <span><strong>Ref. </strong> {{ $row->IdEntradaMex }} ({{ $row->Origen->origen ?? ''}})</span>
                                    <span><strong>Peso: </strong> {{  Util::Miles($row->pesoEnUMat,3) }}</span>
                                    <span><strong>$ </strong> {{ Util::Miles($row->precioU,2) }}</span><br>
                                    <div>{!! $row->propiedades !!}</div>
                                </div>

                            </div>
                        @empty
                            <div class="text-center text-muted">
                                Sin registros
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
