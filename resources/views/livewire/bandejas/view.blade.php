@section('title', __('Bandejas'))
<div class="container-fluid p-0">
    <div class="row g-0 justify-content-center">
        <div class="col-12">
            <div class="cardPrin">
                <div
                    class="cardPrin-header d-flex flex-column flex-md-row justify-content-between align-items-stretch align-items-md-center gap-2 p-2">
                    <span class="fs-5 fw-bold text-nowrap align-self-center">Administración de Bandejas</span>
                    <div
                        class="d-flex flex-column flex-md-row align-items-stretch align-items-md-center gap-2 flex-grow-1 justify-content-end">
                        <div class="d-flex flex-column flex-sm-row align-items-stretch align-items-md-center gap-2">
                            <div class="position-relative">
                                <input wire:model.lazy="selected_id" wire:change="escanear" class="inpSolo"
                                    wire:keydown.escape="$set('selected_id','')" onfocus="this.select()"
                                    placeholder="Escanear...">
                                @if ($selected_id)
                                    <span wire:click="$set('selected_id','')" class="bot botNegro botChico"
                                        style="position: absolute; right: 6px; top: 50%; transform: translateY(-50%); cursor: pointer;">X</span>
                                @endif
                            </div>
                            <div class="position-relative">
                                <input wire:model.lazy="keyWord" class="inpSolo"
                                    wire:keydown.escape="$set('keyWord','')" onfocus="this.select()"
                                    placeholder="Buscar...">
                                @if ($keyWord)
                                    <span wire:click="$set('keyWord','')" class="bot botNegro botChico"
                                        style="position: absolute; right: 6px; top: 50%; transform: translateY(-50%); cursor: pointer;">X</span>
                                @endif
                            </div>
                            <div class="position-relative">
                                <select wire:model="IdFactura" wire:change="elegirFactura" class="inpSolo">
                                    <option value="">Elegir Factura</option>
                                    @foreach ($facturas as $key => $value)
                                        <option value="{{ $key }}">{{ $value }}</option>
                                    @endforeach
                                </select>
                                @error('IdFactura')
                                    <span
                                        class="text-danger small position-absolute start-0 top-100">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="d-flex align-items-center justify-content-sm-end gap-2 mt-1 mt-md-0">
                            <button class="bot {{ $IdFactura ? 'botVerde' : 'botGris' }}" wire:click="exportar"
                                title="Exportar Bandejas Terminadas" {{ $IdFactura ? '' : 'disabled' }}>
                                <i class="bi bi-box-arrow-up"></i>
                                <span>Exportar</span>
                            </button>
                            <button class="bot botVerde" wire:click="create" title="Nueva Bandeja">
                                <i class="bi bi-file-earmark-plus"></i><span>Nueva</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="cardPrin-body">
                <div class="d-flex justify-content-end mb-3">
                    {{ $bandejas->links() }}
                </div>
                @include('livewire.bandejas.modals')
                <div class="tablaCont mb-3" style="width: 100%; max-width: 100%; overflow-x: auto;">
                    <table class="table tabBase ch">
                        <thead>
                            <tr>
                                <th class="text-center">Bandeja</th>
                                <th class="text-left">Cliente | Lote | Estilo | Producto</th>
                                <th class="text-center">Proceso</th>
                                <th class="text-center">
                                    <i class="bi bi-shield-check text-danger fs-6"></i>
                                </th>
                                <th class="text-center">Estatus</th>
                                <th class="text-center">Factura</th>
                                <th class="text-end pe-3">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($bandejas as $row)
                                <tr>
                                    <td class="text-center">
                                        <span class="badge bg-secondary text-white">{{ $row->codigoBandeja }}</span>
                                    </td>
                                    <td class="text-left">
                                        <strong class="text-primary">{{ $row->cantidad }} Pz</strong>
                                        {{ $row->folio->ascendencia ?? '' }}
                                    </td>
                                    <td class="text-center">
                                        <span class="d-inline-block styleProceso">
                                            {{ substr($row->ultimoMovimiento?->proceso?->proceso ?? 'Sin Proceso', 0, 6) }}
                                            |
                                            {{ substr($row->ultimoMovimiento?->proceso?->depto?->depto ?? 'Sin Depto', 0, 6) }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <i
                                            class="bi {{ $row->enBoveda ? 'bi-shield-check text-danger fs-5' : 'bi-shield-x text-muted fs-6' }}"></i>
                                    </td>
                                    <td class="text-center">
                                        <div class="form-check form-switch d-inline-block">
                                            <input
                                                class="form-check-input {{ $row->estatus === 'terminado' ? 'bg-success' : 'bg-primary' }}"
                                                type="checkbox" role="switch" id="switchTerminado{{ $row->id }}"
                                                {{ $row->estatus === 'terminado' ? 'checked' : '' }}
                                                {{ !$row->factura?->factura && $IdFactura ? '' : 'disabled' }}
                                                wire:change="terminar({{ $row->id }})" style="cursor: pointer;">
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <span class="text-dark">{{ $row->factura->factura ?? '' }}</span>
                                    </td>
                                    <td>
                                        <div class="d-flex justify-content-end align-items-center gap-1">
                                            <button wire:model="selected_id"
                                                wire:click="iniciarTraspaso({{ $row->id }})"
                                                class="bot botNaranja botChico" title="Traspasar">
                                                ➡️
                                            </button>
                                            <div class="d-none d-md-flex gap-1">
                                                <button wire:click="verHistorial({{ $row->id }})"
                                                    class="bot botNegro botChico" title="Historial">
                                                    📈
                                                </button>
                                                <button wire:click="iniciarDivision({{ $row->id }})"
                                                    class="bot botAzul botChico" title="Dividir Bandeja">
                                                    ◀▶
                                                </button>
                                                <button wire:click="iniciarUnion({{ $row->id }})"
                                                    class="bot botVerde botChico" title="Unir con otra Bandeja">
                                                    ▶◀
                                                </button>
                                            </div>
                                            <button wire:click="edit({{ $row->id }})"
                                                class="bot botNaranja botChico" title="Editar">
                                                <i class="bi bi-pencil-square"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10" class="text-center p-4 bg-light text-muted">
                                        No se encontraron bandejas con los criterios de búsqueda.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if ($selected_id && !$verModalDividir && !$verModalUnir && !$verModalBandeja)
                    <div class="mt-4 border-top pt-3">
                        <h5 class="text-secondary fw-bold mb-3">Historial de Movimientos de la Bandeja</h5>
                        @livewire('bandejasmovs', ['IdBandeja' => $selected_id], key('bandejasmovs-' . $selected_id))
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
