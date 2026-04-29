@section('title', __('Estilos'))
<div class="container-fluid p-3">
    <div class="row g-3">
        <div class="col-12 col-lg-3">
            @include('livewire.estilos.modals')
            <div class="cardSec">
                <div class="cardSec-header d-flex justify-content-between align-items-center">
                    <span class="fw-bold">Estilos</span>
                    <div class="me-2 position-relative" style="display:inline-block;">
                        <input wire:model.lazy="keyWord" class="inpSolo" 
                        wire:keydown.escape="$set('keyWord','')"
                        onfocus="this.select()" placeholder="Search...">
                        @if($keyWord)
                            <span wire:click="$set('keyWord','')" 
                                class="bot botNegro botChico"
                                style="position: absolute; right: 6px; top: 50%; transform: translateY(-50%); cursor: pointer;">
                                X
                            </span>
                        @endif
                    </div>                    
                    <button class="bot botVerde" wire:click="create"><i class="bi bi-plus-lg"></i></button>
                </div>
                <div class="cardSec-body" style="max-height: 70vh; overflow-y: auto; overflow-x: hidden;">
                    <div class="row g-2">
                        @forelse($estilos as $row)
                            <div class="col-12">
                                <div class="cardSec {{ $selected_id == $row->id ? 'border-primary shadow-sm' : '' }}" 
                                    wire:click="$set('selected_id', {{ $row->id }})" 
                                    style="cursor: pointer;">
                                    <div class="cardSec-body d-flex justify-content-between align-items-center">
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="border rounded bg-white d-flex align-items-center justify-content-center" style="width: 45px; height: 45px; overflow: hidden; flex-shrink: 0;">
                                                @if($row->foto)
                                                    <img src="{{ asset('storage/estilos/' . $row->foto) }}?v={{ time() }}" class="img-fluid" alt="foto">
                                                @else
                                                    <i class="bi bi-image text-muted"></i>
                                                @endif
                                            </div>
                                            <div>
                                                <h6 class="mb-0 fw-bold">{{ $row->estilo }}</h6>
                                                <small class="text-muted">{{ $row->clase->clase ?? $row->IdClase }}</small>
                                            </div>
                                        </div>
                                        <div class="btn-group">
                                            <button wire:click.stop="edit({{ $row->id }})" class="bot botNaranja botChico">
                                                <i class="bi bi-pencil"></i>
                                            </button>
                                            <button wire:click.stop="destroy({{ $row->id }})" class="bot botRojo botChico" onclick="confirm('¿Eliminar estilo?') || event.stopImmediatePropagation()">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                        <div class="text-center p-4">No hay resultados</div>
                        @endforelse
                    </div>
                </div>
                <div class="cardSec-footer">
                    {{ $estilos->links() }}
                </div>
            </div>
        </div>
        <div class="col-12 col-lg-9">
            @if($selected_id)
                <div class="cardSec">
                    <div class="cardSec-body">
                        @livewire('estilosdets', ['IdEstilo' => $selected_id], key('estilosdets-'.$selected_id))
                    </div>
                </div>
            @else
                <div class="h-100 d-flex flex-column justify-content-center align-items-center text-muted border rounded bg-light" style="min-height: 400px;">
                    <i class="bi bi-arrow-left-circle display-4"></i>
                    <p class="mt-2">Selecciona un estilo de la lista para gestionar sus materiales</p>
                </div>
            @endif
        </div>
    </div>
</div>