@section('title', __('Unidads'))
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="cardPrin">
                <div class="cardPrin-header gap-2">
                    <span class="fs-5">Unidades</span>
                    <input wire:model.live="keyWord" type="text" class="inpSolo" placeholder="Buscar">
                    <button class="bot botVerde" wire:click="create" title="Nuevo Unidad">
                        <i class="bi bi-file-earmark-plus"></i>
                    </button>
                </div>
                <div class="cardPrin-body">
                    @include('livewire.unidads.modals')
                    <div class="table-responsive">
                        <table class="table tabBase">
                            <thead>
                                <tr>
                                    <th>Tipo</th>
                                    <th>Unidad</th>
                                    <th>Abreviatura</th>
                                    <th>Factor Conv.</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($unidads as $row)
                                    <tr>
                                        <td>{{ $row->tipo }}</td>
                                        <td>{{ $row->unidad }}</td>
                                        <td>{{ $row->abreviatura }}</td>
                                        <td>{{ $row->factorConversion }}</td>
                                        <td width="120">
                                            <div class="d-flex justify-content-around align-items-center gap-1">
                                                <a wire:click="edit({{ $row->id }})" class="bot botNaranja"
                                                    title="Editar">
                                                    <i class="bi-pencil-square"></i>
                                                </a>
                                                <a wire:click="destroy({{ $row->id }})" class="bot botRojo"
                                                    onclick="confirm('¿Estás seguro de eliminar este registro?') || event.stopImmediatePropagation()">
                                                    <i class="bi-trash3-fill"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="100%" class="text-center">No se encontraron datos.</td>
                                    </tr>
                                @endforelse
                            </tbody>

                        </table>
                        <div class="float-end">
                            {{ $unidads->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
