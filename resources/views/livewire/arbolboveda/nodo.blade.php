<li class="mb-0" wire:key="nodo-boveda-{{ $tipo }}-{{ $nodo->id }}">
    <div class="d-flex align-items-center py-1 border-bottom border-light px-2" 
        style="cursor:pointer; user-select:none;" 
        onmouseover="this.style.backgroundColor='#e9ecef'" 
        onmouseout="this.style.backgroundColor='transparent'" 
        wire:click="elegir('{{ $tipo }}', {{ $nodo->id }})">
        
        <div style="width: 20px;" class="text-center me-1 flex-shrink-0">
            @if ($tipo == 'Material')
                <span>{{ $expanded ? '🔽' : '▶️' }}</span>
            @else
                <span style="font-size: 0.8rem;">📑</span>
            @endif
        </div>

        <div class="flex-grow-1 d-flex align-items-center justify-content-between overflow-hidden">
            @php
                $estiloTexto = $tipo == 'Material' ? 'fw-bold text-dark small text-uppercase' : 'small text-muted';
            @endphp
            
            <span class="{{ $estiloTexto }} text-truncate">
                @if($tipo == 'Material')
                    {{ $texto }}
                @else
                    {{ $nodo->IdEntradaMex }} <small class="text-xs">({{ $nodo->propsTot }})</small>
                @endif
            </span>

            @if($tipo == 'Partida')
                <span class="badge bg-success-subtle text-success border border-success-subtle ms-2" style="font-size: 0.7rem;">
                    {{ number_format($nodo->stock, 2) }}
                </span>
            @endif
        </div>
    </div>

    @if ($expanded && $tipo == 'Material' && count($hijos) > 0)
        <ul class="list-unstyled ps-2 border-start ms-3 bg-white">
            @foreach ($hijos as $hijo)
                @include('livewire.arbolboveda.nodo', [
                    'tipo' => 'Partida',
                    'nodo' => $hijo,
                    'texto' => $hijo->IdEntradaMex,
                    'expanded' => false,
                    'hijos' => [],
                    'icono' => '📑'
                ])
            @endforeach
        </ul>
    @endif
</li>