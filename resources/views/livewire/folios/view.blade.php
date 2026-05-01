@section('title', __('import'))
<div class="container-fluid p-2">
    <div class="cardSec">
        <div class="cardSec-header">
            Generación de Folios
        </div>
        <div class="cardSec-body p-3">
            @if($IdFolio)
                <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 bg-light border rounded p-2 mb-3 shadow-sm">
                    <div class="d-flex flex-wrap align-items-center gap-2">
                        <div class="btn-group shadow-sm">
                            <button type="button" 
                                class="bot botBlanco botChico" 
                                wire:click="generarMateriales"
                                @if($folio->tieneMats) disabled @endif>
                                <span class="text-primary">{{ $folio->tieneMats ? '✅' : '⏬' }}</span>
                                <small class="fw-bold">
                                    {{ $folio->tieneMats ? 'Materiales Cargados' : 'Generar Materiales' }}
                                </small>
                            </button>
                            <button type="button" class="bot botBlanco botChico" wire:click="limpiar">
                                <span class="text-danger">🗑️</span>
                                <small class="fw-bold">Limpiar</small>
                            </button>
                        </div>
                        <div class="btn-group shadow-sm">
                            <button type="button" class="bot botBlanco botChico" wire:click="confirmarIngreso">
                                <span class="text-success">🔐</span>
                                <small class="fw-bold">Entrada</small>
                            </button>
                        </div>
                        <span class="badge bg-success px-3 py-2">
                            {{ $folio->totalBandejas }} Bandeja(s)
                        </span>
                        <small class="text-muted">
                            Vence: {{ $folio->fechaVen }}
                        </small>
                        <span class="fw-semibold text-dark">
                            {{ $folio->jobStyle }}
                        </span>
                    </div>
                    <div class="d-none d-lg-block">
                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-3 py-2">
                            Estilo: <strong>#{{ $folio->Estilo->estilo ?? '' }}</strong>
                        </span>
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