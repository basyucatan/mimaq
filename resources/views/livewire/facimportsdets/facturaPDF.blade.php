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
                <div class="linea-detalle">CONSIGNADO A:</div>
                <strong style="font-size: 9pt;">EMA DE YUCATAN, S.A. DE C.V.</strong><br>
                CALLE 64 #366-A X 39 Y 41 COL. CENTRO<br>
                MERIDA, YUCATAN, MEXICO | RFC: EYU-030827-858
            </td>
            <td width="50%" style="border: none; padding-left: 10px;">
                <div class="linea-detalle">ENVIADO A:</div>
                <strong style="font-size: 9pt;">EMA DE YUCATAN, S.A. DE C.V.</strong><br>
                CALLE 64 #366-A X 39 Y 41 COL. CENTRO<br>
                MERIDA, YUCATAN, MEXICO | RFC: EYU-030827-858
            </td>
        </tr>
    </table>
    <table style="margin-bottom: 5px; border: none;">
        <tr>
            <td width="30%" style="border: none;">
                <span class="negrita">FACTURA:</span> {{ $factura->factura }}<br>
                <span class="negrita">FECHA:</span> {{ Util::formatFecha($factura->fecha,'MM/DD/YY') }}
            </td>
            <td width="40%" style="border: none;" class="centro"></td>
            <td width="30%" style="border: none;" class="derecha">
                <span class="negrita">Via:</span> {{ $factura->adicionales['viadE'] ?? '' }}<br>
                <span class="negrita">Guia:</span> {{ $factura->adicionales['guiaA'] ?? '' }}<br>
                <span class="negrita"># Paq:</span> {{ $factura->adicionales['nPaq'] ?? '' }}
            </td>
        </tr>
    </table>    
    <table>
        <thead>
            <tr>
                <th width="12%" class="centro">Cantidad</th>
                <th width="10%" class="centro">Unidad</th>
                <th width="48%">Descripción</th>
                <th width="15%" class="derecha">Peso (g)</th>
                <th width="15%" class="derecha">Importe</th>
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
                <td colspan="3" class="derecha negrita" style="border-bottom: none;">TOTALES:</td>
                <td class="derecha negrita" style="background: #f8f9fa;">{{ number_format($totalGralPeso, 3) }}</td>
                <td class="derecha negrita" style="background: #f8f9fa;">{{ number_format($totalGralImp, 2) }}</td>
            </tr>
        </tfoot>
    </table>
    <table style="margin-top: 10px; border: none;">
        <tr>
            <td width="70%" style="border: none; vertical-align: top;">
                <div class="linea-detalle">CANTIDAD EN LETRAS:</div>
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
        Página <span class="pagina"></span>
    </div>
</body>
</html>