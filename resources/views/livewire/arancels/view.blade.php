@section('title', __('Arancels'))
<div class="container-fluid p-0">
    <div class="row g-0 justify-content-center">
        <div class="col-12">
            <div class="cardPrin">
                <div class="cardPrin-header">
                    <span>Arancels</span>
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
                        <button class="bot botVerde" wire:click="create" title="Nuevo Arancel">
                            <i class="bi bi-file-earmark-plus"></i>
                        </button>                   
                    </div>                
                </div>
                <div class="cardPrin-body" style="max-height: 75vh; overflow-y: auto; overflow-x: hidden;">
                    <div class="d-flex justify-content-end mb-2">
                        {{ $arancels->links() }}
                    </div>                               
                    @include('livewire.arancels.modals')
                    <div class="row g-1">
                        @forelse($arancels as $row)
                            <div class="col-12 col-sm-6 col-md-3 col-lg-3">
                                <div class="cardSec">
                                    <div class="cardSec-header">
                                        {{ $row->arancel }}
                                    </div>
                                    <div class="cardSec-body">
                                        <strong>{{ $row->descripcion }}</strong><br>
                                        Fracción en USA: <strong>{{ $row->arancelUSA }}</strong>, 
                                        Permiso: <strong>{{ $row->Permiso->permiso }}</strong>
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
