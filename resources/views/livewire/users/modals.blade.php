@if ($verModalUser)
    <div class="modal-overlay">
        <div class="modal-dialog" style="width: 80%;">
            <div class="modal-content">
                <div class="cardPrin">
                    <div class="cardPrin-header d-flex justify-content-between align-items-center">
                        <h5 class="m-0">
                            {{ $selected_id ? 'Editar User' : 'Crear User' }}
                        </h5>
                        <button wire:click="cancel" type="button" class="btn-close" aria-label="Cerrar"></button>
                    </div>
                    <div class="cardPrin-body" style="padding: 0 20px; max-height: 400px; overflow-y: auto;">
                        <form>
                            <div class="row g-1">
                                @if ($selected_id)
                                    <input type="hidden" wire:model="selected_id">
                                @endif
                                <div class="col-md-6">
                                    <label for="name" class="etiBase">Name</label>
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
