<li class="mb-0">
    <div class="d-flex align-items-center py-1 border-bottom border-light px-2" 
        style="cursor:pointer; user-select:none;" 
        onmouseover="this.style.backgroundColor='#fbff00'" 
        onmouseout="this.style.backgroundColor='transparent'" 
        onclick="manejadorClick(event, 
            () => {
                @if($tipo == 'Factura') @this.elegir('Factura', {{ $nodo->id }}) 
                @else @this.alternarNodo('{{ $tipo }}', {{ $nodo->id }}) @endif
            }, 
            () => @this.agregar('{{ $tipo }}', {{ $nodo->id }})
        )">
        <div style="width: 20px;" class="text-center me-1 flex-shrink-0">
            @if ($hijos && count($hijos) > 0)
                <span>{{ $expanded ? '🔽' : '▶️' }}</span>
            @else
                <span>{{ $tipo == 'Factura' ? '📄' : '📁' }}</span>
            @endif
        </div>
        <div class="flex-grow-1 d-flex align-items-center justify-content-between overflow-hidden">
            @php
                if($tipo == 'Pedimento'){
                    $estiloTexto = 'fw-bold text-uppercase';
                }else{
                    if($selected_id == $nodo->id){
                        $estiloTexto = 'small text-success fw-bold';
                    }else{
                        $estiloTexto = 'text-dark small';
                    }
                }
                $fechaFormateada = $nodo->fecha ? $nodo->fecha : '';
            @endphp
            <span class="{{ $estiloTexto }} text-truncate">
                {{ $texto }} 
                <small class="text-muted fw-normal ms-1" style="font-size: 0.7rem;">({{ $fechaFormateada }})</small>
            </span>
            <div class="d-flex gap-2 align-items-center ms-2">
                @if($tipo == 'Pedimento')
                    <button wire:click.stop="nuevaFactura({{ $nodo->id }})" class="bot botBlanco botChico" title="Agregar Factura">
                        <span style="color: #198754; font-weight: bold;">✚</span>
                    </button>
                @endif
                <button onclick="confirm('¿Desea eliminar este registro?') || event.stopImmediatePropagation()" 
                    wire:click.stop="destroy('{{ $tipo }}', {{ $nodo->id }})" 
                    class="bot botBlanco botChico" title="Eliminar">
                    <span>⛔</span>
                </button>
            </div>
        </div>
    </div>
    @if ($expanded && $hijos && count($hijos) > 0)
        <ul class="list-unstyled ps-2 border-start ms-3" style="border-color: #dee2e6 !important;">
            @foreach ($hijos as $hijo)
                @include('livewire.arbolfacturas.nodo', [
                    'tipo' => 'Factura',
                    'nodo' => $hijo,
                    'texto' => $hijo->factura,
                    'expanded' => false,
                    'hijos' => []
                ])
            @endforeach
        </ul>
    @endif
</li>
@once
    <script>
        let timerClick = null;
        function manejadorClick(evento, accionSimple, accionDoble) {
            if (timerClick == null) {
                timerClick = setTimeout(() => {
                    accionSimple();
                    timerClick = null;
                }, 250);
            } else {
                clearTimeout(timerClick);
                timerClick = null;
                accionDoble();
            }
        }
    </script>
@endonce