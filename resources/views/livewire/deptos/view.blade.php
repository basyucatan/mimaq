@section('title', __('Deptos'))
<div class="container-fluid p-3">
    <div class="row g-3">
        <div class="col-12 col-lg-4">
            @include('livewire.deptos.modals')
            <div class="cardSec">
                <div class="cardSec-header d-flex justify-content-between align-items-center">
                    <span class="fw-bold">Departamentos</span>
                    <div class="me-2 position-relative" style="display:inline-block;">
                        <input wire:model.lazy="keyWord" class="inpSolo" wire:keydown.escape="$set('keyWord','')" onfocus="this.select()" placeholder="Buscar...">
                        @if($keyWord)
                            <span wire:click="$set('keyWord','')" class="bot botNegro botChico" style="position: absolute; right: 6px; top: 50%; transform: translateY(-50%); cursor: pointer;">X</span>
                        @endif
                    </div>
                    <button class="bot botVerde" wire:click="create"><i class="bi bi-plus-lg"></i></button>
                </div>
                <div class="cardSec-body" style="max-height: 75vh; overflow-y: auto; overflow-x: hidden;">
                    <div class="row g-2">
                        @forelse($deptos as $row)
                            <div class="col-12">
                                <div class="cardSec {{ $selected_id == $row->id ? 'border-primary shadow-sm' : '' }}" wire:click="$set('selected_id', {{ $row->id }})" style="cursor: pointer;">
                                    <div class="cardSec-body d-flex justify-content-between align-items-center p-2">
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="rounded-circle bg-light d-flex align-items-center justify-content-center text-primary fw-bold" style="width: 35px; height: 35px; flex-shrink: 0; border: 1px solid #dee2e6;">
                                                {{ $row->orden ?? $loop->iteration }}
                                            </div>
                                            <div>
                                                <h6 class="mb-0 fw-bold">{{ $row->depto }}</h6>
                                                <small class="text-muted">{{ $row->tipo }}</small>
                                            </div>
                                        </div>
                                        <div class="btn-group">
                                            <button wire:click.stop="edit({{ $row->id }})" class="bot botNaranja botChico">
                                                <i class="bi bi-pencil"></i>
                                            </button>
                                            <button wire:click.stop="destroy({{ $row->id }})" class="bot botRojo botChico" onclick="confirm('¿Eliminar departamento?') || event.stopImmediatePropagation()">
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
                <div class="cardSec-footer p-2">
                    {{ $deptos->links() }}
                </div>
            </div>
        </div>
        <div class="col-12 col-lg-8">
            @if($selected_id)
                <div class="cardSec">
                    <div class="cardSec-header bg-white">
                        <i class="bi bi-gear-fill me-2"></i> Procesos del Departamento
                    </div>
                    <div class="cardSec-body">
                        @livewire('procesos', ['IdDepto' => $selected_id], key('procesos-'.$selected_id))
                    </div>
                </div>
            @else
                <div class="h-100 d-flex flex-column justify-content-center align-items-center text-muted border rounded bg-light" style="min-height: 450px; border-style: dashed !important;">
                    <i class="bi bi-arrow-left-circle display-4"></i>
                    <p class="mt-2 fw-bold">Gestión de Procesos</p>
                    <small>Selecciona un departamento del panel izquierdo para configurar sus procesos y mermas.</small>
                </div>
            @endif
        </div>
    </div>
</div>