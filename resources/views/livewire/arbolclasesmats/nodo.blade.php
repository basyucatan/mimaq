<li class="mb-0">
    <div class="d-flex align-items-center py-1 border-bottom border-light"
        style="cursor:pointer; user-select:none;"
        onmouseover="this.style.backgroundColor='#fbff00'"
        onmouseout="this.style.backgroundColor='transparent'"
        onclick="manejadorClick(event, () => @this.toggle('{{ $tipo }}', {{ $nodo['id'] }}), () => @this.agregar('{{ $tipo }}', {{ $nodo['id'] }}))">
        
        <div style="width: 20px;" class="text-center me-1">
            @if($hijos->count() > 0)
                <span>{{ $expanded ? '🔽' : '▶️' }}</span>
            @else
                <span>{{ $tipo == 'Materialscosto' ? '🔹' : '🔴' }}</span>
            @endif
        </div>

        <div class="flex-grow-1 text-truncate">
            @php
                $estiloTexto = ($tipo == 'Clase') ? 'fw-bold' 
                    : (($tipo == 'Material') ? 'text-dark' 
                    : 'text-muted small');
            @endphp
            <span class="{{ $estiloTexto }}">{{ $texto }}</span>
        </div>
    </div>

    @if($expanded && $hijos->count() > 0)
        <ul class="list-unstyled ps-2 border-start ms-2" style="border-color: #eee !important;">
            @foreach($hijos as $hijo)
                @include('livewire.arbolclasesmats.nodo', [
                    'tipo' => ($tipo == 'Clase' ? 'Material' : 'Materialscosto'),
                    'nodo' => $hijo,
                    'texto' => ($tipo == 'Clase' ? $hijo['material'].' '.$hijo['referencia'] : $hijo['referencia'].($hijo['color']['color'] ?? '')),
                    'expanded' => $expand[$tipo == 'Clase' ? 'Material' : 'Materialscosto'][$hijo['id']] ?? false,
                    'hijos' => collect($hijo[$tipo == 'Clase' ? 'materialscostos' : ''] ?? [])
                ])
            @endforeach
        </ul>
    @endif
</li>

<script>
    let timerClick = null;
    function manejadorClick(evento, accionSimple, accionDoble) {
        if (timerClick == null) {
            timerClick = setTimeout(() => {
                accionSimple();
                timerClick = null;
            }, 200); // Tiempo de espera para detectar doble click
        } else {
            clearTimeout(timerClick);
            timerClick = null;
            accionDoble();
        }
    }
</script>