@section('title', __('Materialscostos'))
<div class="d-flex flex-column h-100">
    <div class="cardSec d-flex flex-column h-100" style="min-height: 0;">
        <div class="cardSec-header flex-shrink-0 d-flex flex-wrap align-items-center justify-content-between gap-2 p-2">
            <div class="d-flex align-items-center gap-2 flex-grow-1">
                <span class="fw-bold d-none d-md-inline">Costos</span>
                <input wire:model.live="keyWord" type="text" class="inpSolo flex-grow-1" placeholder="Buscar..." style="max-width: 250px;">
            </div>
            <div class="d-flex gap-1">
                <button class="bot botVerde" wire:click="create" title="Nuevo"><i class="bi bi-file-earmark-plus"></i></button>
                <button class="bot botVerde" wire:click="variantes" title="Variantes"><i class="bi-cash"></i></button>
                <button class="bot botVerde d-sm-inline" wire:click="editOriDes">Ori🡆Des</button>
                <button class="bot botVerde d-sm-inline" wire:click="editArbolDes">Arb🡆Des</button>
                <button class="bot botVerde" wire:click="ubicacionesPDF">🖨️</button>
            </div>                    
        </div>                
        <div class="cardSec-body flex-grow-1 d-flex flex-column p-0" style="min-height: 0; overflow: hidden;">
            @include('livewire.materialscostos.modals')
            @include('livewire.materialscostos.modalsUbi')
            @include('livewire.materialscostos.modalsArbolDes')
            @include('livewire.materialscostos.modalsOriDes')
            <div class="table-responsive flex-grow-1" style="overflow-y: auto;">
                <table class="table tabBase mb-0">
                    <thead style="position: sticky; top: 0; background: white; z-index: 10;">
                        <tr>
                            <th class="d-none d-md-table-cell">#</th>
                            <th>Color</th>
                            <th>Ref.</th>
                            <th class="d-none d-md-table-cell">RefBodega</th>
                            <th class="d-none d-md-table-cell">Vidrio</th>
                            <th class="d-none d-md-table-cell">Barra</th>
                            <th class="d-none d-md-table-cell">Panel</th>
                            <th class="d-none d-md-table-cell" style="text-align: right;">Costo</th>
                            <th class="d-none d-md-table-cell" style="text-align: center;">Moneda</th>
                            <th class="d-none d-md-table-cell" style="text-align: center;">$ MXN</th>
                            <th class="text-end">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($materialscostos as $row)
                        <tr>
                            <td class="d-none d-md-table-cell">{{ $loop->iteration }}</td>
                            <td>
                                @if($row->Color)
                                    <span class="cuadroColor" style="background-color: {{ $row->Color->colorRgba }}"></span>
                                    <span class="small">{{ $row->Color->color }}</span>
                                @else
                                    <span class="cuadroColor sinColor" style="color: red;">✕</span>
                                @endif
                            </td>
                            <td>{{ $row->referencia }}</td>
                            <td class="d-none d-md-table-cell" wire:click="editUbi({{ $row->id }})" style="cursor: pointer; user-select: none;">
                                {{ $row->UbiCodificada }}
                            </td>
                            <td class="d-none d-md-table-cell">
                                @if ($row->Vidrio)
                                    {{ $row->Vidrio->vidrio }} {{ $row->Vidrio->grosor }} mm
                                @endif
                            </td>
                            <td class="d-none d-md-table-cell">{{ $row->Barra->descripcion ?? null }}</td>
                            <td class="d-none d-md-table-cell">{{ $row->Panel->panel ?? null }}</td>
                            <td class="d-none d-md-table-cell" style="text-align: right;">{{ App\Models\Util::Dinero($row->costo, 2) }}</td>
                            <td class="d-none d-md-table-cell" style="text-align: center;">{{ $row->Moneda->abreviatura ?? null }}</td>
                            <td class="d-none d-md-table-cell text-center">{{ number_format($row->valores['valorURealMXN'] ?? 0, 2) }}</td>
                            <td width="120">
                                <div class="d-flex justify-content-end align-items-center gap-1">
                                    <a wire:click="edit({{ $row->id }})" class="bot botNaranja" title="Editar"><i class="bi-pencil-square"></i></a>
                                    <a wire:click="destroy({{ $row->id }})" class="bot botRojo" onclick="confirm('¿Estás seguro?') || event.stopImmediatePropagation()"><i class="bi-trash3-fill"></i></a>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="100%" class="text-center">No se encontraron datos.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>