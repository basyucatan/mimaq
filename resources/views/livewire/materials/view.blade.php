@section('title', __('Materials'))
<div class="container-fluid h-100 p-0">
        @include('livewire.materials.modals')
        <div class="row g-2">
            @forelse($materials as $row)
            <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                <div class="cardSec shadow-sm h-100">
                    <div class="cardSec-header bg-light small fw-bold text-truncate">
                        <div>
                            {{ $row->material }}
                        </div>
                        <div class="bot botVerde" wire:click="create" title="Nuevo Mensaje">
                            <i class="bi bi-file-earmark-plus"></i>
                        </div>                        
                    </div>
                    <div class="cardSec-body p-2">
                        <div class="row g-0">
                            <div class="col-4 pe-2">
                                @if ($row->fotoUrl)
                                    <img src="{{ $row->fotoUrl }}?v={{ now()->timestamp }}" 
                                            class="ImgExpandible img-fluid border rounded shadow-sm">
                                @else
                                    <div class="bg-light border rounded d-flex align-items-center justify-content-center" style="height: 50px;">
                                        <span class="text-muted small">N/A</span>
                                    </div>
                                @endif
                            </div>
                            <div class="col-8 small text-muted" style="line-height: 1.2;">
                                <strong>Cod:</strong> {{ $row->referencia }} id {{ $selected_id }}<br>
                                <strong>Clase:</strong> {{ $row->Clase->clase }}<br>
                                <strong>Marca:</strong> {{ $row->Linea?->Marca->marca }}<br>
                                <strong>Línea:</strong> {{ $row->Linea?->linea }}
                            </div>
                        </div>
                    </div>
                    <div class="cardSec-footer d-flex justify-content-end gap-1 p-1">
                        <button wire:click="edit({{ $selected_id }})" class="bot botNaranja botSm" title="Editar">
                            <i class="bi-pencil-square"></i>
                        </button>
                        <button wire:click="destroy({{ $selected_id }})" class="bot botRojo botSm" 
                                onclick="confirm('¿Eliminar?') || event.stopImmediatePropagation()">
                            <i class="bi-trash3-fill"></i>
                        </button>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12 text-center text-muted p-4">No se encontraron materiales.</div>
            @endforelse
        </div>
        <div class="d-flex justify-content-center">
            {{ $materials->links() }}
        </div>
</div>

