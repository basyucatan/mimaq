@section('title', __('Catálogos'))
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="cardPrin" style="display: flex; flex-direction: column; height: 80vh;">
                <div class="cardPrin-header d-flex justify-content-between align-items-center flex-shrink-0">
                    <div class="fw-bold">Catálogos</div>
                    <div class="d-flex gap-2">
                        <select wire:model.live="catalogo" class="inpSolo">
                            @foreach($catalogos as $cat)
                                <option value="{{ $cat }}">{{ $cat }}</option>
                            @endforeach
                        </select>
                        <button class="bot botVerde" wire:click="create">+</button>
                    </div>
                </div>
                <div class="cardPrin-body" style="flex: 1 1 auto; overflow-y: scroll; padding: 15px;">
                    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-3">
                        @forelse($items as $item)
                            <div class="col">
                                <div class="cardSec">
                                    <div class="cardSec-header">
                                        <span class="fw-bold">#: {{ $item['id'] }}</span>
                                        <div class="d-flex gap-1">
                                            <button wire:click="edit({{ $item['id'] }})" class="bot botNaranja p-1 px-2" title="Editar">
                                                <i class="bi bi-pencil-square"></i>
                                            </button>
                                            <button wire:click="destroy({{ $item['id'] }})" class="bot botRojo p-1 px-2" onclick="confirm('¿Eliminar?') || event.stopImmediatePropagation()" title="Eliminar">
                                                <i class="bi bi-trash3-fill"></i>
                                            </button>
                                        </div>                                        
                                    </div>
                                    <div class="cardSec-body">
                                            @foreach($cols as $col)
                                                @if($col !== 'id')
                                                    <div class="d-flex border-bottom py-1 align-items-baseline">
                                                        <div class="text-muted fw-bold" style="width: 35%; font-size: 0.75rem; text-transform: uppercase;">
                                                            {{ ucwords(preg_replace('/(?<!^)[A-Z]/', ' $0', $col)) }}:
                                                        </div>
                                                        <div class="text-dark ps-2 flex-grow-1" style="font-size: 0.85rem;">
                                                            {{ $item[$col] ?? '---' }}
                                                        </div>
                                                    </div>
                                                @endif
                                            @endforeach
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-12 text-center py-5">
                                <p class="text-muted">No se encontraron datos en este catálogo.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('livewire.catalogos.modals')
</div>