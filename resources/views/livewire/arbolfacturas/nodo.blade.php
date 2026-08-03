<li class="mb-0">
    <div class="d-flex align-items-center py-2 border-bottom border-light px-2" 
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
        <div style="width: 24px;" class="text-center me-1 flex-shrink-0">
            @if ($hijos && count($hijos) > 0)
                <span class="fs-6">{{ $expanded ? '🔽' : '▶️' }}</span>
            @else
                <span class="fs-6">{{ $tipo == 'Factura' ? '' : '📁' }}</span>
            @endif
        </div>
        
        <div class="flex-grow-1 d-flex align-items-center justify-content-between overflow-hidden">
            @php
                if($tipo == 'Pedimento'){
                    $estiloTexto = 'fw-bold text-uppercase fs-5';
                    $textoMostrar = (!empty($nodo->pedimento) && trim($nodo->pedimento) !== '') ? $nodo->pedimento : $nodo->id;
                }else{
                    if($selected_id == $nodo->id){
                        $estiloTexto = 'fs-6 text-success fw-bold';
                    }else{
                        $estiloTexto = 'text-dark fs-6 fw-bold';
                    }
                    $textoMostrar = $texto;
                }
                $fechaFormateada = '';
            @endphp
            <span class="{{ $estiloTexto }} text-truncate">
                @if($tipo == 'Pedimento')
                    {{ $textoMostrar }}
                @else
                    <span class="badge {{ $nodo->estatus == 'abierto' ? 'bg-success' : 'bg-danger' }} ms-1 fs-6">
                        {{ $nodo->estatus == 'abierto' ? '🔓' : '🔒' }} {{ $textoMostrar }}
                    </span>
                @endif
                <small class="text-muted fw-normal ms-1 fs-6">{{ $fechaFormateada }}</small>
            </span>
            <div class="d-flex gap-2 align-items-center ms-2">
                @if($tipo == 'Pedimento')
                    <button wire:click.stop="asignarSecuencias({{ $nodo->id }})" class="bot botAzul botChico" title="Asignar Secuencias">
                        <i class="bi bi-list-ol"></i>
                    </button>
                    <button wire:click.stop="nuevaFactura({{ $nodo->id }})" class="bot botBlanco botChico" title="New Invoice">
                        <span style="color: #198754; font-weight: bold;">✚</span>
                    </button>
                @endif
                @if($tipo == 'Pedimento' || ($tipo == 'Factura' && $nodo->estatus == 'abierto'))
                    <button onclick="confirm('¿Desea eliminar este registro?') || event.stopImmediatePropagation()" 
                        wire:click.stop="destroy('{{ $tipo }}', {{ $nodo->id }})" 
                        class="bot botBlanco botChico" title="Delete">
                        <span>⛔</span>
                    </button>
                @endif
            </div>
        </div>
    </div>
    @if ($expanded && $hijos && count($hijos) > 0)
        <ul class="list-unstyled border-start ps-3" style="border-color: #dee2e6 !important;">
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