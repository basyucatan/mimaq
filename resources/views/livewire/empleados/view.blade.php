@section('title', __('Empleados'))
<div class="container-fluid p-0">
    <div class="row g-0 justify-content-center">
        <div class="col-12">
            <div class="cardPrin">
                <div class="cardPrin-header" style="cursor: move;">
                    <span>Empleados</span>
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
                        <button class="bot botVerde" wire:click="create" title="Nuevo Empleado">
                            <i class="bi bi-file-earmark-plus"></i>
                        </button>
                    </div>
                </div>
                <div class="cardPrin-body">
                    <div class="d-flex justify-content-end mb-2">
                        {{ $empleados->links() }}
                    </div>
                    @include('livewire.empleados.modals')
                    <div class="tablaCont">
                        <table class="table tabBase ch">
                            <thead>
                                <tr>
                                    <th>Numero</th>
                                    <th>Empleado</th>
                                    <th>Depto</th>
                                    <th>Vigente</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($empleados as $row)
                                    <tr>
                                        <td>{{ $row->numero }}</td>
                                        <td>{{ $row->empleado }}</td>
                                        <td>{{ $row->depto->depto }}</td>
                                        <td class="text-center">
                                            <div class="form-check form-switch d-inline-block">
                                                <input
                                                    class="form-check-input {{ $row->vigente ? 'bg-success' : 'bg-primary' }} border-0"
                                                    type="checkbox" role="switch" id="sw{{ $row->id }}"
                                                    @checked($row->vigente) disabled>
                                            </div>
                                        </td>
                                        <td width="60">
                                            <div class="d-flex justify-content-around align-items-center gap-1">
                                                <button wire:click="edit({{ $row->id }})"
                                                    class="bot botNaranja botChico" title="Editar">
                                                    <i class="bi-pencil-square"></i>
                                                </button>
                                                <button wire:click="destroy({{ $row->id }})"
                                                    class="bot botRojo botChico"
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
