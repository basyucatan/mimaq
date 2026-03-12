<div class="cardSec mt-4">
    <div class="cardSec-header d-flex justify-content-between align-items-center">
        <div class="fw-bold">Cuentas Bancarias</div>
        <div class="d-flex gap-2">
            <input wire:model.live="keyWord" type="text" class="inpSolo" placeholder="Buscar cuenta">
            <button class="bot botVerde" wire:click="create" title="Nueva Cuenta">
                <i class="bi bi-plus-lg"></i>
            </button>
        </div>
    </div>
    <div class="cardSec-body">
        @include('livewire.empresascuentas.modals')
        {{-- Vista para PC (Tabla) --}}
        <div class="table-responsive d-none d-md-block">
            <table class="table tabBase ch">
                <thead>
                    <tr>
                        <th>Banco</th>
                        <th>Cuenta CLABE</th>
                        <th>Número de Cuenta</th>
                        <th width="100" class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($empresascuentas as $row)
                    <tr>
                        <td>{{ $row->banco }}</td>
                        <td><code>{{ $row->cuentaClabe }}</code></td>
                        <td>{{ $row->cuenta }}</td>
                        <td>
                            <div class="d-flex justify-content-center gap-1">
                                <button wire:click="edit({{ $row->id }})" class="bot botNaranja" title="Editar">
                                    <i class="bi bi-pencil-square"></i>
                                </button>
                                <button wire:click="destroy({{ $row->id }})" class="bot botRojo" title="Eliminar"
                                    onclick="confirm('¿Eliminar cuenta?') || event.stopImmediatePropagation()">
                                    <i class="bi bi-trash3-fill"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="text-center text-muted">Sin cuentas registradas</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{-- Vista para Móvil (Cards) --}}
        <div class="d-md-none">
            @forelse($empresascuentas as $row)
            <div class="card mb-2 border-start border-4 border-info shadow-sm">
                <div class="card-body p-2">
                    <div class="d-flex justify-content-between align-items-start mb-1">
                        <span class="fw-bold text-primary">{{ $row->banco }}</span>
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
                        <div class="text-muted">CLABE: <span class="text-dark">{{ $row->cuentaClabe }}</span></div>
                        <div class="text-muted">Cuenta: <span class="text-dark">{{ $row->cuenta }}</span></div>
                    </div>
                </div>
            </div>
            @empty
            <div class="text-center p-3 text-muted">Sin cuentas registradas</div>
            @endforelse
        </div>
        <div class="mt-2">
            {{ $empresascuentas->links() }}
        </div>
    </div>
</div>