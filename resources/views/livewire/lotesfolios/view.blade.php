@section('title', __('Lotesfolios'))
<div class="container-fluid p-0">
    <div class="row g-0 justify-content-center">
        <div class="col-12">
            <div class="cardPrin">
                <div class="cardPrin-header">
                    <span>Lotesfolios</span>
                    <div>
                        <input wire:model.live="keyWord" type="text" class="inpSolo"  onfocus="this.select()" placeholder="Buscar">
                    </div>
                    <div>
                        <button class="bot botVerde" wire:click="create" title="Nuevo Lotesfolio">
                            <i class="bi bi-file-earmark-plus"></i>
                        </button>                   
                    </div>                
                </div>
                <div class="cardPrin-body">    
                    <div class="d-flex justify-content-end mb-2">
                        {{ $lotesfolios->links() }}
                    </div>                               
                    @include('livewire.lotesfolios.modals')
                    <div class="tablaCont">
                        <table class="table tabBase ch">
                            <thead>
                                <tr>
								<th>Idlote</th>
								<th>Idestilo</th>
								<th>Cantidad</th>
								<th>Preciou</th>
								<th>Peso</th>
								<th>Jobstyle</th>
								<th>Fechaven</th>
<th>Acciones</th></tr>
                            </thead>
                            <tbody>
                                @forelse($lotesfolios as $row)
                                    <tr>
                                        
								<td>{{ $row->IdLote }}</td>
								<td>{{ $row->IdEstilo }}</td>
								<td>{{ $row->cantidad }}</td>
								<td>{{ $row->precioU }}</td>
								<td>{{ $row->peso }}</td>
								<td>{{ $row->jobStyle }}</td>
								<td>{{ $row->fechaVen }}</td>

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
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
