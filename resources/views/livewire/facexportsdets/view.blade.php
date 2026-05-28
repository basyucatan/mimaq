@section('title', __('Facexportsdets'))
<div class="container-fluid p-0">
    <div class="row g-0 justify-content-center">
        <div class="col-12">
            <div class="cardPrin">
<div class="cardPrin-header">
    <span>Productos</span>
    <div class="me-2 position-relative" style="display:inline-block;">
        <input wire:model.lazy="keyWord" class="inpSolo" wire:keydown.escape="$set('keyWord','')" onfocus="this.select()" placeholder="Buscar...">
        @if ($keyWord)
            <span wire:click="$set('keyWord','')" class="bot botNegro botChico" style="position: absolute; right: 6px; top: 50%; transform: translateY(-50%); cursor: pointer;">
                X
            </span>
        @endif
    </div>
    <div>
        <button class="bot botVerde botChico" wire:click="plExport" 
            wire:loading.attr="disabled" wire:target="plExport" title="Packing List">
            <span wire:loading.remove wire:target="plExport"><i class="bi bi-printer"></i></span>
            <span wire:loading wire:target="plExport">⏳</span>
        </button>
        <button class="bot botAzul botChico" wire:click="factura" 
            wire:loading.attr="disabled" wire:target="factura" title="Factura">
            <span wire:loading.remove wire:target="factura"><i class="bi bi-printer"></i></span>
            <span wire:loading wire:target="factura">⏳</span>
        </button>
        <button class="bot botVerde" wire:click="create" title="Nuevo Facexportsdet">
            <i class="bi bi-file-earmark-plus"></i>
        </button>
    </div>
</div>
                <div class="cardPrin-body">
                    <div class="d-flex justify-content-end mb-2">
                        {{ $facexportsdets->links() }}
                    </div>
                    @include('livewire.facexportsdets.modals')
                    <div class="tablaCont">
                        <table class="table tabBase ch">
                            <thead>
                                <tr>
                                    <th>Bandeja</th>
                                    <th>Producto</th>
                                    <th>Arancel</th>
                                    <th>Cantidad</th>
                                    <th>Precio</th>
                                    <th>Peso</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($facexportsdets as $row)
                                    <tr>
                                        <td>{{ $row->Bandeja->codigoBandeja }}</td>
                                        <td>{{ $row->productoFinal }}</td>
                                        <td>{{ $row->arancel }}</td>
                                        <td>{{ $row->cantidad }}</td>
                                        <td>{{ $row->precioU }}</td>
                                        <td>{{ $row->pesoG }}</td>
                                        <td width="90">
                                            <div class="d-flex justify-content-around align-items-center gap-1">
                                                <button wire:click="verMaterials({{ $row->id }})" class="bot botNegro botChico" title="Historial de Movimientos">
                                                    🛎️
                                                </button>
                                                <button wire:click="edit({{ $row->id }})"
                                                    class="bot botNaranja botChico" title="Editar">
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
