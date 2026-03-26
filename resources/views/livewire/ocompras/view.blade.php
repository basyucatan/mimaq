@section('title', __('Ocompras'))
<div class="cardPrin">
    <div class="cardPrin-header">
        <div>Compras (OC)</div>
        <div class="d-flex gap-2">
            <input wire:model.live="keyWord" type="text" class="inpSolo" placeholder="Buscar">
            <button class="bot botVerde" wire:click="create">Nueva</button>
        </div>
    </div>
    <div class="cardPrin-body">
        @include('livewire.ocompras.modals.modals')
        @include('livewire.ocompras.modals.destino')        
        <div class="table-responsive d-none d-md-block">
            <div class="float-end">
                {{ $ocompras->links() }}
            </div>            
            <table class="table tabBase ch">
                <thead>
                    <tr>
                        <th>Folio</th>
                        <th>Solicitó</th>
                        <th class="text-center">Fecha</th>
                        <th>Cliente</th>
                        <th>Obra</th>
                        <th>Proveedor</th>
                        <th>Concepto</th>
                        <th>Total</th>
                        <th class="text-center">Estatus</th>
                        <th width="200">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($ocompras as $row)
                    <tr>
                        <td><span class="small text-muted">#{{ str_pad($row->id, 5, '0', STR_PAD_LEFT) }}</span></td>
                        <td>{{ Str::limit($row->Solicito->name, 20) }}</td>
                        <td class="text-center small">{{ \Carbon\Carbon::parse($row->fechaHSol)->format('d/m/y') }}</td>
                        <td>{{ Str::limit($row->obra->Cliente->empresa, 20) }}</td>
                        <td>{{ Str::limit($row->obra->obra, 30) }}</td>
                        <td><div class="fw-bold">{{ $row->proveedor->empresa ?? 'N/A' }}</div></td>
                        <td>{{ Str::limit($row->concepto, 50) }}</td>
                        <td>${{ number_format($row->subtotal * $factorIva, 2) }}</td>
                        <td class="text-center">
                            <span class="badge {{ $row->estatus == 'edicion' ? 'bg-warning text-dark' : ($row->estatus == 'cancelado' ? 'bg-danger' : 'bg-success') }} text-uppercase">
                                {{ $row->estatus }}
                            </span>
                        </td>
                        <td>@include('livewire.ocompras.botones')</td>
                    </tr>
                    @empty
                    <tr><td colspan="10" class="text-center text-muted">No hay registros</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="d-md-none">
            <div class="d-flex justify-content-end align-items-center mb-2">
                {{ $ocompras->links() }}
            </div>         
            @forelse($ocompras as $row)
            <div class="card border mb-2 shadow-sm border-start border-4 {{ $row->estatus == 'edicion' ? 'border-warning' : ($row->estatus == 'cancelado' ? 'border-danger' : 'border-success') }}">
                <div class="card-body p-2">
                    <div class="d-flex justify-content-between">
                        <span class="fw-bold">#{{ str_pad($row->id, 4, '0', STR_PAD_LEFT) }}</span>
                        <span class="fw-bold">${{ number_format($row->subtotal*$factorIva, 2) }}</span>
                        <span class="small text-muted">{{ App\Models\Util::formatFecha($row->fechaHSol,'Corta') }}</span>
                    </div>
                    <div class="fw-bold text-dark">
                        {{ $row->Solicito->name ?? '' }}➡️{{ $row->proveedor->empresa ?? '' }}
                    </div>
                    <span class="small text-muted">{{ $row->concepto }}</span>
                    <div>
                        🚧<span class="fw-bold">{{ $row->Obra->obra }} ({{ explode(' ', $row->Obra->Cliente->empresa)[0] ?? '' }})</span>
                    </div>
                    <div class="d-flex justify-content-between mt-1 pt-2 border-top">
                        @include('livewire.ocompras.botones')
                    </div>
                </div>
            </div>
            @empty
            <div class="text-center p-3 text-muted">Sin registros</div>
            @endforelse
        </div>
    </div>
</div>