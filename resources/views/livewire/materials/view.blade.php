@section('title', __('Materials'))
<div class="container-fluid p-0">
    <div class="row g-0 justify-content-center">
        <div class="col-12">
            <div class="cardPrin">
                <div class="cardPrin-header">
                    <span>Materials</span>
                    <div>
                        <input wire:model.live="keyWord" type="text" class="inpSolo"  onfocus="this.select()" placeholder="Buscar">
                    </div>
                    <div>
                        <button class="bot botVerde" wire:click="create" title="Nuevo Material">
                            <i class="bi bi-file-earmark-plus"></i>
                        </button>                   
                    </div>                
                </div>
                <div class="cardPrin-body">    
                    <div class="d-flex justify-content-end mb-2">
                        {{ $materials->links() }}
                    </div>                               
                    @include('livewire.materials.modals')
                    <div class="tablaCont">
                        <table class="table tabBase ch">
                            <thead>
                                <tr>
								<th>Idclase</th>
								<th>Idunidad</th>
								<th>Idunidadp</th>
								<th>Material</th>
								<th>Materiali</th>
								<th>Materialfiscal</th>
								<th>Abreviatura</th>
<th>Acciones</th></tr>
                            </thead>
                            <tbody>
                                @forelse($materials as $row)
                                    <tr>
                                        
								<td>{{ $row->IdClase }}</td>
								<td>{{ $row->IdUnidad }}</td>
								<td>{{ $row->IdUnidadP }}</td>
								<td>{{ $row->material }}</td>
								<td>{{ $row->materialI }}</td>
								<td>{{ $row->materialFiscal }}</td>
								<td>{{ $row->abreviatura }}</td>

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
