<div class="container-fluid">
    <div class="cardSec">
        <div class="cardSec-header">
            <input wire:model.live="keyWord" class="inpSolo" onfocus="this.select()" placeholder="Buscar...">
            <a class="bot botNegro" wire:click="$toggle('mostrarRaiz')">
                {{ $mostrarRaiz ? '🔼' : '🔽' }}
            </a>        
        </div>
        <div class="cardSec-body" style="max-height: 70vh; overflow-y: auto;">
            @if($mostrarRaiz)
                <ul class="list-unstyled">
                    @foreach($arbol as $clase)
                        @php
                            $expanded = $expand['Clase'][$clase['id']] ?? false;
                            $hasChildren = !empty($clase['materials']);
                        @endphp

                        @include('livewire.arbolclasesmats.nodo', [
                            'tipo'     => 'Clase',
                            'nodo'     => $clase,
                            'texto'    => $clase['clase'],
                            'expanded' => $expanded,
                            'hijos'    => collect($clase['materials'] ?? [])
                        ])
                    @endforeach
                </ul>
            @endif
        </div>
    </div>
</div>
