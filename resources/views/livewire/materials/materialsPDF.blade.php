@php
    $totalMaterialesGral = 0;
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
            <td width="30%" rowspan="2" valign="middle">
                <img src="{{ public_path('img/logo.png') }}" width="45" class="logo">
                <span class="negrita" style="margin-left: 50px;">EMA INC.</span>
            </td>
            <td width="40%" valign="top" class="centro">
                <strong style="font-size: 14px;">CATÁLOGO DE MATERIALES</strong>
            </td>
            <td width="30%" valign="top" class="derecha">
                <strong>FECHA:</strong> {{ date('d/m/Y') }} &nbsp;&nbsp;
                <strong>PÁGINA:</strong> <span class="pagina"></span>
            </td>
        </tr>
    </table>
</div>

<table>
    <thead>
        <tr>
            <th width="30%">Material / Clase</th>
            <th width="25%">Nombre Inglés</th>
            <th width="15%">Arancel</th>
            <th width="10%">Abrev.</th>
            <th width="10%">Unidad</th>
            <th width="10%">Unidad P.</th>
        </tr>
    </thead>
    <tbody>
        @foreach($itemsAgrupados as $nombreTipo => $clasesGroup)
            <tr class="separadora">
                <td colspan="6" class="negrita">
                    TIPO: {{ mb_strtoupper($nombreTipo) }}
                </td>
            </tr>
            @foreach($clasesGroup as $nombreClase => $materiales)
                @php
                    $esMultiple = $materiales->count() > 1;
                @endphp
                @if($esMultiple)
                    <tr>
                        <td colspan="6" class="grupo">
                            CLASE: {{ $nombreClase }}
                        </td>
                    </tr>
                @endif
                @foreach($materiales as $item)
                    @php
                        $totalMaterialesGral++;
                        $filaNum++;
                    @endphp
                    <tr class="detalle {{ $filaNum % 2 != 0 ? 'gris' : '' }}">
                        <td>
                            @if($esMultiple)
                                <span style="margin-left:10px;">•</span> {{ $item->material }}
                            @else
                                {{ $item->material }} <span class="linea-detalle">({{ $nombreClase }})</span>
                            @endif
                        </td>
                        <td>{{ $item->materialI ?? '-' }}</td>
                        <td class="centro">{{ $item->clase?->arancel?->arancel ?? '-' }}</td>
                        <td class="centro">{{ $item->abreviatura ?? '-' }}</td>
                        <td class="centro">{{ $item->unidad?->unidad ?? '-' }}</td>
                        <td class="centro">{{ $item->unidadP?->unidad ?? '-' }}</td>
                    </tr>
                @endforeach
            @endforeach
        @endforeach
    </tbody>
</table>

<table class="totales">
    <tr>
        <td width="80%" class="derecha negrita">TOTAL DE MATERIALES:</td>
        <td width="20%" class="derecha negrita total">{{ $totalMaterialesGral }}</td>
    </tr>
</table>
</body>
</html>