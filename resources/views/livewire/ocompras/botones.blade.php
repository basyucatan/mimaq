<div class="d-flex gap-2">
    <button wire:click="imprimir({{ $row->id }})" class="bot botNegro" 
        wire:loading.attr="disabled" wire:target="imprimir({{ $row->id }})">
        <span wire:loading.remove wire:target="imprimir({{ $row->id }})">🖨️</span>
        <span wire:loading wire:target="imprimir({{ $row->id }})">⏳</span>
    </button>    
    @can('validarAprobacion', $row)
        <button wire:click="toggleAprobar({{ $row->id }})" 
                class="bot {{ $row->estatus == 'aprobado' ? 'botNaranja' : 'botVerde' }}">✅
        </button>
    @endcan
    @can('gestionar', $row)
        <button wire:click="edit({{ $row->id }})" 
                class="bot {{ $row->estatus == 'aprobado' ? 'botGris' : 'botNaranja' }}" 
                {{ $row->estatus == 'aprobado' ? 'disabled' : '' }}>
            <i class="bi bi-pencil"></i>
        </button>
        <button wire:click="destroy({{ $row->id }})" 
                onclick="confirm('¿Estás seguro de eliminar este registro?') || event.stopImmediatePropagation()" 
                class="bot {{ $row->estatus == 'aprobado' ? 'botGris' : 'botRojo' }}" 
                {{ $row->estatus == 'aprobado' ? 'disabled' : '' }}>
            <i class="bi bi-trash"></i>
        </button>
    @endcan
</div>