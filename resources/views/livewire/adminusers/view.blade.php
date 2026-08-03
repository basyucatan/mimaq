<div class="container-fluid py-2">
@if (session()->has('message'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('message') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row g-3">
        
        <!-- ================= PANEL IZQUIERDO (4 Columnas) ================= -->
        <div class="col-md-4">
            <div class="card shadow-sm border-0 mb-3">
                <div class="card-header bg-dark text-white fw-bold py-3">
                    <i class="bi bi-shield-fill-gear"></i> Panel de Administración
                </div>
                <div class="card-body d-flex flex-column gap-2 p-3">
                    <button wire:click="switchTab('users')" class="btn {{ $activeTab == 'users' ? 'btn-primary' : 'btn-outline-primary' }} text-start py-2">
                        <i class="bi bi-people-fill me-2"></i> Administrar Usuarios
                    </button>
                    <button wire:click="switchTab('roles')" class="btn {{ $activeTab == 'roles' ? 'btn-primary' : 'btn-outline-secondary' }} text-start py-2">
                        <i class="bi bi-shield-lock-fill me-2"></i> Administrar Roles
                    </button>
                    <button wire:click="switchTab('permissions')" class="btn {{ $activeTab == 'permissions' ? 'btn-primary' : 'btn-outline-secondary' }} text-start py-2">
                        <i class="bi bi-key-fill me-2"></i> Administrar Permisos
                    </button>
                </div>
            </div>

            @if($activeTab == 'users')
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-secondary text-white py-2">
                        <i class="bi bi-search me-1"></i> Filtrar Usuarios
                    </div>
                    <div class="card-body">
                        <div class="input-group">
                            <input wire:model.live.debounce.300ms="keyWord" type="text" class="form-control" placeholder="Buscar por nombre, correo, teléfono...">
                            @if($keyWord)
                                <button wire:click="$set('keyWord', '')" class="btn btn-outline-dark" type="button">X</button>
                            @endif
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- ================= PANEL DERECHO (8 Columnas) ================= -->
        <div class="col-md-8">
            <div class="card shadow-sm border-0">
                <div class="card-body p-4">
                    
                    <!-- ================= SECCIÓN 1: USUARIOS ================= -->
                    @if($activeTab == 'users')
                        
                        <!-- VISTA 1A: LISTADO DE USUARIOS -->
                        @if($userAction == 'list')
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <h4 class="m-0 fw-bold text-dark"><i class="bi bi-people me-2"></i> Lista de Usuarios</h4>
                                <button wire:click="createNewUser" class="btn btn-success">
                                    <i class="bi bi-person-plus-fill me-1"></i> Nuevo Usuario
                                </button>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-hover align-middle">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>Nombre / Depto</th>
                                            <th>Contacto</th>
                                            <th>Roles y Permisos</th>
                                            <th class="text-end">Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($users as $row)
                                            <tr>
                                                <td>
                                                    <span class="fw-bold">{{ $row->name }}</span><br>
                                                    <small class="text-muted"><i class="bi bi-building"></i> {{ $row->Depto->depto ?? 'Sin Departamento' }}</small>
                                                </td>
                                                <td>
                                                    <small><i class="bi bi-envelope"></i> {{ $row->email ?: 'Sin email' }}</small><br>
                                                    <small><i class="bi bi-telephone"></i> {{ $row->telefono }}</small>
                                                </td>
                                                <td>
                                                    <div class="mb-1">
                                                        @foreach($row->roles as $role)
                                                            <span class="badge bg-primary text-white">{{ $role->name }}</span>
                                                        @endforeach
                                                    </div>
                                                    <div>
                                                        @foreach($row->permissions as $perm)
                                                            <span class="badge bg-light text-dark border">{{ $perm->name }}</span>
                                                        @endforeach
                                                    </div>
                                                </td>
                                                <td class="text-end">
                                                    <div class="d-flex justify-content-end gap-1">
                                                        <button wire:click="editUser({{ $row->id }})" class="btn btn-sm btn-warning text-white" title="Editar completo">
                                                            <i class="bi bi-pencil-square"></i>
                                                        </button>
                                                        <button wire:click="deleteUser({{ $row->id }})" onclick="confirm('¿Estás seguro de eliminar este usuario?') || event.stopImmediatePropagation()" class="btn btn-sm btn-danger" title="Eliminar">
                                                            <i class="bi bi-trash3-fill"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="text-center text-muted py-4">No se encontraron usuarios registrados.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            <div class="mt-3">
                                {{ $users->links() }}
                            </div>

                        <!-- VISTA 1B & 1C: CREAR O EDITAR USUARIO COMPLETO -->
                        @elseif($userAction == 'create' || $userAction == 'edit')
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h4 class="m-0 fw-bold">
                                    {{ $userAction == 'create' ? 'Crear Nuevo Usuario' : 'Editando Usuario: ' . $name }}
                                </h4>
                                <button wire:click="$set('userAction', 'list')" class="btn btn-outline-secondary btn-sm">
                                    <i class="bi bi-arrow-left"></i> Volver a la lista
                                </button>
                            </div>
                            <hr>

                            <form wire:submit.prevent="saveUser">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold">Nombre</label>
                                        <input wire:model="name" type="text" class="form-control" placeholder="Nombre completo">
                                        @error('name') <span class="text-danger small">{{ $message }}</span> @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label fw-bold">Teléfono</label>
                                        <input wire:model="telefono" type="text" class="form-control" placeholder="Teléfono">
                                        @error('telefono') <span class="text-danger small">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="col-md-6">
                                        xd
                                        <input wire:model="email" type="email" class="form-control" placeholder="correo@ejemplo.com">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold">Email</label>
                                        <input wire:model="email" type="email" class="form-control" placeholder="correo@ejemplo.com">
                                        @error('email') <span class="text-danger small">{{ $message }}</span> @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label fw-bold">Departamento</label>
                                        <select wire:model="IdDepto" class="form-select">
                                            <option value="">Seleccione Departamento...</option>
                                            @foreach($deptos as $depto)
                                                <option value="{{ $depto->id }}">{{ $depto->depto }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label fw-bold">Contraseña {{ $userAction == 'edit' ? '(Dejar en blanco para no cambiar)' : '' }}</label>
                                        <input wire:model="password" type="password" class="form-control" placeholder="••••••••">
                                        @error('password') <span class="text-danger small">{{ $message }}</span> @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label fw-bold">Confirmar Contraseña</label>
                                        <input wire:model="passwordConf" type="password" class="form-control" placeholder="••••••••">
                                    </div>

                                    <div class="col-12"><hr class="my-2"></div>

                                    <!-- SECCIÓN DE ROLES ASIGNADOS AL USUARIO -->
                                    <div class="col-md-6">
                                        <h5 class="fw-bold fs-6 text-primary"><i class="bi bi-shield-check"></i> Roles del Usuario</h5>
                                        <div class="p-3 border rounded bg-light" style="max-height: 200px; overflow-y: auto;">
                                            @forelse($roles as $role)
                                                <div class="form-check">
                                                    <input wire:model="userRoles" class="form-check-input" type="checkbox" value="{{ $role->name }}" id="role_{{ $role->id }}">
                                                    <label class="form-check-label" for="role_{{ $role->id }}">
                                                        {{ $role->name }}
                                                    </label>
                                                </div>
                                            @empty
                                                <span class="text-muted small">No hay roles definidos en el sistema.</span>
                                            @endforelse
                                        </div>
                                    </div>

                                    <!-- SECCIÓN DE PERMISOS DIRECTOS AL USUARIO -->
                                    <div class="col-md-6">
                                        <h5 class="fw-bold fs-6 text-secondary"><i class="bi bi-key"></i> Permisos Directos</h5>
                                        <div class="p-3 border rounded bg-light" style="max-height: 200px; overflow-y: auto;">
                                            @forelse($permissions as $permission)
                                                <div class="form-check">
                                                    <input wire:model="userPermissions" class="form-check-input" type="checkbox" value="{{ $permission->name }}" id="perm_{{ $permission->id }}">
                                                    <label class="form-check-label" for="perm_{{ $permission->id }}">
                                                        {{ $permission->name }}
                                                    </label>
                                                </div>
                                            @empty
                                                <span class="text-muted small">No hay permisos definidos en el sistema.</span>
                                            @endforelse
                                        </div>
                                    </div>

                                    <div class="col-12 text-end mt-4">
                                        <button type="button" wire:click="$set('userAction', 'list')" class="btn btn-secondary px-4">Cancelar</button>
                                        <button type="submit" class="btn btn-success px-4">Guardar Cambios</button>
                                    </div>
                                </div>
                            </form>
                        @endif

                    <!-- ================= SECCIÓN 2: ROLES ================= -->
                    @elseif($activeTab == 'roles')
                        <h4 class="fw-bold mb-3"><i class="bi bi-shield-lock me-2"></i> Gestión de Roles (Spatie)</h4>
                        
                        <!-- Crear Rol -->
                        <div class="card bg-light border-0 p-3 mb-4">
                            <label class="form-label fw-bold">Crear Nuevo Rol</label>
                            <div class="input-group">
                                <input wire:model="newRoleName" type="text" class="form-control" placeholder="Nombre del nuevo rol...">
                                <button wire:click="createRole" class="btn btn-primary" type="button"><i class="bi bi-plus-lg"></i> Agregar Rol</button>
                            </div>
                            @error('newRoleName') <span class="text-danger small mt-1">{{ $message }}</span> @enderror
                        </div>

                        <!-- Lista de Roles y sus permisos asociados -->
                        <div class="row g-3">
                            <div class="col-md-5">
                                <h6 class="fw-bold text-muted">Roles Existentes</h6>
                                <div class="list-group">
                                    @foreach($roles as $role)
                                        <div class="list-group-item list-group-item-action d-flex justify-content-between align-items-center {{ $selectedRoleForPermissions == $role->id ? 'active' : '' }}">
                                            <span style="cursor: pointer;" wire:click="selectRoleForPermissions({{ $role->id }})">
                                                {{ $role->name }}
                                            </span>
                                            <div>
                                                <button wire:click="selectRoleForPermissions({{ $role->id }})" class="btn btn-sm {{ $selectedRoleForPermissions == $role->id ? 'btn-light' : 'btn-outline-primary' }}" title="Configurar Permisos">
                                                    <i class="bi bi-key"></i>
                                                </button>
                                                <button wire:click="deleteRole({{ $role->id }})" onclick="confirm('¿Estás seguro de eliminar este rol?') || event.stopImmediatePropagation()" class="btn btn-sm {{ $selectedRoleForPermissions == $role->id ? 'btn-light text-danger' : 'btn-outline-danger' }}" title="Eliminar">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <div class="col-md-7">
                                <h6 class="fw-bold text-muted">Permisos del Rol Seleccionado</h6>
                                @if($selectedRoleForPermissions)
                                    <div class="card border p-3 bg-white">
                                        <p class="small text-muted mb-2">Selecciona los permisos que tendrá este rol:</p>
                                        <div style="max-height: 250px; overflow-y: auto;" class="mb-3">
                                            @foreach($permissions as $perm)
                                                <div class="form-check">
                                                    <input wire:model="rolePermissions" class="form-check-input" type="checkbox" value="{{ $perm->id }}" id="role_perm_{{ $perm->id }}">
                                                    <label class="form-check-label" for="role_perm_{{ $perm->id }}">
                                                        {{ $perm->name }}
                                                    </label>
                                                </div>
                                            @endforeach
                                        </div>
                                        <button wire:click="updateRolePermissions" class="btn btn-success btn-sm w-100">
                                            <i class="bi bi-save"></i> Guardar Permisos del Rol
                                        </button>
                                    </div>
                                @else
                                    <div class="alert alert-secondary text-center py-4">
                                        Selecciona un rol de la lista izquierda para editar sus permisos.
                                    </div>
                                @endif
                            </div>
                        </div>

                    <!-- ================= SECCIÓN 3: PERMISOS ================= -->
                    @elseif($activeTab == 'permissions')
                        <h4 class="fw-bold mb-3"><i class="bi bi-key me-2"></i> Gestión de Permisos (Spatie)</h4>
                        
                        <!-- Crear Permiso -->
                        <div class="card bg-light border-0 p-3 mb-4">
                            <label class="form-label fw-bold">Crear Nuevo Permiso</label>
                            <div class="input-group">
                                <input wire:model="newPermissionName" type="text" class="form-control" placeholder="Ej. crear-productos, editar-usuarios...">
                                <button wire:click="createPermission" class="btn btn-primary" type="button"><i class="bi bi-plus-lg"></i> Agregar Permiso</button>
                            </div>
                            @error('newPermissionName') <span class="text-danger small mt-1">{{ $message }}</span> @enderror
                        </div>

                        <h6 class="fw-bold text-muted mb-2">Permisos Disponibles en el Sistema</h6>
                        <div class="row g-2">
                            @forelse($permissions as $permission)
                                <div class="col-md-4">
                                    <div class="p-2 border rounded bg-white d-flex justify-content-between align-items-center shadow-sm">
                                        <span class="small font-monospace">{{ $permission->name }}</span>
                                        <button wire:click="deletePermission({{ $permission->id }})" onclick="confirm('¿Eliminar este permiso?') || event.stopImmediatePropagation()" class="btn btn-sm text-danger p-0">
                                            <i class="bi bi-x-circle-fill"></i>
                                        </button>
                                    </div>
                                </div>
                            @empty
                                <div class="col-12 text-center text-muted py-3">No hay permisos creados todavía.</div>
                            @endforelse
                        </div>

                    @endif

                </div>
            </div>
        </div>

    </div>
</div>