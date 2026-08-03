@php
    $totalGralCant = 0;
    $totalGralPeso = 0;
    $totalGralImp = 0;
    $filaNum = 0;
@endphp
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="{{ public_path('css/reportes.css') }}">
</head>
<body>
    <header class="cabecera">
        <img src="{{ public_path('img/logo.png') }}" class="logo">
        <div style="margin-left: 50px;">
            <strong style="font-size: 12pt;">EMA JEWELRY INC.</strong><br>
            2 EXECUTIVE DRIVE SUITE 270, FORT LEE, N.J. 07024 U.S.A<br>
            ID TAX: 132908878
        </div>
    </header>
    <table style="margin-bottom: 5px; border: none;">
        <tr>
            <td width="50%" style="border: none; padding-right: 10px;">
                <div class="linea-detalle">SOLD TO:</div>
                <strong style="font-size: 9pt;">EMA DE YUCATAN, S.A. DE C.V.</strong><br>
                CALLE 64 #366-A X 39 Y 41 COL. CENTRO<br>
                MERIDA, YUCATAN, MEXICO | RFC: EYU-030827-858
            </td>
            <td width="50%" style="border: none; padding-left: 10px;">
                <div class="linea-detalle">SHIPPED TO:</div>
                <strong style="font-size: 9pt;">EMA DE YUCATAN, S.A. DE C.V.</strong><br>
                CALLE 64 #366-A X 39 Y 41 COL. CENTRO<br>
                MERIDA, YUCATAN, MEXICO | RFC: EYU-030827-858
            </td>
        </tr>
    </table>
<table style="margin-bottom: 5px; border: none; table-layout: fixed; width: 100%;">
        <tr>
            <td width="25%" style="border: none; vertical-align: top;">
                <span class="negrita">INVOICE:</span> {{ $factura->factura }}<br>
                <span class="negrita">DATE:</span> {{ Util::formatFecha($factura->fecha,'MM/DD/YY') }}
            </td>
            <td width="20%" style="border: none;"></td>
            <td width="55%" style="border: none; vertical-align: top;" class="derecha">
                <span class="negrita">CARRIER:</span> {{ $factura->adicionales['viadE'] ?? '' }}<br>
                @php
                    $arregloGuias = $factura->guias;
                    if (is_string($arregloGuias)) {
                        $arregloGuias = json_decode($arregloGuias, true);
                    }
                @endphp
                @if(!empty($arregloGuias) && is_array($arregloGuias))
                    <span class="negrita">AWB:</span> {{ implode(', ', $arregloGuias) }}<br>
                @endif
                <span class="negrita">PACKAGES:</span> {{ $factura->adicionales['nPaq'] ?? '' }}
            </td>
        </tr>
    </table>   
    <table>
        <thead>
            <tr>
                <th width="12%" class="centro">QTY</th>
                <th width="10%" class="centro">UNIT</th>
                <th width="48%">DESCRIPTION</th>
                <th width="15%" class="derecha">WGT. GR</th>
                <th width="15%" class="derecha">AMOUNT</th>
            </tr>
        </thead>
        <tbody>
            @foreach($itemsAgrupados as $nombreGrupo => $detalles)
                @php
                    $filaNum++;
                    $cantGrupo = $detalles->sum('cantidad');
                    $pesoGrupo = $detalles->sum('pesoG');
                    $importeGrupo = $detalles->sum(fn($d) => $d->cantidad * $d->precioU);
                    $totalGralCant += $cantGrupo;
                    $totalGralPeso += $pesoGrupo;
                    $totalGralImp += $importeGrupo;
                    $unidad = $detalles->first()->material->unidad->unidad ?? 'PZA';
                @endphp
                <tr class="{{ $filaNum % 2 != 0 ? 'gris' : '' }}">
                    <td class="centro">{{ number_format($cantGrupo, 2) }}</td>
                    <td class="centro">{{ $unidad }}</td>
                    <td><span class="negrita">{{ $nombreGrupo }}</span></td>
                    <td class="derecha">{{ number_format($pesoGrupo, 3) }}</td>
                    <td class="derecha negrita">{{ number_format($importeGrupo, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="3" class="derecha negrita" style="border-bottom: none;">TOTAL:</td>
                <td class="derecha negrita" style="background: #f8f9fa;">{{ number_format($totalGralPeso, 3) }}</td>
                <td class="derecha negrita" style="background: #f8f9fa;">{{ number_format($totalGralImp, 2) }}</td>
            </tr>
        </tfoot>
    </table>
    <table style="margin-top: 10px; border: none;">
        <tr>
            <td width="70%" style="border: none; vertical-align: top;">
                <div class="negrita" style="text-transform: uppercase; font-size: 8.5pt;">{{ $totalEnLetras }}</div>
            </td>
            <td width="30%" style="border: none;">
                <table style="border: none;">
                    <tr>
                        <td class="derecha negrita" style="border: none; font-size: 10pt; padding-right: 10px;">TOTAL USD:</td>
                        <td class="derecha negrita total" style="font-size: 12pt; padding: 10px;">
                            $ {{ number_format($totalGralImp, 2) }}
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    <div style="position: fixed; bottom: 0; width: 100%; font-size: 7pt;" class="derecha">
        Page <span class="pagina"></span>
    </div>
</body>
</html>