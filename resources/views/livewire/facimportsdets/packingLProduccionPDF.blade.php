@php
    $totalGralCant = 0;
    $totalGralPeso = 0;
    $totalGralImp = 0;
    $filaNum = 0;
@endphp
<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="{{ public_path('css/reportes.css') }}">
</head>
<body>
<div class="sub-cabecera">
    <table width="100%">
        <tr>
            <td width="20%" rowspan="2">
                <img src="{{ public_path('img/logo.png') }}" width="40">
                <span>EMA INC.</span>
            </td>
            <td width="15%">
                <strong>From</strong> USA<br>
                <strong>To</strong> MEX
            </td>
            <td width="20%">
                <h2>Packing List</h2>
            </td>
            <td width="45%" class="derecha">
                @php
                    $arregloGuias = $factura->guias;
                    if (is_string($arregloGuias)) {
                        $arregloGuias = json_decode($arregloGuias, true);
                    }
                @endphp
                @if(!empty($arregloGuias) && is_array($arregloGuias))
                    <strong>Airway Bill(s):</strong> {{ implode(', ', $arregloGuias) }}<br>
                @endif
                <strong>Date:</strong> {{ $factura->fecha ?? '' }} &nbsp;&nbsp;
                <strong>Invoice #:</strong> {{ $factura->factura }} &nbsp;&nbsp;
                <strong>Page:</strong><span class="pagina"></span>
            </td>
        </tr>
    </table>
</div>
<table width="100%">
    <thead>
        <tr>
            <th width="10%" style="text-align: left;">Lot #</th>
            <th width="37%" style="text-align: left;">Description</th>
            <th width="8%" class="derecha">Qty</th>
            <th width="12%" class="derecha">Weight</th>
            <th width="10%" class="derecha">Grms</th>
            <th width="10%" class="derecha">UnitPr</th>
            <th width="12%" class="derecha">Total</th>
        </tr>
    </thead>
    <tbody>
        @foreach($itemsAgrupados as $idFolio => $detalles)
            @php
                $primerItem = $detalles->first();
                $folioObj = $primerItem->folio;
                $loteObj = $folioObj?->lote;
                $ordenObj = $loteObj?->orden;
                $clienteObj = $ordenObj?->cliente;
                $loteIdentificador = $loteObj->lote ?? '-';
                $ordenNombre = $ordenObj->orden ?? '';
                $clienteNombre = $clienteObj->cliente ?? '';
                $cantidadFolio = $folioObj->cantidad ?? 0;
                $fechaVencimiento = $folioObj?->fechaVen ? $folioObj->fechaVen : '';
                $subPeso = $detalles->sum('pesoG');
                $subImp = $detalles->sum(fn($item) => $item->cantidad * $item->precioU);
            @endphp
            <tr class="separadora">
                <td class="negrita">{{ $loteIdentificador }}</td>
                <td>
                    Order# <span class="negrita">{{ $ordenNombre }}</span> &nbsp;&nbsp; Cust.: <span class="negrita">{{ $clienteNombre }}</span> @if($fechaVencimiento) &nbsp;&nbsp; Due Date: <span class="negrita">{{ $fechaVencimiento }}</span> @endif
                </td>
                <td class="derecha negrita">
                    {{ number_format($cantidadFolio, 2) }}
                </td>
                <td colspan="4"></td>
            </tr>
            @foreach($detalles as $item)
                @php
                    $imp = $item->cantidad * $item->precioU;
                    $totalGralCant += $item->cantidad;
                    $totalGralPeso += $item->pesoG;
                    $totalGralImp += $imp;
                    $esCasting = ($item->material?->clase?->IdTipo == 1);
                    $filaNum++;
                @endphp
                <tr class="detalle {{ $filaNum % 2 != 0 ? 'gris' : '' }}">
                    <td></td>
                    <td>
                        {{ $item->material->materialI ?? '' }}<strong> {{ $item->propsTot }}</strong>
                    </td>
                    <td class="derecha">{{ number_format($item->cantidad, 2) }}</td>
                    <td class="derecha">
                        @if($item->pesoEnUMat > 0)
                            {{ number_format($item->pesoEnUMat, 2) }} {{ $item->material->unidadP->unidad ?? '' }}
                        @endif
                    </td>
                    <td class="derecha">{{ number_format($item->pesoG, 3) }}</td>
                    <td class="derecha">{{ number_format($item->precioU, 2) }}</td>
                    <td class="derecha">{{ number_format($imp, 2) }}</td>
                </tr>
            @endforeach
            <tr class="subtotal">
                <td colspan="4"></td>
                <td class="derecha">{{ number_format($subPeso, 3) }}</td>
                <td class="derecha"></td>
                <td class="derecha">{{ number_format($subImp, 2) }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
<table width="100%" class="totales" style="table-layout: fixed;">
    <tr>
        <td width="30%"></td>
        <td width="28%" class="derecha negrita">TOTAL GENERAL:</td>
        <td width="20%" class="derecha negrita bordes">{{ number_format($totalGralPeso, 3) }}</td>
        <td width="22%" class="derecha negrita bordes">{{ number_format($totalGralImp, 2) }}</td>
    </tr>
</table>
</body>
</html>