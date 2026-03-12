@if ($verModalEmpresa)
    <div class="modal-overlay">
        <div x-data="{}" x-init="dragModal($el)" class="modal-dialog" style="width: 80%;" wire:ignore.self>
            <div class="modal-content">
                <div class="cardPrin" style="cursor: move;">
                    <div class="cardPrin-header d-flex justify-content-between align-items-center">
                        <h5 class="m-0">
                            {{ $selected_id ? 'Editar Empresa' : 'Crear Empresa' }}
                        </h5>
                        <button wire:click="cancel" type="button" class="btn-close" aria-label="Cerrar"></button>
                    </div>
                    <div class="cardPrin-body" style="padding: 0 20px; max-height: 400px; overflow-y: auto;">
                        <form>
                            <div class="row">
                                @if ($selected_id)
                                    <input type="hidden" wire:model="selected_id">
                                @endif
                                <div class="col-md-6">
                                    <label for="empresa" class="etiBase">Empresa</label>
                                    <input wire:model="empresa" type="text" class="inpBase" id="empresa">
                                    @error('empresa')
                                        <span class="error text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="razonSocial" class="etiBase">Razón Social</label>
                                    <input wire:model="razonSocial" type="text" class="inpBase" id="razonSocial">
                                    @error('razonSocial')
                                        <span class="error text-danger">{{ $message }}</span>
                                    @enderror
                                </div>          
                                <div class="col-md-6">
                                    <label for="rfc" class="etiBase">RFC</label>
                                    <input wire:model="rfc" type="text" class="inpBase" id="rfc">
                                    @error('rfc')
                                        <span class="error text-danger">{{ $message }}</span>
                                    @enderror
                                </div>                                                        
                                <div class="col-md-6">
                                    <label for="direccion" class="etiBase">Direccion</label>
                                    <input wire:model="direccion" type="text" class="inpBase" id="direccion">
                                    @error('direccion')
                                        <span class="error text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="gmaps" class="etiBase">Gmaps</label>
                                    <input wire:model="gmaps" type="text" class="inpBase" id="gmaps">
                                    @error('gmaps')
                                        <span class="error text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="telefono" class="etiBase">Telefono</label>
                                    <input wire:model="telefono" type="text" class="inpBase" id="telefono">
                                    @error('telefono')
                                        <span class="error text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="email" class="etiBase">Email</label>
                                    <input wire:model="email" type="text" class="inpBase" id="email">
                                    @error('email')
                                        <span class="error text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </form>
                        @if($tipo=='cliente' || $tipo=='ambos')
                            @livewire('obras', ['IdEmpresa' => $selected_id], key('obras-' . $selected_id))  
                        @endif
                        @if($tipo=='proveedor' || $tipo=='ambos')
                        @livewire('empresascuentas', ['IdEmpresa' => $selected_id], key('cuentas-' . $selected_id))  
                        @endif
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
