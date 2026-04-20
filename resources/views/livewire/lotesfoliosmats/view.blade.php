@section('title', __('Lotesfoliosmats'))
<div class="container-fluid p-0">
    <div class="row g-0 justify-content-center">
        <div class="col-12">
            <div class="cardPrin">
                <div class="cardPrin-header">
                    <span>Lotesfoliosmats</span>
                    <div>
                        <input wire:model.live="keyWord" type="text" class="inpSolo"  onfocus="this.select()" placeholder="Buscar">
                    </div>
                    <div>
                        <button class="bot botVerde" wire:click="create" title="Nuevo Lotesfoliosmat">
                            <i class="bi bi-file-earmark-plus"></i>
                        </button>                   
                    </div>                
                </div>
                <div class="cardPrin-body">    
                    <div class="d-flex justify-content-end mb-2">
                        {{ $lotesfoliosmats->links() }}
                    </div>                               
                    @include('livewire.lotesfoliosmats.modals')
                    <div class="tablaCont">
                        <table class="table tabBase ch">
                            <thead>
                                <tr>
								<th>Idlotesfolio</th>
								<th>Idfacimportsdet</th>
								<th>Cantidad</th>
								<th>Idmaterial</th>
								<th>Pesoenumat</th>
								<th>Idsize</th>
								<th>Idforma</th>
<th>Acciones</th></tr>
                            </thead>
                            <tbody>
                                @forelse($lotesfoliosmats as $row)
                                    <tr>
                                        
								<td>{{ $row->IdLotesFolio }}</td>
								<td>{{ $row->IdFacImportsDet }}</td>
								<td>{{ $row->cantidad }}</td>
								<td>{{ $row->IdMaterial }}</td>
								<td>{{ $row->pesoEnUMat }}</td>
								<td>{{ $row->IdSize }}</td>
								<td>{{ $row->IdForma }}</td>

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
