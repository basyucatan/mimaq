@section('title', __('import'))
<div class="container-fluid p-2">
    <div class="cardPrin">
        <div class="card-header bg-success text-white fs-5 ps-2">
            Facturas de importación
        </div>
        <div class="cardPrin-body">
            <div class="row">
                <div class="col-12 col-md-3">
                    @livewire('arbolfacturas', ['Regimen' => 'IN'])
                </div>
                <div class="col-md-9">
                    @if($selected_id)
                        @livewire('facimportsdets', ['IdFactura' => $selected_id], key('dets-'.$selected_id))
                    @else
                        <div class="h-100 d-flex align-items-center justify-content-center border rounded bg-light text-muted">
                            <span>✚ Seleccione una factura para ver sus partidas</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>