<li class="mb-0" wire:key="nodo-{{ $tipo }}-{{ $nodo->id }}">
    <div class="d-flex align-items-center py-1 border-bottom border-light px-2" 
        style="cursor:pointer; user-select:none;" 
        onmouseover="this.style.backgroundColor='#fbff00'" 
        onmouseout="this.style.backgroundColor='transparent'" 
        onclick="manejadorClick(event, '{{ $tipo }}', {{ $nodo->id }})">
        
        <div style="width: 20px;" class="text-center me-1 flex-shrink-0">
            @if ($hijos && count($hijos) > 0)
                <span>{{ $expanded ? '🔽' : '▶️' }}</span>
            @else
                {{-- Icono según tipo --}}
                <span>{{ $tipo == 'Folio' ? '💎' : ($tipo == 'Lote' ? '🏷️' : '📦') }}</span>
            @endif
        </div>

        <div class="flex-grow-1 d-flex align-items-center justify-content-between overflow-hidden">
            @php
                $estiloTexto = 'text-dark small';
                if($tipo == 'Orden') {
                    $estiloTexto = 'fw-bold text-uppercase';
                } elseif($tipo == 'Folio') {
                    if(($nodo->estatus ?? '') !== 'abierto') $estiloTexto = 'small text-danger fw-bold';
                    elseif($selected_id == $nodo->id) $estiloTexto = 'small text-success fw-bold';
                }
            @endphp
            <span class="{{ $estiloTexto }} text-truncate">
                {{ $tipo == 'Folio' ? '' : $tipo }} {{ $texto }} 
            </span>

            <div class="d-flex gap-2 align-items-center ms-2">
                @if($tipo == 'Orden')
                    <button wire:click.stop="nuevoLote({{ $nodo->id }})" class="bot botBlanco botChico">✚</button>
                @elseif($tipo == 'Lote')
                    <button wire:click.stop="nuevoFolio({{ $nodo->id }})" class="bot botBlanco botChico">✚</button>
                @endif
                <button onclick="confirm('¿Eliminar?') || event.stopImmediatePropagation()" 
                    wire:click.stop="destroy('{{ $tipo }}', {{ $nodo->id }})" 
                    class="bot botBlanco botChico">⛔</button>
            </div>
        </div>
    </div>

    @if ($expanded && $hijos && count($hijos) > 0)
        <ul class="list-unstyled ps-2 border-start ms-3">
            @foreach ($hijos as $hijo)
                @include('livewire.arbolfolios.nodo', [
                    'tipo' => ($tipo == 'Orden' ? 'Lote' : 'Folio'),
                    'nodo' => $hijo,
                    'texto' => ($tipo == 'Orden' ? $hijo->lote : ($hijo->Estilo ? $hijo->Estilo->estilo.' ('.$hijo->cantidad.' pz)': '')),
                    'expanded' => ($tipo == 'Orden' ? ($expandir['Lote'][$hijo->id] ?? false) : false),
                    'hijos' => ($tipo == 'Orden' ? $hijo->folios : []),
                    'selected_id' => $selected_id
                ])
            @endforeach
        </ul>
    @endif
</li>

@once
<script>
    if (typeof timerClick === 'undefined') {
        var timerClick = null;
    }
    function manejadorClick(evento, tipo, id) {
        if (evento.target.closest('button')) return;
        evento.stopPropagation();
        
        if (timerClick == null) {
            timerClick = setTimeout(() => {
                @this.elegir(tipo, id);
                timerClick = null;
            }, 250);
        } else {
            clearTimeout(timerClick);
            timerClick = null;
            @this.agregar(tipo, id);
        }
    }
</script>
@endonce