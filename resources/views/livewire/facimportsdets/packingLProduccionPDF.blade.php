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
    <style>
        .tabla-pl { width: 100%; border-collapse: collapse; font-family: sans-serif; font-size: 11px; margin-bottom: 15px; }
        .tabla-pl th { background-color: #f2f2f2; padding: 5px; border: 1px solid #ddd; text-align: center; }
        .tabla-pl td { padding: 5px; border: 1px solid #ddd; }
        .encabezado-folio { background-color: #e6f2ff; font-weight: bold; padding: 6px; border: 1px solid #b3d7ff; }
        .derecha { text-align: right; }
        .centro { text-align: center; }
        .negrita { font-weight: bold; }
        .subtotal-row { background-color: #fafafa; font-style: italic; }
    </style>
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
                <strong>LISTA DE EMPAQUE (PACKING LIST)</strong>
            </td>
        </tr>
    </table>
</div>
@foreach($itemsAgrupados as $idFolio => $detalles)
    @php
        $primerItem = $detalles->first();
        $nombreEstilo = $primerItem->Estilo->estilo ?? $primerItem->estiloY ?? 'S/E';
        $numeroLote = $primerItem->folio?->lote?->lote ?? 'S/L';
        $numeroOrden = $primerItem->folio?->lote?->orden?->orden ?? 'S/O';
        $subCant = $detalles->sum('cantidad');
        $subPeso = $detalles->sum('pesoG');
        $subImp = $detalles->sum(fn($item) => $item->cantidad * $item->precioU);
    @endphp
    <div class="encabezado-folio">
        Estilo: {{ $nombreEstilo }} &nbsp;|&nbsp; Lote: {{ $numeroLote }} &nbsp;|&nbsp; Orden: {{ $numeroOrden }}
    </div>
    <table class="tabla-pl">
        <thead>
            <tr>
                <th width="40%">Descripción del Material</th>
                <th width="15%">Fracción</th>
                <th width="10%">Cant.</th>
                <th width="15%">Peso (gr)</th>
                <th width="10%">Precio/U</th>
                <th width="10%">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($detalles as $item)
                @php
                    $imp = $item->cantidad * $item->precioU;
                    $totalGralCant += $item->cantidad;
                    $totalGralPeso += $item->pesoG;
                    $totalGralImp += $imp;
                    $filaNum++;
                @endphp
                <tr style="background-color: {{ $filaNum % 2 != 0 ? '#ffffff' : '#f9f9f9' }};">
                    <td>{!! $item->material->material ?? 'N/A' !!} {!! $item->propiedades !!}</td>
                    <td class="centro">{{ $item->arancel ?? '-' }}</td>
                    <td class="derecha">{{ number_format($item->cantidad, 2) }}</td>
                    <td class="derecha">{{ number_format($item->pesoG, 3) }}</td>
                    <td class="derecha">{{ number_format($item->precioU, 2) }}</td>
                    <td class="derecha negrita">{{ number_format($imp, 2) }}</td>
                </tr>
            @endforeach
            <tr class="subtotal-row">
                <td colspan="2" class="derecha negrita">Subtotal Estilo:</td>
                <td class="derecha negrita">{{ number_format($subCant, 2) }}</td>
                <td class="derecha negrita">{{ number_format($subPeso, 3) }}</td>
                <td></td>
                <td class="derecha negrita">{{ number_format($subImp, 2) }}</td>
            </tr>
        </tbody>
    </table>
@endforeach
<table width="100%" style="font-family: sans-serif; font-size: 12px; margin-top: 20px; border-collapse: collapse;">
    <tr>
        <td width="60%" class="derecha negrita" style="padding: 8px;">TOTAL GENERAL:</td>
        <td width="20%" class="derecha negrita" style="padding: 8px; border: 1px solid #ddd;">{{ number_format($totalGralPeso, 3) }} gr</td>
        <td width="20%" class="derecha negrita" style="padding: 8px; background:#eee; border: 1px solid #ddd; font-size: 14px;">$ {{ number_format($totalGralImp, 2) }}</td>
    </tr>
</table>
</body>
</html>