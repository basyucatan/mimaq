<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="{{ public_path('css/reportes.css') }}">
</head>
<body>
<div class="cabecera">
    <table width="100%" style="font-family: sans-serif;">
        <tr>
            <td width="50%" rowspan="2" valign="middle" style="border: none; text-align: left;">
                <span class="negrita" style="font-size: 11pt;">EMA DE YUCATAN, S.A. DE C.V.</span><br>
                CALLE 64 #366-A X 39 Y 41 COL. CENTRO<br>
                MERIDA, YUCATAN, MEXICO<br>
                TELEFONO (999) 920-37-79<br>
                RFC: EYU-030827-858 &nbsp;&nbsp; CP 97000
            </td>
            <td width="50%" valign="top" style="border: none; text-align: right;">
                <table width="100%">
                    <tr>
                        <td class="negrita centro" style="background: #001060; color: white; padding: 4px;">FACTURA #</td>
                        <td class="negrita centro" style="background: #001060; color: white; padding: 4px;">FECHA</td>
                    </tr>
                    <tr>
                        <td class="centro negrita">{{ $factura->factura }}</td>
                        <td class="centro">{{ \Carbon\Carbon::parse($factura->fecha)->format('d/m/Y') }}</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</div>
<table width="100%" style="margin-bottom: 20px;">
    <thead>
        <tr>
            <th width="50%">VENDIDO A:</th>
            <th width="50%">ENVIAR A:</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td valign="top" style="border: none; padding: 8px 5px;" width="40%">
                <span class="negrita">EMA JEWELRY INC.</span><br>
                2 EXECUTIVE DRIVE SUITE 270<br>
                FORT LEE, N.J. 07024 U.S.A<br>
                TELEFONO (212) 575-89-89<br>
                TAX ID: 132908878
            </td>
            <td valign="top" style="border: none; padding: 8px 5px;" width="60%">
                <span class="negrita">EMA JEWELRY INC.</span><br>
                2 EXECUTIVE DRIVE SUITE 270<br>
                FORT LEE, N.J. 07024 U.S.A<br>
                TELEFONO (212) 575-89-89<br>
                VIA EMBARQUE: {{ $factura->adicionales['viadE'] ?? 'FEDEX' }}<br>
                @php
                    $arregloGuias = $factura->guias;
                    if (is_string($arregloGuias)) {
                        $arregloGuias = json_decode($arregloGuias, true);
                    }
                @endphp
                @if(!empty($arregloGuias) && is_array($arregloGuias))
                    GUIA(S): {{ implode(', ', $arregloGuias) }}
                @endif
            </td>
        </tr>
    </tbody>
</table>
<table>
    <thead>
        <tr>
            <th width="8%">CANT.</th>
            <th width="8%">UNIDAD</th>
            <th width="34%">DESCRIPCION</th>
            <th width="12%" class="derecha">PESO GRMS</th>
            <th width="13%" class="derecha">VALOR M.P.</th>
            <th width="13%" class="derecha">V. AGREGADO</th>
            <th width="12%" class="derecha">TOTAL COM.</th>
        </tr>
    </thead>
    <tbody>
        @php $contadorLineas = 0; @endphp
        @foreach($itemsFactura as $item)
            @php $contadorLineas++; @endphp
            <tr class="{{ $contadorLineas % 2 != 0 ? 'gris' : '' }}">
                <td class="centro">{{ number_format($item['cantidad'], 0) }}</td>
                <td class="centro">PCS</td>
                <td>{{ $item['descripcion'] }}</td>
                <td class="derecha">{{ number_format($item['pesoG'], 3) }}</td>
                <td class="derecha">$ {{ number_format($item['valorMP'], 2) }}</td>
                <td class="derecha">$ {{ number_format($item['valorVA'], 2) }}</td>
                <td class="derecha negrita">$ {{ number_format($item['totalComercial'], 2) }}</td>
            </tr>
        @endforeach
        <tr>
            <td class="centro negrita total">{{ number_format($totalesFactura['cantidad'], 0) }}</td>
            <td class="centro negrita total">PCS</td>
            <td class="negrita total">TOTALES</td>
            <td class="derecha negrita total">{{ number_format($totalesFactura['pesoG'], 3) }}</td>
            <td class="derecha negrita total">$ {{ number_format($totalesFactura['valorMP'], 2) }}</td>
            <td class="derecha negrita total">$ {{ number_format($totalesFactura['valorVA'], 2) }}</td>
            <td class="derecha negrita total" style="background: #e0e0e0;">$ {{ number_format($totalesFactura['totalComercial'], 2) }}</td>
        </tr>
    </tbody>
</table>
<div style="margin-top: 25px;">
    <span class="negrita">DESGLOSE POR FRACCION ARANCELARIA</span>
    <table width="50%" style="margin-top: 5px;">
        <thead>
            <tr>
                <th width="30%">CANTIDAD</th>
                <th width="40%">FRACCION ARANCELARIA</th>
                <th width="30%" class="derecha">PESO GRMS</th>
            </tr>
        </thead>
        <tbody>
            @foreach($resumenArancelario as $fraccion => $datos)
                <tr>
                    <td class="centro">{{ number_format($datos['cantidad'], 0) }}</td>
                    <td class="centro negrita">{{ $fraccion ?? '71131999' }}</td>
                    <td class="derecha">{{ number_format($datos['pesoG'], 3) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
</body>
</html>