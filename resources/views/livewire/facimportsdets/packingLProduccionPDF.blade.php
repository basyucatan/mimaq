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
<div class="cabecera">
    <table width="100%" style="font-family: sans-serif; table-layout: fixed;">
        <tr>
            <td width="25%" rowspan="2" valign="middle">
                <img src="{{ public_path('img/logo.png') }}" width="40">
                <span>EMA INC.</span>
            </td>
            <td width="15%" valign="top">
                <strong>De</strong> USA<br>
                <strong>Para</strong> MEX
            </td>
            <td width="60%" valign="top" class="derecha">
                @php
                    $arregloGuias = $factura->guias;
                    if (is_string($arregloGuias)) {
                        $arregloGuias = json_decode($arregloGuias, true);
                    }
                @endphp
                @if(!empty($arregloGuias) && is_array($arregloGuias))
                    <strong>GUÍA(S):</strong> {{ implode(', ', $arregloGuias) }}<br>
                @endif
                <strong>FECHA:</strong> {{ Util::formatFecha($factura->fecha,'DD/MMM/AA') }} &nbsp;&nbsp;
                <strong>FACTURA:</strong> {{ $factura->factura }} &nbsp;&nbsp;
                <strong>PÁGINA:</strong> <span class="pagina"></span>
            </td>
        </tr>
        <tr>
            <td colspan="2" valign="top">
                <strong>LISTA DE EMPAQUE</strong>
            </td>
        </tr>
    </table>
</div>
<table width="100%" style="table-layout: fixed;">
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
                $fechaVencimiento = $folioObj?->fechaVen ? Util::formatFecha($folioObj->fechaVen, 'MM/DD/YYYY') : '';
                $subPeso = $detalles->sum('pesoG');
                $subImp = $detalles->sum(fn($item) => $item->cantidad * $item->precioU);
            @endphp
            <tr class="fila-separadora">
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
                <tr class="fila-material {{ $filaNum % 2 != 0 ? 'gris' : '' }}">
                    <td></td>
                    <td>
                        {{ $item->material->material ?? '' }}<strong> {{ $item->propsTot }}</strong>
                    </td>
                    <td class="derecha">{{ number_format($item->cantidad, 2) }}</td>
                    <td class="derecha" style="font-size: 9px; color: #444;">
                        @if($item->pesoEnUMat > 0)
                            {{ number_format($item->pesoEnUMat, 2) }} {{ $item->material->unidadP->unidad ?? '' }}
                        @endif
                    </td>
                    <td class="derecha">{{ number_format($item->pesoG, 3) }}</td>
                    <td class="derecha">{{ number_format($item->precioU, 2) }}</td>
                    <td class="derecha">{{ number_format($imp, 2) }}</td>
                </tr>
            @endforeach
            <tr class="fila-subtotal">
                <td colspan="4"></td>
                <td class="derecha">{{ number_format($subPeso, 3) }}</td>
                <td class="derecha"></td>
                <td class="derecha">{{ number_format($subImp, 2) }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
<table width="100%" class="total-general-contenedor" style="table-layout: fixed;">
    <tr>
        <td width="53%"></td>
        <td width="10%" class="derecha negrita">TOTAL GENERAL:</td>
        <td width="10%" class="derecha negrita borde-total-general">{{ number_format($totalGralPeso, 3) }}</td>
        <td width="15%" class="derecha"></td>
        <td width="12%" class="derecha negrita borde-total-general">{{ number_format($totalGralImp, 2) }}</td>
    </tr>
</table>
</body>
</html>