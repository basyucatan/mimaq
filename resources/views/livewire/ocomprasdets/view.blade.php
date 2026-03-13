@section('title', __('Ocomprasdets'))
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="cardPrin">
                <div class="cardPrin-header">
                    <div>
                        Ocomprasdets
                    </div>
                    <div>
                        <input wire:model.live="keyWord" type="text" class="inpSolo" placeholder="Buscar">
                    </div>
                    <div>
                        <button class="bot botVerde" wire:click="create" title="Nuevo Ocomprasdet">
                            <i class="bi bi-file-earmark-plus"></i>
                        </button>                   
                    </div>                
                </div>
                <div class="cardPrin-body">
                    @include('livewire.ocomprasdets.modals')
                    <div class="table-responsive">
                        <table class="table tabBase ch">
                            <thead>
                                <tr>
								<th>Idocompra</th>
								<th>Idmatcosto</th>
								<th>Cantidad</th>
								<th>Costou</th>
<th>Acciones</th></tr>
                            </thead>
                            <tbody>
                                @forelse($ocomprasdets as $row)
                                    <tr>
                                        
								<td>{{ $row->IdOCompra }}</td>
								<td>{{ $row->IdMatCosto }}</td>
								<td>{{ $row->cantidad }}</td>
								<td>{{ $row->costoU }}</td>

                                        <td width="60">
                                            <div class="d-flex justify-content-around align-items-center gap-1">
                                                <button wire:click="edit({{ $row->id }})"
                                                        class="bot botNaranja"
                                                        title="Editar">
                                                    <i class="bi-pencil-square"></i>
                                                </button>
                                                <button wire:click="destroy({{ $row->id }})"
                                                        class="bot botRojo"
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
                        <div class="float-end">
                            {{ $ocomprasdets->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
