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
    <table width="100%" style="font-family: sans-serif;">
        <tr>
            <td width="30%" rowspan="2" valign="middle">
                <img src="{{ public_path('img/logo.png') }}" width="40">
                <span>EMA INC.</span>
            </td>
            <td width="20%" valign="top">
                <strong>De</strong> USA<br>
                <strong>Para</strong> MEX
            </td>
            <td width="25%" valign="top">
                <strong>GUÍA:</strong> {{$factura->adicionales['guiaA'] ?? ''}}<br>
                <strong>FECHA:</strong> {{ Util::formatFecha($factura->fecha,'DD/MMM/AA') }}
            </td>
            <td width="25%" valign="top">
                <strong>FACTURA:</strong> {{ $factura->factura }}<br>
                <strong>PÁGINA:</strong> <span class="pagina"></span>
            </td>
        </tr>
        <tr>
            <td colspan="3" valign="top">
                <strong>LISTA DE EMPAQUE</strong>
            </td>
        </tr>
    </table>
</div>

<table>
    <thead>
        <tr>
            <th width="30%">Material / Detalles</th>
            <th width="10%">Estilo</th>
            <th width="10%">Lote</th>
            <th width="12%">Fracción</th>
            <th width="10%">Cant.</th>
            <th width="10%">Peso (gr)</th>
            <th width="10%">Precio/U</th>
            <th width="8%">Total</th>
        </tr>
    </thead>
    <tbody>
        @foreach($itemsAgrupados as $nombreGrupo => $detalles)
            @php
                $esMultiple = $detalles->count() > 1;
                $subCant = $detalles->sum('cantidad');
                $subPeso = $detalles->sum('pesoG');
                $subImp = $detalles->sum(fn($item) => $item->cantidad * $item->precioU);
            @endphp
            @if($esMultiple)
                <tr>
                    <td colspan="4" class="grupo">{!! $nombreGrupo !!}</td>
                    <td class="derecha negrita">{{ number_format($subCant, 2) }}</td>
                    <td class="derecha negrita">{{ number_format($subPeso, 2) }}</td>
                    <td></td>
                    <td class="derecha negrita">{{ number_format($subImp, 2) }}</td>
                </tr>
            @endif
            @foreach($detalles as $item)
                @php
                    $imp = $item->cantidad * $item->precioU;
                    $totalGralCant += $item->cantidad;
                    $totalGralPeso += $item->pesoG;
                    $totalGralImp += $imp;
                    $filaNum++;
                @endphp
                <tr class="{{ $filaNum % 2 != 0 ? 'gris' : '' }}">
                    <td>
                        @if($esMultiple)
                            <span style="margin-left:10px;">•</span>
                        @else
                            {!! $item->material->material !!} {!! $item->propiedades !!}
                        @endif
                    </td>
                    <td class="centro">{{ $item->Estilo->estilo ?? $item->estiloY ?? '-' }}</td>
                    <td class="centro">{{ data_get($item->adicionales, 'lote', '-') }}</td>
                    <td class="centro">{{ $item->material->clase->arancel->arancel ?? '-' }}</td>
                    <td class="derecha">{{ number_format($item->cantidad, 2) }}</td>
                    <td class="derecha">{{ number_format($item->pesoG, 3) }}</td>
                    <td class="derecha">{{ number_format($item->precioU, 2) }}</td>
                    <td class="derecha negrita">{{ number_format($imp, 2) }}</td>
                </tr>
            @endforeach
        @endforeach
    </tbody>
</table>

<table>
    <tr>
        <td width="72%" class="derecha negrita">TOTAL GENERAL:</td>
        <td width="10%" class="derecha negrita">{{ number_format($totalGralPeso, 3) }}</td>
        <td width="18%" class="derecha negrita total" style="background:#eee;">$ {{ number_format($totalGralImp, 2) }}</td>
    </tr>
</table>
</body>
</html>