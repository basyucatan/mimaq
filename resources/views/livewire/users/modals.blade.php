@if ($verModalUser)
    <div class="modal-overlay" x-data="{}" x-init="dragModal($el)">
        <div class="modal-dialog" style="width: 85%;">
            <div class="modal-content">
                <div class="cardPrin">
                    <div class="cardPrin-header d-flex justify-content-between align-items-center">
                        <h5 class="m-0">{{ $selected_id ? 'Editar Usuario' : 'Crear Usuario' }}</h5>
                        <button wire:click="cancel" type="button" class="btn-close" aria-label="Cerrar"></button>
                    </div>
                    <div class="cardPrin-body" style="padding: 0 20px; max-height: 480px; overflow-y: auto;">
                        <form autocomplete="off">
                            <div class="row g-2">
                                <div class="col-md-4">
                                    <label for="name" class="etiBase">Nombre</label>
                                    <input wire:model.live="name" type="text" class="inpBase" id="name">
                                    @error('name') <span class="error text-danger">{{ $message }}</span> @enderror
                                </div>
                                <div class="col-md-4">
                                    <label for="telefono" class="etiBase">Teléfono</label>
                                    <input wire:model.live="telefono" type="text" class="inpBase" id="telefono">
                                    @error('telefono') <span class="error text-danger">{{ $message }}</span> @enderror
                                </div>
                                <div class="col-md-4">
                                    <label for="email" class="etiBase">Email</label>
                                    <input wire:model.live="email" type="text" class="inpBase" id="email" placeholder="ejemplo@gmail.com">
                                    @error('email') <span class="error text-danger">{{ $message }}</span> @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="password" class="etiBase">Password {{ $selected_id ? '(Opcional)' : '' }}</label>
                                    <input wire:model.live="password" type="password" class="inpBase" id="password" autocomplete="new-password">
                                    @error('password') <span class="error text-danger">{{ $message }}</span> @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="passwordConf" class="etiBase">Confirmar Password</label>
                                    <input wire:model.live="passwordConf" type="password" class="inpBase" id="passwordConf" autocomplete="new-password">
                                </div>
                                <div class="col-12 mt-2">
                                    <label class="etiBase font-weight-bold">Roles Asignados</label>
                                    <div class="row g-1 border rounded p-2 bg-light">
                                        @foreach($this->todosRoles as $rol)
                                            <div class="col-md-3">
                                                <div class="form-check">
                                                    <input wire:model="rolesSeleccionados" class="form-check-input" type="checkbox" value="{{ $rol->name }}" id="u_rol_{{ $rol->id }}">
                                                    <label class="form-check-label etiBase" for="u_rol_{{ $rol->id }}">
                                                        {{ $rol->name }}
                                                    </label>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                <div class="col-12 mt-2">
                                    <label class="etiBase font-weight-bold">Permisos Directos Específicos</label>
                                    <div class="row g-1 border rounded p-2 bg-light">
                                        @foreach($this->todosPermisos as $permiso)
                                            <div class="col-md-3">
                                                <div class="form-check">
                                                    <input wire:model="permisosSeleccionados" class="form-check-input" type="checkbox" value="{{ $permiso->name }}" id="u_perm_{{ $permiso->id }}">
                                                    <label class="form-check-label etiBase" for="u_perm_{{ $permiso->id }}">
                                                        {{ $permiso->name }}
                                                    </label>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="cardPrin-footer mt-3 d-flex justify-content-end gap-2">
                        <a wire:click.prevent="cancel()" class="bot botNegro">Cerrar</a>
                        <a wire:click.prevent="save()" class="bot botVerde">Guardar</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif

@if ($verModalRol && $this->puedeGestionarEstructura)
    <div class="modal-overlay" x-data="{}" x-init="dragModal($el)">
        <div class="modal-dialog" style="width: 70%;">
            <div class="modal-content">
                <div class="cardPrin">
                    <div class="cardPrin-header d-flex justify-content-between align-items-center">
                        <h5 class="m-0">{{ $selected_id ? 'Editar Rol' : 'Crear Rol' }}</h5>
                        <button wire:click="cancel" type="button" class="btn-close" aria-label="Cerrar"></button>
                    </div>
                    <div class="cardPrin-body" style="padding: 0 20px; max-height: 400px; overflow-y: auto;">
                        <form autocomplete="off">
                            <div class="row g-2">
                                <div class="col-md-8">
                                    <label for="role_name" class="etiBase">Nombre del Rol</label>
                                    <input wire:model.live="name" type="text" class="inpBase" id="role_name">
                                    @error('name') <span class="error text-danger">{{ $message }}</span> @enderror
                                </div>
                                <div class="col-md-4">
                                    <label for="role_nivel" class="etiBase">Nivel Jerárquico</label>
                                    <input wire:model.live="nivel" type="number" class="inpBase" id="role_nivel">
                                    @error('nivel') <span class="error text-danger">{{ $message }}</span> @enderror
                                </div>
                                <div class="col-12 mt-2">
                                    <label class="etiBase">Permisos del Rol</label>
                                    <div class="row g-1">
                                        @foreach($this->todosPermisos as $permiso)
                                            <div class="col-md-4">
                                                <div class="form-check">
                                                    <input wire:model="permisosSeleccionados" class="form-check-input" type="checkbox" value="{{ $permiso->name }}" id="perm_{{ $permiso->id }}">
                                                    <label class="form-check-label etiBase" for="perm_{{ $permiso->id }}">
                                                        {{ $permiso->name }}
                                                    </label>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="cardPrin-footer mt-3 d-flex justify-content-end gap-2">
                        <a wire:click.prevent="cancel()" class="bot botNegro">Cerrar</a>
                        <a wire:click.prevent="save()" class="bot botVerde">Guardar</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif

@if ($verModalPermiso && $this->puedeGestionarEstructura)
    <div class="modal-overlay" x-data="{}" x-init="dragModal($el)">
        <div class="modal-dialog" style="width: 50%;">
            <div class="modal-content">
                <div class="cardPrin">
                    <div class="cardPrin-header d-flex justify-content-between align-items-center">
                        <h5 class="m-0">{{ $selected_id ? 'Editar Permiso' : 'Crear Permiso' }}</h5>
                        <button wire:click="cancel" type="button" class="btn-close" aria-label="Cerrar"></button>
                    </div>
                    <div class="cardPrin-body" style="padding: 0 20px; max-height: 400px; overflow-y: auto;">
                        <form autocomplete="off">
                            <div class="row g-1">
                                <div class="col-12">
                                    <label for="perm_name" class="etiBase">Nombre del Permiso</label>
                                    <input wire:model.live="name" type="text" class="inpBase" id="perm_name">
                                    @error('name') <span class="error text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="cardPrin-footer mt-3 d-flex justify-content-end gap-2">
                        <a wire:click.prevent="cancel()" class="bot botNegro">Cerrar</a>
                        <a wire:click.prevent="save()" class="bot botVerde">Guardar</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif