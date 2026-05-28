@section('title', __('Facexportsmats'))
<div class="container-fluid p-0">
    <div class="row g-0 justify-content-center">
        <div class="col-12">
            <div class="cardPrin">
                <div class="cardPrin-header">
                    <span>Materiales</span>
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
                    {{-- <div>
                        <button class="bot botVerde" wire:click="create" title="Nuevo Facexportsmat">
                            <i class="bi bi-file-earmark-plus"></i>
                        </button>
                    </div> --}}
                </div>
                <div class="cardPrin-body">
                    @include('livewire.facexportsmats.modals')
                    <div class="tablaCont">
                        <table class="table tabBase ch">
                            <thead>
                                <tr>
                                    <th>Referencia</th>
                                    <th>Material</th>
                                    <th>Tipo</th>
                                    <th>Cantidad</th>
                                    <th>Peso</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($facexportsmats as $row)
                                    <tr>

                                        <td>{{ $row->facimportsdet->IdEntradaMex }}</td>
                                        <td>{{ $row->facimportsdet->material->material }}</td>
                                        <td>{{ $row->facimportsdet->material->clase->tipo->tipo }}</td>
                                        <td>{{ $row->cantidad }}</td>
                                        <td>{{ $row->pesoG }}</td>
                                        <td width="60">
                                            <div class="d-flex justify-content-around align-items-center gap-1">
                                                <button wire:click="edit({{ $row->id }})"
                                                    class="bot botNaranja botChico" title="Editar">
                                                    <i class="bi-pencil-square"></i>
                                                </button>
                                                {{-- <button wire:click="destroy({{ $row->id }})"
                                                    class="bot botRojo botChico"
                                                    onclick="confirm('¿Estás seguro de eliminar este registro?') || event.stopImmediatePropagation()">
                                                    <i class="bi-trash3-fill"></i>
                                                </button> --}}
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
