@section('title', __('import'))
<div class="container-fluid p-2" style="max-height:90vh;">
    <div class="cardPrin">
        <div class="card-header bg-success text-white fs-5 ps-2">
            Import Invoices
        </div>
        <div class="cardPrin-body">
            <div class="row">
                <div class="col-12 col-md-3">
                    @livewire('arbolfacturas', ['regimen' => 'IN'])
                </div>
                <div class="col-md-9">
                    @if($selected_id)
                        @livewire('facimportsdets', ['IdFactura' => $selected_id], key('dets-'.$selected_id))
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