@section('title', __('Folios'))
<div class="container-fluid p-2">
    <div class="cardSec">
        <div class="cardSec-header"> 
            Folios de trabajo
        </div>
        <div class="cardSec-body p-3">
            @if($IdFolio)
                <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 bg-light border rounded p-2 mb-3 shadow-sm">
                    <div class="d-flex flex-wrap align-items-center gap-2">
                        <div class="btn-group shadow-sm">
                            <button type="button" class="bot botAzul botChico" wire:click="imprimir({{ $folio->id }})" wire:loading.attr="disabled" wire:target="imprimir({{ $folio->id }})" title="Imprimir Folio">
                                <span wire:loading.remove wire:target="imprimir({{ $folio->id }})"><i class="bi bi-printer"></i></span>
                                <span wire:loading wire:target="imprimir({{ $folio->id }})">⏳</span>
                            </button>
                        </div>
                        <span class="badge bg-success px-3 py-2">
                            {{ $folio->totalBandejas }} Bandeja(s), Vence: {{ $folio->fechaVen }}
                        </span>
                    </div>
                    <div class="bg-warning text-black rounded-2 p-1">
                        <div class="fs-5 fw-bold">{{ $folio->id ?? '' }} | {{ $folio->Estilo->estilo ?? '' }}</div>
                    </div>
                </div>

                <div class="rounded border shadow-sm bg-white">
                    
                    <div class="cardSec-body border-bottom">
                        @livewire('foliosmats', ['IdFolio' => $IdFolio], key('foliosmats-'.$IdFolio))
                    </div>

                </div>

            @endif
        </div>
    </div>
</div>