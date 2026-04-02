@if ($verModalUser)
    <div class="modal-overlay">
        <div x-data="{}" x-init="dragModal($el)" class="modal-dialog" wire:ignore.self>            
            <div class="modal-content">
                <div class="cardPrin" style="cursor: move;">
                    <div class="cardPrin-header">
                        <span>{{ $selected_id ? 'Editar Usuario' : 'Crear Usuario' }}</span>
                    </div>
                    <div class="cardPrin-body" style="padding: 10px; max-height: 400px; overflow-y: auto;">
                        <form>
                            <div class="row g-1">
                                @if ($selected_id)<input type="hidden" wire:model="selected_id">@endif
                                <div class="col-md-6">
                                    <label for="name" class="etiBase">Nombre</label>
                                    <input wire:model.live="name" type="text" class="inpBase" id="name">
                                    @error('name')
                                        <span class="error text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="telefono" class="etiBase">Telefono</label>
                                    <input wire:model.live="telefono" type="text" class="inpBase" id="telefono">
                                    @error('telefono')
                                        <span class="error text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="email" class="etiBase">Email</label>
                                    <input wire:model.live="email" type="text" class="inpBase" id="email">
                                    @error('email')
                                        <span class="error text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="etiBase">Rol</label>
                                    <select wire:model="IdRol" class="inpBase">
                                        <option value=""></option>
                                        @foreach ($roles as $key => $value)
                                            <option value="{{ $key }}">{{ $value }}</option>
                                        @endforeach
                                    </select>
                                    @error('IdRol') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>                                 
                                <div class="col-md-6">
                                    <label for="password" class="etiBase">Password</label>
                                    <input wire:model.live="password" type="password" class="inpBase" id="password">
                                    @error('password')
                                        <span class="error text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="passwordConf" class="etiBase">Confirmar Password</label>
                                    <input wire:model.live="passwordConf" type="password" class="inpBase" id="passwordConf">
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
