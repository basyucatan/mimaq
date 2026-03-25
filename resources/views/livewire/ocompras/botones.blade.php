<div class="d-flex gap-2">
    <button wire:click="imprimir({{ $row->id }})" class="bot botNegro" wire:loading.attr="disabled" wire:target="imprimir({{ $row->id }})">
        <span wire:loading.remove wire:target="imprimir({{ $row->id }})">🖨️</span>
        <span wire:loading wire:target="imprimir({{ $row->id }})">⏳</span>
    </button>
    @can('validarAprobacion', $row)
        @if($row->estatus === 'edicion')
            <button wire:click="cambiarEstatus({{ $row->id }}, 'aprobado')" class="bot botVerde">Aprobar</button>
        @endif
    @endcan
    @can('gestionar', $row)
        @if($row->estatus === 'aprobado')
            <button wire:click="cambiarEstatus({{ $row->id }}, 'ordenado')" class="bot botAzul">Ordenar</button>
        @endif
        @if($row->estatus === 'ordenado')
            <button wire:click="cerrarRecepcion({{ $row->id }})" onclick="confirm('¿Afectar inventario y cerrar orden?') || event.stopImmediatePropagation()" class="bot botVerde">Recibir</button>
        @endif
        @can('eliminar', $row)
            @if(!in_array($row->estatus, ['recibido', 'cancelado']))
                <button wire:click="cambiarEstatus({{ $row->id }}, 'cancelado')" class="bot botRojo" onclick="confirm('¿Cancelar esta orden?') || event.stopImmediatePropagation()">✖</button>
            @endif
        @endcan
        @if($row->estatus !== 'recibido')
            <button wire:click="edit({{ $row->id }})" class="bot {{ $row->estatus !== 'edicion' ? 'botGris' : 'botNaranja' }}">
                <i class="bi bi-pencil"></i>
            </button>
        @endif
        @can('eliminar', $row)
            @if($row->estatus === 'edicion')
                <button wire:click="destroy({{ $row->id }})" onclick="confirm('¿Eliminar registro?') || event.stopImmediatePropagation()" class="bot botRojo">
                    <i class="bi bi-trash"></i>
                </button>
            @endif
        @endcan
        @if($row->estatus === 'recibido')
            <span class="badge bg-success d-flex align-items-center px-2">
                <i class="bi bi-check-circle-fill me-1"></i> RECIBIDO
            </span>
        @endif
    @endcan
</div>