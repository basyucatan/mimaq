<div class="cardSec">
    <div class="cardSec-header d-flex gap-2">
        <span class="fw-bold">Tabla</span>
        <div>
            <input wire:model.live="keyWord" type="text" class="inpSolo" placeholder="Buscar">
        </div>
        <div class="bot botVerde" wire:click="create" title="Nueva Tabla">
            <i class="bi bi-file-earmark-plus"></i>
        </div>
    </div>
    <div class="cardSec-body" style="height:200px; max-height:50vh; overflo-y: auto;">
        @include('livewire.tablaherrajes.modals')  
        <div class="table-responsive">
            <table class="table tabBase ch">
                <div class="float-end">
                    {{ $tablaherrajes->links() }}
                </div>              
                <thead>
                    <tr>
                        <th>Marca</th>
                        <th>Linea</th>
                        <th>Tabla</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tablaherrajes as $row)
                        <tr wire:click="elegir({{ $row->id }})"
                            style="cursor: pointer; user-select: none;">
                            <td>{{ $row->Linea->Marca->marca }}</td>
                            <td>{{ $row->Linea->linea }}</td>
                            <td>
                                {{ $row->tablaHerraje }}
                            </td>
                            <td width="80">
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
        </div>
    </div>
</div>        