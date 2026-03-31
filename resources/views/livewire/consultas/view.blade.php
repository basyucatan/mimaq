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
                                    <select wire:model.lazy="IdObra" class="inpBase">
                                        <option value=""></option>
                                        @foreach ($obras as $key => $value)
                                        <option value="{{ $key }}">{{ $value }}</option>
                                        @endforeach
                                    </select>                                    
                                </div>
                                <div class="col-md-3">
                                    <label class="etiBase"><small>División</small></label>
                                    <select wire:model.lazy="IdDivision" class="inpBase">
                                        <option value=""></option>
                                        @foreach ($divisions as $key => $value)
                                        <option value="{{ $key }}">{{ $value }}</option>
                                        @endforeach
                                    </select> 
                                </div>
                                <div class="col-md-4">
                                    <label class="etiBase"><small>Periodo (Mes/Año)</small></label>
                                    <input type="month" wire:model.live="filtroMes" class="inpSolo">
                                </div>
                                <div class="col-md-1">
                                    <button wire:click="$set('filtroObra', ''); $set('filtroDivision', '');"
                                        class="bot botVerde">Ver todo</button>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-4 text-end fw-bold">
                                    ${{ number_format($granTotal, 2) }}
                                </div>
                                <div class="col-8">
                                    <div class="d-flex justify-content-end mb-2">
                                        {{ $resultados->links() }}
                                    </div> 
                                </div>
                            </div>
                            <div class="table-responsive" style="max-height: 50vh;">
                                <table class="table tabBase ch">
                                    <thead>
                                        <tr>
                                            <th>Fecha</th>
                                            <th>Obra (Cliente)</th>
                                            <th>Div</th>
                                            <th class="text-end">Monto</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($resultados as $res)
                                            <tr>
                                                <td>{{ Util::formatFecha($res['fecha'], 'Corta') }}</td>
                                                <td style="max-width:110px;"><small>{{ $res['concepto'] }}</small></td>
                                                <td>
                                                    <span class="badge" style="background-color: {{ $res['color'] }};
                                                        color: {{ Util::colorTxtHex($res['color']) }};">
                                                        <span class="d-md-none">{{ substr($res['division'], 0, 3) }}</span>
                                                        <span class="d-none d-md-inline">{{ $res['division'] }}</span>
                                                    </span>
                                                </td>
                                                <td class="text-end">${{ number_format($res['monto'], 2) }}
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4">Sin datos</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                    @if ($resultados->count() > 0)
                                        <tfoot class="sticky-bottom bg-dark text-white">
                                            <tr class="table-secondary text-dark">
                                                <td colspan="3" class="text-end small fw-bold">SubTotal</td>
                                                <td class="text-end small">${{ number_format($resultados->getCollection()->sum('monto'), 2) }}</td>
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
