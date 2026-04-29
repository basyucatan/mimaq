@section('title', __('Clases'))
<div class="container-fluid p-0">
    <div class="row g-0 justify-content-center">
        <div class="col-12">
            <div class="cardPrin">
                <div class="cardPrin-header">
                    <span>Clases</span>
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
                        <button class="bot botVerde" wire:click="create" title="Nuevo Clase">
                            <i class="bi bi-file-earmark-plus"></i>
                        </button>                   
                    </div>                
                </div>
                <div class="cardPrin-body">    
                    <div class="d-flex justify-content-end mb-2">
                        {{ $clases->links() }}
                    </div>                               
                    @include('livewire.clases.modals')
                    <div class="row g-1">
                        @forelse($clases as $row)
                            <div class="col-12 col-sm-6 col-md-3 col-lg-3">
                                <div class="cardSec">
                                    <div class="cardSec-header">
                                        {{ $row->clase }}
                                    </div>
                                    <div class="cardSec-body">
                                        <strong>{{ $row->IdAccess }}, {{ $row->claseI }}</strong><br>
                                        Tipo: <strong>{{ $row->Tipo->tipo }}</strong>, Fracción: <strong>{{ $row->Arancel->arancel }}</strong>
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
