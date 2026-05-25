<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="{{ public_path('css/reportes.css') }}">
</head>
<body>
<div class="cabecera">
    <table width="100%" style="font-family: sans-serif;">
        <tr>
            <td width="30%" rowspan="2" valign="middle" style="border: none;">
                <img src="{{ public_path('img/logo.png') }}" width="40">
                <span class="negrita">EMA INC.</span>
            </td>
            <td width="20%" valign="top" style="border: none;">
                <span class="negrita">De:</span> MEX<br>
                <span class="negrita">Para:</span> USA
            </td>
            <td width="25%" valign="top" style="border: none;">
                <span class="negrita">GUÍA:</span> {{ $factura->adicionales['guiaA'] ?? '' }}<br>
                <span class="negrita">FECHA:</span> {{ \Carbon\Carbon::parse($factura->fecha)->format('m/d/Y') }}
            </td>
            <td width="25%" valign="top" style="border: none;">
                <span class="negrita">FACTURA:</span> {{ $factura->factura }}<br>
                <span class="negrita">PEDIMENTO:</span> {{ $factura->pedimento?->pedimento ?? 'N/A' }}
            </td>
        </tr>
        <tr>
            <td colspan="3" valign="top" style="border: none;">
                <span class="negrita">LISTA DE EMPAQUE (EXPORTACIÓN)</span>
            </td>
        </tr>
    </table>
</div>
<table>
    <thead>
        <tr>
            <th width="15%">Lote #</th>
            <th width="37%">Descripción</th>
            <th width="12%" class="derecha">Cant.</th>
            <th width="12%" class="derecha">Peso Gramos</th>
            <th width="12%" class="derecha">Unitario</th>
            <th width="12%" class="derecha">Importe</th>
        </tr>
    </thead>
    <tbody>
        @php $contadorFilas = 0; @endphp
        @foreach($estructuraFinal as $producto)
            <tr>
                <td colspan="2" class="grupo">{{ $producto['productoFinal'] }}</td>
                <td class="derecha grupo">{{ number_format($producto['cantidad'], 2) }}</td>
                <td class="derecha grupo">{{ number_format($producto['pesoG'], 2) }}</td>
                <td class="grupo"></td>
                <td class="derecha grupo">{{ number_format($producto['importe'], 2) }}</td>
            </tr>
            @foreach($producto['bloques'] as $bloque)
                @php $contadorFilas++; @endphp
                <tr class="{{ $contadorFilas % 2 != 0 ? 'gris' : '' }}">
                    <td class="negrita" style="padding-left: 10px;">{{ $bloque['lote'] }}</td>
                    <td class="negrita">Estilo &nbsp;&nbsp;&nbsp;&nbsp; {{ $bloque['estilo'] }}</td>
                    <td class="derecha negrita">{{ number_format($bloque['cantidad'], 2) }}</td>
                    <td class="derecha negrita">{{ number_format($bloque['pesoG'], 2) }}</td>
                    <td class="derecha"></td>
                    <td class="derecha negrita">{{ number_format($bloque['importe'], 2) }}</td>
                </tr>
                @foreach($bloque['materiales'] as $material)
                    @php $contadorFilas++; @endphp
                    <tr class="{{ $contadorFilas % 2 != 0 ? 'gris' : '' }}">
                        <td style="padding-left: 20px;">{{ $material['identificador'] }}</td>
                        <td class="linea-detalle">{{ $material['descripcion'] }}</td>
                        <td class="derecha">{{ number_format($material['cantidad'], 2) }}</td>
                        <td class="derecha">{{ number_format($material['pesoG'], 2) }}</td>
                        <td class="derecha">{{ number_format($material['precioU'], 2) }}</td>
                        <td class="derecha">{{ number_format($material['cantidad'] * $material['precioU'], 2) }}</td>
                    </tr>
                @endforeach
            @endforeach
        @endforeach
    </tbody>
</table>
<table style="margin-top: 15px;">
    <tr>
        <td width="52%" class="derecha negrita" style="border: none;">TOTALES:</td>
        <td width="12%" class="derecha negrita total">{{ number_format($totalesGlobales['cantidad'], 2) }}</td>
        <td width="12%" class="derecha negrita total">{{ number_format($totalesGlobales['pesoG'], 2) }}</td>
        <td width="12%" style="border: none;"></td>
        <td width="12%" class="derecha negrita total">$ {{ number_format($totalesGlobales['importe'], 2) }}</td>
    </tr>
</table>
</body>
</html>