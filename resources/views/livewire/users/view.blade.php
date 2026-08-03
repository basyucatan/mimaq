@section('title', __('Gestión de Usuarios'))
<div class="container-fluid p-0">
    <div class="row g-2">
        <div class="col-12 col-md-4 col-lg-3">
            <div class="cardPrin">
                <div class="cardPrin-header">
                    <span>Usuarios y Roles</span>
                </div>
                <div class="cardPrin-body d-flex flex-column gap-2">
                    <div>
                        <label class="etiBase">Gestión</label>
                        <select wire:model.live="vistaActiva" class="inpBase">
                            <option value="usuarios">Usuarios</option>
                            @if($this->puedeGestionarEstructura)
                                <option value="roles">Roles</option>
                                <option value="permisos">Permisos</option>
                            @endif
                        </select>
                    </div>
                    <div>
                        <label class="etiBase">Buscar</label>
                        <div class="position-relative">
                            <input wire:model.lazy="keyWord" class="inpBase" 
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
                    </div>
                    @if($vistaActiva === 'usuarios')
                        <div>
                            <label class="etiBase">Filtrar por Rol</label>
                            <select wire:model.live="IdRolFiltro" class="inpBase">
                                <option value="">Todos los Roles</option>
                                @foreach ($roles as $key => $value)
                                    <option value="{{ $key }}">{{ $value }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endif
                    <div class="mt-2">
                        <button class="bot botVerde w-100 d-flex align-items-center justify-content-center gap-2" wire:click="create">
                            <i class="bi bi-file-earmark-plus"></i>
                            <span>Nuevo {{ ucfirst(substr($vistaActiva, 0, -1)) }}</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-8 col-lg-9">
            <div class="cardPrin">
                <div class="cardPrin-header">
                    <span>Listado de {{ ucfirst($vistaActiva) }}</span>
                </div>
                <div class="cardPrin-body">
                    @include('livewire.users.modals')
                    <table class="table tabBase">
                        <thead>
                            @if($vistaActiva === 'usuarios')
                                <tr>
                                    <th>Nombre</th>
                                    <th>Teléfono</th>
                                    <th>Roles</th>
                                    <th>Acciones</th>
                                </tr>
                            @elseif($vistaActiva === 'roles')
                                <tr>
                                    <th>Nombre</th>
                                    <th>Nivel</th>
                                    <th>Permisos</th>
                                    <th>Acciones</th>
                                </tr>
                            @elseif($vistaActiva === 'permisos')
                                <tr>
                                    <th>Nombre</th>
                                    <th>Acciones</th>
                                </tr>
                            @endif
                        </thead>
                        <tbody>
                            @forelse($this->listado as $row)
                                <tr>
                                    @if($vistaActiva === 'usuarios')
                                        <td>{{ $row->name }}</td>
                                        <td>{{ $row->telefono }}</td>
                                        <td>
                                            @foreach($row->roles as $r)
                                                <span class="badge bg-secondary">{{ $r->name }}</span>
                                            @endforeach
                                        </td>
                                    @elseif($vistaActiva === 'roles')
                                        <td>{{ $row->name }}</td>
                                        <td>{{ $row->nivel }}</td>
                                        <td>
                                            @foreach($row->permissions as $p)
                                                <span class="badge bg-light text-dark border">{{ $p->name }}</span>
                                            @endforeach
                                        </td>
                                    @elseif($vistaActiva === 'permisos')
                                        <td>{{ $row->name }}</td>
                                    @endif
                                    <td width="60">
                                        <div class="d-flex gap-2">
                                            <button wire:click="edit({{ $row->id }})" class="bot botNaranja">
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
                                <tr>
                                    <td colspan="100%" class="text-center">No se encontraron datos.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    @if(method_exists($this->listado, 'links'))
                        <div class="float-end">
                            {{ $this->listado->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>