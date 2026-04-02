@section('title', __('Users'))
<div class="container-fluid p-0">
    <div class="row g-0 justify-content-center">
        <div class="col-12">
            <div class="cardPrin">
                <div class="cardPrin-header">
                    <span>Users</span>
                    <div>
                        <input wire:model.live="keyWord" type="text" class="inpSolo" placeholder="Buscar">
                    </div>
                    <button class="bot botVerde" wire:click="create">
                        <i class="bi bi-file-earmark-plus"></i>
                    </button>
                </div>
                <div class="cardPrin-body">
                    @include('livewire.users.modals')
                    <table class="table tabBase">
                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>Telefono</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($users as $row)
                                <tr>
                                    <td>{{ $row->name }}</td>
                                    <td>{{ $row->telefono }}</td>
                                    <td width="60">
                                        <div class="d-flex gap-3">
                                            <button wire:click="edit({{ $row->id }})" class="bot botNaranja">
                                                <i class="bi-pencil-square"></i>
                                            </button>
                                            <button wire:click="destroy({{ $row->id }})" class="bot botRojo"
                                                onclick="confirm('¿Estás seguro de eliminar este registro?') || event.stopImmediatePropagation()">
                                                <i class="bi-trash3-fill"></i>
                                            </button>
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
                        {{ $users->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
