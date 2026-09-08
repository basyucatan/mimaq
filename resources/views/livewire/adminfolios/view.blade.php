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
                </div>

                <div class="col-12 col-md-9">
                    <div class="ficha">
                        <div class="ficha-headers">
                            <button class="ficha-boton active">Materiales</button>
                            <button class="ficha-boton">Bandejas</button>
                        </div>
                        <div class="ficha-body active">
                            <div>
                                @if($IdFolio)
                                    @livewire('foliosmats', ['IdFolio' => $IdFolio], key('foliosmats-'.$IdFolio))
                                @else
                                    <div class="card-body">
                                        <span>✔️ Selecciona un folio</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="ficha-body">
                            <div>
                                @if($IdFolio)
                                    @livewire('foliosbandejas', ['IdFolio' => $IdFolio], key('foliosbandejas-'.$IdFolio))
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>