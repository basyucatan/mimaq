@section('title', __('Bandejas'))
<div class="container-fluid p-0">
    <div class="row g-0 justify-content-center">
        <div class="col-12">
            <div class="cardPrin">
                <div class="cardPrin-header">
                    <span>Bandejas</span>
                    <div class="me-2 position-relative" style="display:inline-block;">
                        <input wire:model.lazy="keyWord" class="inpSolo" 
                        wire:keydown.escape="$set('keyWord','')"
                        onfocus="this.select()" placeholder="Buscar...">
                        @if($keyWord)
                            <span wire:click="$set('keyWord','')" 
                                class="bot botNegro botChico"
                                style="position: absolute; right: 6px; top: 50%; transform: translateY(-50%); cursor: pointer;">
                                X
                            </span>
                        @endif
                    </div>
                    <div>
                        <button class="bot botVerde" wire:click="create" title="Nuevo Bandeja">
                            <i class="bi bi-file-earmark-plus"></i>
                        </button>                   
                    </div>                
                </div>
                <div class="cardPrin-body">    
                    <div class="d-flex justify-content-end mb-2">
                        {{ $bandejas->links() }}
                    </div>                               
                    @include('livewire.bandejas.modals')
                    <div class="tablaCont">
                        <table class="table tabBase ch">
                            <thead>
                                <tr>
								<th>Idfolio</th>
								<th>Idfacturaexport</th>
								<th>Cantidad</th>
								<th>Pesometalinicial</th>
								<th>Pesometalactual</th>
								<th>Pesopiedrasconstante</th>
								<th>Mermametalacumulada</th>
								<th>Idprocesoactual</th>
								<th>Enboveda</th>
								<th>Habilitada</th>
								<th>Estatus</th>
<th>Acciones</th></tr>
                            </thead>
                            <tbody>
                                @forelse($bandejas as $row)
                                    <tr>
                                        
								<td>{{ $row->IdFolio }}</td>
								<td>{{ $row->IdFacturaExport }}</td>
								<td>{{ $row->cantidad }}</td>
								<td>{{ $row->pesoMetalInicial }}</td>
								<td>{{ $row->pesoMetalActual }}</td>
								<td>{{ $row->pesoPiedrasConstante }}</td>
								<td>{{ $row->mermaMetalAcumulada }}</td>
								<td>{{ $row->IdProcesoActual }}</td>
								<td>{{ $row->enBoveda }}</td>
								<td>{{ $row->habilitada }}</td>
								<td>{{ $row->estatus }}</td>

                                        <td width="60">
                                            <div class="d-flex justify-content-around align-items-center gap-1">
                                                <button wire:click="edit({{ $row->id }})"
                                                        class="bot botNaranja botChico"
                                                        title="Editar">
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
