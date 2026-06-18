@section('title', __('Foliosmats'))
<div class="container-fluid p-0">
    <div class="row g-0 justify-content-center">
        <div class="col-12">
            <div class="cardPrin">
                <div class="cardPrin-header" style="cursor: move;">
                    <span>Materiales del folio</span>
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
                    <div>
                        <button class="bot botVerde" wire:click="create" title="Nuevo Foliosmat">
                            <i class="bi bi-file-earmark-plus"></i>
                        </button>
                    </div>
                </div>
                <div class="cardPrin-body">
                    <div class="d-flex justify-content-end mb-2">
                        {{ $foliosmats->links() }}
                    </div>
                    @include('livewire.foliosmats.modals')
                    <div class="tablaCont">
                        <table class="table tabBase ch">
                            <thead>
                                <tr>
                                    <th>Referencia</th>
                                    <th>Material</th>
                                    <th>Rol</th>
                                    <th>Cantidad</th>
                                    <th>Peso(g)</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($foliosmats as $row)
                                    <tr>
                                        <td title="id: {{ $row->id ?? '' }} RefId: {{ $row->facimportsdet->id ?? '' }}">
                                            {{ $row->facimportsdet->IdEntradaMex ?? '' }}</td>
                                        <td>{{ $row->Material->material }} {{ $row->facimportsdet->propsTot ?? '' }}
                                            @if ($row->facimportsdet?->diferencias)
                                                <div class="mt-1 px-2 py-1 rounded bg-warning-subtle border-start border-3 border-warning"
                                                    style="font-size: 0.7rem;">
                                                    <span class="text-dark">
                                                        <i class="bi bi-info-circle me-1"></i>
                                                        {{ $row->facimportsdet->difsFormat }}
                                                    </span>
                                                </div>
                                            @endif
                                        </td>
                                        <td>{{ $row->tipo->tipo }}</td>
                                        <td>{{ Util::Miles($row->cantidad, 0) }}</td>
                                        <td>{{ Util::Miles($row->pesoG, 4) }}</td>
                                        <td width="60">
                                            <div class="d-flex justify-content-around align-items-center gap-1">
                                                <button wire:click="edit({{ $row->id }})"
                                                    class="bot botChico {{ $row->integrado ? 'botGris' : 'botNaranja' }}"
                                                    title="Editar" @if ($row->integrado) disabled @endif>
                                                    <i class="bi-pencil-square"></i>
                                                </button>
                                                <button wire:click="destroy({{ $row->id }})"
                                                    class="bot botChico {{ $row->integrado ? 'botGris' : 'botRojo' }}"
                                                    title="Eliminar" @if ($row->integrado) disabled @endif
                                                    onclick="confirm('¿Estás seguro de eliminar este registro?') || event.stopImmediatePropagation()">
                                                    <i class="bi-trash3-fill"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
