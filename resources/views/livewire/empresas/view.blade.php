@section('title', __('Empresas'))
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="cardPrin">
                <div class="cardPrin-header">
                    <div>
                        {{$tituloTipo}}
                    </div>
                    <div>
                        <input wire:model.live="keyWord" type="text" class="inpSolo" placeholder="Buscar {{ $tipoContexto }}...">
                    </div>
                    <div>
                        <button class="bot botVerde" wire:click="create" title="Nuevo {{ $tipoContexto }}">
                            <i class="bi bi-file-earmark-plus"></i>
                        </button>
                    </div>
                </div>
                <div class="cardPrin-body">
                    @include('livewire.empresas.modals')
                    <div class="row g-1">
                        @forelse($empresas as $row)
                            <div class="col-12 col-sm-6 col-md-3 col-lg-3">
                                <div class="cardSec">
                                    <div class="cardSec-header">
                                        {{ $row->empresa }} - {{ $row->rfc }}
                                    </div>
                                    <div class="cardSec-body">
                                        {{ $row->razonSocial }}<br>
                                        {{ $row->direccion }}<br>
                                        {{ $row->telefono }}<br>
                                        {{ $row->email }}
                                    </div>
                                    <div class="cardSec-footer d-flex justify-content-end gap-2">
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
                    <div class="d-flex justify-content-center mt-3">
                        {{ $empresas->links() }}
                    </div>                  
                </div>
            </div>
        </div>
    </div>
</div>
