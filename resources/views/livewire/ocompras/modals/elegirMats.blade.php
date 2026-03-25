<div class="card mt-2">
    <div class="card-body">
        <div class="row g-2">
            <div class="col-2">
                <input type="number" step="1" wire:model="cantidadMat" class="inpSolo">
            </div>
            <div class="col-10 position-relative">
                <div class="input-group">
                    <input wire:model.live="keyWordMat" type="text" class="form-control inpBase" placeholder="🔍 Buscar material o referencia...">
                    <button type="button" wire:click="toggleNuevoMaterial" class="bot {{ $verNuevoMat ? 'botRojo' : 'botAzul' }}">✚</button>
                </div>
                @if(count($mats) > 0)
                <div class="position-absolute bg-white border shadow-lg w-100 mt-1 rounded-3" style="z-index: 2000; max-height: 400px; overflow-y: auto;">
                    @foreach($mats as $m)
                    <a href="javascript:void(0)" wire:click="elegirMaterial({{ $m->id }})"
                            class="d-block p-2 border-bottom text-decoration-none list-group-item-action small">
                        <div class="d-flex gap-2 align-items-center">
                            @if($m->color)
                            <span class="cuadroColor" style="background-color: {{ $m->color->colorRgba }}; width: 12px; height: 12px; border-radius: 2px;"></span>
                            @endif
                            <span class="fw-bold text-dark small">{{ $m->material->material }} ({{ $m->unidad }})</span>
                            <span class="badge bg-secondary ms-auto" style="font-size: 0.6rem;">{{ $m->referencia }}</span>
                        </div>
                    </a>
                    @endforeach
                </div>
                @elseif(strlen($keyWordMat) > 2)
                <div class="position-absolute bg-white border shadow-lg w-100 p-2 text-center rounded-3 mt-1" style="z-index: 2000;">
                    <small class="d-block mb-1 text-muted small">No se encontró "{{$keyWordMat}}"</small>
                    <button type="button" wire:click="toggleNuevoMaterial" class="bot botNaranja botSm">Dar de Alta</button>
                </div>
                @endif
            </div>
        </div>
        @if($verNuevoMat)
        @include('livewire.ocompras.nuevoMaterial')
        @endif
    </div>
</div>