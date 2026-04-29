@section('title', __('Materials'))
<div class="container-fluid p-0">
    <div class="row g-0 justify-content-center">
        <div class="col-12">
            <div class="cardPrin">
                <div class="cardPrin-header">
                    <span>Materials</span>
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
                    <div>
                        <button class="bot botVerde" wire:click="create" title="Nuevo Material">
                            <i class="bi bi-file-earmark-plus"></i>
                        </button>
                    </div>
                </div>
                <div class="cardPrin-body" style="max-height: 80vh; overflow-y: auto; overflow-x: hidden;">
                    <div class="d-flex justify-content-end mb-2">
                        {{ $materials->links() }}
                    </div>
                    @include('livewire.materials.modals')
                    <div class="row g-1">
                        @forelse($materials as $row)
                            <div class="col-12 col-sm-6 col-md-3 col-lg-3">
                                <div class="cardSec">
                                    <div class="cardSec-header">
                                        {{ $row->material }} ( {{ $row->abreviatura }} )
                                    </div>
                                    <div class="cardSec-body">
                                        <strong>{{ $row->Clase->IdAccess }}</strong>, 
                                        Inglés: <strong>{{ $row->materialI }}</strong><br>
                                        Unidad: <strong>{{ $row->Unidad->unidad }}</strong>
                                        Unidad Peso: <strong>{{ $row->UnidadP->unidad }}</strong><br>
                                        Descripción Fiscal: <strong>{{ $row->materialFiscal }}</strong>
                                    </div>
                                    <div class="cardSec-footer">
                                        <button wire:click="edit({{ $row->id }})"
                                                class="bot botNaranja"
                                                title="Editar">
                                            <i class="bi-pencil-square"></i>
                                        </button>
                                        <button wire:click="destroy({{ $row->id }})"
                                                class="bot botRojo"
                                                onclick="confirm('¿Estás seguro?') || event.stopImmediatePropagation()">
                                            <i class="bi-trash3-fill"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @empty
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
