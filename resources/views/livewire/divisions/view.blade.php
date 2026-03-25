@section('title', __('Divisions'))
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="cardPrin">
                <div class="cardPrin-header">
                    <div>
                        Divisions
                    </div>
                    <div>
                        <input wire:model.live="keyWord" type="text" class="inpSolo" placeholder="Buscar">
                    </div>
                    <div>
                        <button class="bot botVerde" wire:click="create" title="Nuevo Division">
                            <i class="bi bi-file-earmark-plus"></i>
                        </button>
                    </div>
                </div>
                <div class="cardPrin-body">
                    @include('livewire.divisions.modals')
                    <div class="table-responsive">
                        <table class="table tabBase ch">
                            <thead>
                                <tr>
                                    <th>Division</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($divisions as $row)
                                    <tr>

                                        <td>{{ $row->division }}</td>

                                        <td width="60">
                                            <div class="d-flex justify-content-around align-items-center gap-1">
                                                <button wire:click="edit({{ $row->id }})" class="bot botNaranja"
                                                    title="Editar">
                                                    <i class="bi-pencil-square"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                @endforelse
                            </tbody>
                        </table>
                        <div class="float-end">
                            {{ $divisions->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
