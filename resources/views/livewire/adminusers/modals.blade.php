@if($verModalUser)
    <div class="modal-overlay" style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1050; display: flex; align-items: center; justify-content: center;">
        <div x-data="{}" x-init="if(typeof dragModal === 'function') dragModal($el)" class="modal-dialog" style="width: 100%; max-width: 650px;" wire:ignore.self>            
            <div class="modal-content">
                <div class="cardPrin">
                    <div class="cardPrin-header" style="cursor: move; padding: 12px 15px; font-weight: bold;">
                        <span>{{ $selectedUserId ? 'Editando Usuario: ' . $name : 'Crear Nuevo Usuario' }}</span>
                    </div>
                    
                    <div class="cardPrin-body" style="padding: 15px; max-height: 450px; overflow-y: auto;">
                        <form>
                            @if ($selectedUserId)
                                <input type="hidden" wire:model="selectedUserId">
                            @endif

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="etiBase">Nombre</label>
                                    <input wire:model="name" type="text" class="inpBase form-control" placeholder="Nombre completo" onfocus="this.select()">
                                    @error('name') <span class="text-danger small">{{ $message }}</span> @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="etiBase">Teléfono</label>
                                    <input wire:model="telefono" type="text" class="inpBase form-control" placeholder="Teléfono" onfocus="this.select()">
                                    @error('telefono') <span class="text-danger small">{{ $message }}</span> @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="etiBase">Correo Electrónico (Email)</label>
                                    <input wire:model="email" type="email" class="inpBase form-control" placeholder="correo@ejemplo.com" onfocus="this.select()">
                                    @error('email') <span class="text-danger small">{{ $message }}</span> @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="etiBase">Departamento</label>
                                    <select wire:model="IdDepto" class="inpBase form-control">
                                        <option value="">Seleccione Departamento</option>
                                        @foreach($deptos as $depto)
                                            <option value="{{ $depto->id }}">{{ $depto->nombre ?? $depto->name ?? $depto->id }}</option>
                                        @endforeach
                                    </select>
                                    @error('IdDepto') <span class="text-danger small">{{ $message }}</span> @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="etiBase">Contraseña {{ $selectedUserId ? '(Dejar en blanco para no cambiar)' : '' }}</label>
                                    <input wire:model="password" type="password" class="inpBase form-control" placeholder="******">
                                    @error('password') <span class="text-danger small">{{ $message }}</span> @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="etiBase">Confirmar Contraseña</label>
                                    <input wire:model="passwordConf" type="password" class="inpBase form-control" placeholder="******">
                                </div>

                                <!-- Roles y Permisos dentro del Modal -->
                                <div class="col-md-6 mt-3">
                                    <label class="etiBase fw-bold">Roles del Usuario</label>
                                    <div class="border p-2 rounded" style="max-height: 150px; overflow-y: auto;">
                                        @foreach($roles as $role)
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" wire:model="userRoles" value="{{ $role->name }}" id="role_{{ $role->id }}">
                                                <label class="form-check-label" for="role_{{ $role->id }}">
                                                    {{ $role->name }}
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                                <div class="col-md-6 mt-3">
                                    <label class="etiBase fw-bold">Permisos Directos</label>
                                    <div class="border p-2 rounded" style="max-height: 150px; overflow-y: auto;">
                                        @foreach($permissions as $permission)
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" wire:model="userPermissions" value="{{ $permission->name }}" id="perm_{{ $permission->id }}">
                                                <label class="form-check-label" for="perm_{{ $permission->id }}">
                                                    {{ $permission->name }}
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>

                    <div class="cardPrin-footer mt-3 d-flex justify-content-end gap-2 p-2">
                        <button wire:click.prevent="cancel()" class="bot botNegro btn btn-secondary btn-sm">Cerrar</button>
                        <button wire:click.prevent="save()" class="bot botVerde btn btn-success btn-sm">Guardar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif