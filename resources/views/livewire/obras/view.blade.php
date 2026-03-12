<div class="cardSec mt-4">
    <div class="cardSec-header d-flex justify-content-between align-items-center">
        <div class="fw-bold">Obras</div>
        <div class="d-flex gap-2">
            <input wire:model.live="keyWord" type="text" class="inpSolo" placeholder="Buscar obra">
            <button class="bot botVerde" wire:click="create" title="Nueva Obra">
                <i class="bi bi-file-earmark-plus"></i>
            </button>
        </div>
    </div>
    <div class="cardSec-body">
        @include('livewire.obras.modals')
        {{-- Vista para PC (Tabla) --}}
        <div class="table-responsive d-none d-md-block">
            <table class="table tabBase ch">
                <thead>
                    <tr>
                        <th>Obra</th>
                        <th>Gmaps</th>
                        <th width="100" class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($obras as $row)
                    <tr>
                        <td>{{ $row->obra }}</td>
                        <td>
                            @if($row->gmaps)
                                <a href="{{ $row->gmaps }}" target="_blank" class="text-primary text-decoration-none">
                                    <i class="bi bi-geo-alt-fill"></i> Ver mapa
                                </a>
                            @else
                                <span class="text-muted small">N/A</span>
                            @endif
                        </td>
                        <td>
                            <div class="d-flex justify-content-center gap-1">
                                <button wire:click="edit({{ $row->id }})" class="bot botNaranja" title="Editar">
                                    <i class="bi bi-pencil-square"></i>
                                </button>
                                <button wire:click="destroy({{ $row->id }})" class="bot botRojo" title="Eliminar"
                                    onclick="confirm('¿Estás seguro de eliminar este registro?') || event.stopImmediatePropagation()">
                                    <i class="bi bi-trash3-fill"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="text-center text-muted">Sin obras registradas</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{-- Vista para Móvil (Cards) --}}
        <div class="d-md-none">
            @forelse($obras as $row)
            <div class="card mb-2 border-start border-4 border-primary shadow-sm">
                <div class="card-body p-2">
                    <div class="d-flex justify-content-between align-items-start mb-1">
                        <span class="fw-bold text-primary">{{ $row->obra }}</span>
                        <div class="d-flex gap-1">
                            <button wire:click="edit({{ $row->id }})" class="bot botNaranja">
                                <i class="bi bi-pencil"></i>
                            </button>
                            <button wire:click="destroy({{ $row->id }})" class="bot botRojo"
                                onclick="confirm('¿Eliminar?') || event.stopImmediatePropagation()">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                    </div>
                    <div class="small">
                        @if($row->gmaps)
                        <div class="text-muted">Ubicación: 
                            <a href="{{ $row->gmaps }}" target="_blank" class="text-info text-decoration-none">Abrir enlace</a>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @empty
            <div class="text-center p-3 text-muted">Sin obras registradas</div>
            @endforelse
        </div>
        <div class="mt-2">
            {{ $obras->links() }}
        </div>
    </div>
</div>