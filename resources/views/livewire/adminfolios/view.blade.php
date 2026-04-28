@section('title', __('Folios'))
<div class="container-fluid p-2">
    <div class="cardPrin">
        <div class="card-header bg-primary text-white fs-5 ps-2">
            Folios de trabajo
        </div>
        <div class="cardPrin-body">
            <div class="row">
                <div class="col-12 col-md-3">
                    @livewire('arbolfolios')
                    @livewire('arbolboveda')
                </div>
                <div class="col-md-9">
                    @if($IdFolio)
                        @livewire('folios', ['IdFolio' => $IdFolio], key('folios-'.$IdFolio))
                    @else
                        <div class="h-100 d-flex align-items-center justify-content-center border rounded bg-light text-muted">
                            <span>✔️ Selecciona un folio</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>