@section('title', __('Estilosdets'))
<div class="container-fluid p-0">
    <div class="row g-0 justify-content-center">
        <div class="col-12">
            <div class="cardSec">
                <div class="cardSec-header">
                    <span>Materiales del Estilo</span>
                    <div>
                        <input wire:model.live="keyWord" type="text" class="inpSolo" onfocus="this.select()"
                            placeholder="Buscar">
                    </div>
                    <div>
                        <button class="bot botVerde" wire:click="create" title="Nuevo Estilosdet">
                            <i class="bi bi-file-earmark-plus"></i>
                        </button>
                    </div>
                </div>
                <div class="cardSec-body">
                    <div class="d-flex justify-content-end mb-2">
                        {{ $estilosdets->links() }}
                    </div>
                    @include('livewire.estilosdets.modals')
                    <div class="tablaCont">
                        <table class="table tabBase ch">
                            <thead>
                                <tr>
                                    <th>Cantidad</th>
                                    <th>Material</th>
                                    <th>Size</th>
                                    <th>Forma</th>
                                    <th>Estilo Ensamble</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($estilosdets as $row)
                                    <tr>

                                        <td>{{ $row->cantidad }}</td>
                                        <td>{{ $row->Material->material }}</td>
                                        <td>{{ $row->Size->size ?? '' }}</td>
                                        <td>{{ $row->Forma->forma ?? '' }}</td>
                                        <td>{{ $row->estiloY }}</td>

                                        <td width="60">
                                            <div class="d-flex justify-content-around align-items-center gap-1">
                                                <button wire:click="edit({{ $row->id }})" class="bot botNaranja"
                                                    title="Editar">
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
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
