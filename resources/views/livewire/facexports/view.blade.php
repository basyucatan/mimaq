@section('title', __('Export'))
<div class="container-fluid p-2">
    <div class="cardPrin">
        <div class="card-header bg-danger text-white fs-5 ps-2">
            Facturas de Exportación
        </div>
        <div class="cardPrin-body">
            <div class="row">
                <div class="col-12 col-md-3">
                    @livewire('arbolfacturas', ['regimen' => 'RT'])
                </div>
                <div class="col-md-9">
                    @if($selected_id)
                        @livewire('facexportsdets', ['IdFactura' => $selected_id], key('facexportsdets-'.$selected_id))
                    @else
                        <div class="h-100 d-flex align-items-center justify-content-center border rounded bg-light text-muted">
                            <span>✔️ Select an invoice</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>