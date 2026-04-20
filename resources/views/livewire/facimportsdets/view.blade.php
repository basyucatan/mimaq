@section('title', __('Facimportsdets'))
<div class="container-fluid p-0">
    <div class="row g-0 justify-content-center">
        <div class="col-12">
            <div class="cardSec">
                <div class="cardSec-header">
                    <span>Factura <span class="badge bg-danger fs-5">{{ $Factura?->factura }}</span></span>
                    <div>
                        <input wire:model.live="keyWord" type="text" class="inpSolo" onfocus="this.select()"
                            placeholder="Buscar">
                    </div>
                    <div>
                        <button class="bot botVerde" wire:click="create" title="Nuevo Facimportsdet">
                            <i class="bi bi-file-earmark-plus"></i>
                        </button>
                    </div>
                </div>
                <div class="cardSec-body">
                    <div class="d-flex justify-content-end mb-2">
                        {{ $facimportsdets->links() }}
                    </div>
                    @include('livewire.facimportsdets.modals')
                    <div class="tablaCont">
                        <table class="table tabBase ch">
                            <thead>
                                <tr>
                                    <th>Idfacimport</th>
                                    <th>Identradamex</th>
                                    <th>Idorigen</th>
                                    <th>Idmaterial</th>
                                    <th>Cantidad</th>
                                    <th>Preciou</th>
                                    <th>Pesoenumat</th>
                                    <th>Pesog</th>
                                    <th>Idsize</th>
                                    <th>Idforma</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($facimportsdets as $row)
                                    <tr>

                                        <td>{{ $row->IdFacImport }}</td>
                                        <td>{{ $row->IdEntradaMex }}</td>
                                        <td>{{ $row->IdOrigen }}</td>
                                        <td>{{ $row->IdMaterial }}</td>
                                        <td>{{ $row->cantidad }}</td>
                                        <td>{{ $row->precioU }}</td>
                                        <td>{{ $row->pesoEnUMat }}</td>
                                        <td>{{ $row->pesoG }}</td>
                                        <td>{{ $row->IdSize }}</td>
                                        <td>{{ $row->IdForma }}</td>

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
