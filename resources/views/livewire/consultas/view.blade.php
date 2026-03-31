@section('title', __('Consultas'))
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="cardPrin">
                <div class="cardPrin-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>Consultas y Reportes</div>
                    </div>
                </div>
                <div class="cardPrin-body">
                    <div class="row" wire:ignore>
                        <div class="col-md-4">
                            <div class="cardSec text-center">
                                <span class="fw-bold contador" data-target="{{ $obrasVigentes }}" data-sufijo=" 🏗️">0</span>
                                <small>Obras vigentes</small>
                            </div>
                        </div>                         
                        <div class="col-md-4">
                            <div class="cardSec text-center">
                                <span class="fw-bold contador" data-target="{{ $ocMes }}" data-sufijo=" 🧾">0</span>
                                <small>Compras {{ now()->translatedFormat('F Y') }}</small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="cardSec text-center">
                                <span class="fw-bold contador" data-target="{{ $gastoMes }}" data-decimales="2" data-prefijo="$" data-sufijo=" 💰">0</span>
                                <small>Gasto {{ now()->translatedFormat('F Y') }}</small>
                            </div>
                        </div>
                    </div>
                    <div class="cardSec mt-1">
                        <div class="cardSec-header">Explorador de Gastos</div>
                        <div class="cardSec-body">
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label class="etiBase"><small>Filtrar por Obra</small></label>
                                    <select wire:model.live="filtroObra" class="inpSolo">
                                        <option value="">Todas las obras</option>
                                        @foreach ($listaObras as $o)
                                            <option value="{{ $o->id }}">{{ $o->obra }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="etiBase"><small>División</small></label>
                                    <select wire:model.live="filtroDivision" class="inpSolo">
                                        <option value="">Todas</option>
                                        @foreach ($listaDivisiones as $d)
                                            <option value="{{ $d->id }}">{{ $d->division }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="etiBase"><small>Periodo (Mes/Año)</small></label>
                                    <input type="month" wire:model.live="filtroMes" class="inpSolo">
                                </div>
                                <div class="col-md-1">
                                    <button wire:click="$set('filtroObra', ''); $set('filtroDivision', '');"
                                        class="bot botNegro">Ver todo</button>
                                </div>
                            </div>

                            <div class="table-responsive" style="max-height: 50vh;">
                                <table class="table tabBase ch">
                                    <thead>
                                        <tr>
                                            <th>Fecha</th>
                                            <th>Obra (Cliente)</th>
                                            <th>División</th>
                                            <th class="text-end">Monto</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php $granTotal = 0; @endphp
                                        @forelse($resultados as $res)
                                            <tr>
                                                <td>{{ App\Models\Util::formatFecha($res['fecha'], 'Corta') }}</td>
                                                <td><small>{{ $res['concepto'] }}</small></td>
                                                <td><span class="badge bg-secondary">{{ $res['division'] }}</span></td>
                                                <td class="text-end">${{ number_format($res['monto'], 2) }}
                                                </td>
                                            </tr>
                                            @php $granTotal += $res['monto']; @endphp
                                        @empty
                                            <tr>
                                                <td colspan="4">Sin datos</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                    @if ($resultados->count() > 0)
                                        <tfoot class="sticky-bottom bg-dark text-white">
                                            <tr>
                                                <td colspan="2" class="text-end fw-bold">Total</td>
                                                <td colspan="2" class="text-end fw-bold">${{ number_format($granTotal, 2) }}</td>
                                            </tr>
                                        </tfoot>
                                    @endif
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
