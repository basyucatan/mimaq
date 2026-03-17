<div class="d-flex gap-2">
    <button wire:click="imprimir({{ $row->id }})" class="bot botNegro"
        wire:loading.attr="disabled" wire:target="imprimir({{ $row->id }})">
        <span wire:loading.remove wire:target="imprimir({{ $row->id }})">🖨️</span>
        <span wire:loading wire:target="imprimir({{ $row->id }})">⏳</span>
    </button>
    @can('validarAprobacion', $row)
        @if($row->estatus === 'edicion')
            <button wire:click="cambiarEstatus({{ $row->id }}, 'aprobado')" 
                class="bot botVerde">
                Aprobar
            </button>
        @endif
    @endcan
    @can('gestionar', $row)
        @if($row->estatus === 'aprobado')
            <button wire:click="cambiarEstatus({{ $row->id }}, 'ordenado')" 
                class="bot botAzul">
                Ordenar
            </button>
        @endif
        @if($row->estatus === 'ordenado')
            <button wire:click="cambiarEstatus({{ $row->id }}, 'recibido')" 
                class="bot botNaranja">
                Recibir
            </button>
        @endif
        @if(!in_array($row->estatus, ['recibido','cancelado']))
            <button wire:click="cambiarEstatus({{ $row->id }}, 'cancelado')" 
                class="bot botRojo"
                onclick="confirm('¿Cancelar esta orden?') || event.stopImmediatePropagation()">
                ✖
            </button>
        @endif
        <button wire:click="edit({{ $row->id }})"
            class="bot {{ $row->estatus !== 'edicion' ? 'botGris' : 'botNaranja' }}"
            {{ in_array($row->estatus, ['cancelado']) ? 'disabled' : '' }}>
            <i class="bi bi-pencil"></i>
        </button>
        <button wire:click="destroy({{ $row->id }})"
            onclick="confirm('¿Estás seguro de eliminar este registro?') || event.stopImmediatePropagation()"
            class="bot {{ $row->estatus !== 'edicion' ? 'botGris' : 'botRojo' }}"
            {{ $row->estatus !== 'edicion' ? 'disabled' : '' }}>
            <i class="bi bi-trash"></i>
        </button>
        @if($row->estatus === 'recibido')
            <button wire:click="cerrarRecepcion({{ $row->id }})"
                class="bot botVerde">
                Guardar
            </button>
        @endif
    @endcan
</div>