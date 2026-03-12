@if($verModal)
    <div class="modal-overlay"> 
        <div x-data="{}" x-init="dragModal($el)" class="modal-dialog" style="width: 80%;">
            <div class="modal-content">
                <div class="cardPrin" style="overflow-y: auto; height: 60vh; min-height: 200px;">
                    <div class="cardPrin-header">{{ $selected_id ? 'Editar' : 'Crear' }}</div>
                    <div class="cardPrin-body">
                        @foreach($campos as $key => $valor)
                            <div style="margin-bottom: 15px;">
                                <label class="etiBase"> 
                                    {{ ucwords(preg_replace('/(?<!^)[A-Z]/', ' $0', $key)) }}
                                </label>
                                <input wire:model="campos.{{ $key }}" class="inpBase">
                                @error("campos.$key") <span class="text-danger" style="font-size: 12px;">{{ $message }}</span> @enderror
                            </div>
                        @endforeach
                    </div>
                    <div class="cardPrin-footer">
                        <button wire:click="cancel" class="bot botNegro">Cancelar</button>
                        <button wire:click="save" class="bot botVerde">Guardar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif