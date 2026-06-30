<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="{{ public_path('css/reportes.css') }}">
    <style>
        .contenedor-principal { width: 100%; clear: both; }
        .columna-izquierda { width: 52%; float: left; }
        .columna-derecha { width: 46%; float: right; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 5px; }
        th { background: #001060; color: #ffffff; padding: 3px; font-size: 7.8pt; }
        td { padding: 3px; vertical-align: middle; font-size: 8.2pt; }
        .cuadro-borde td, table.cuadro-borde { border: 0.5pt solid #ccc; }
        .linea-abajo td { border-bottom: 0.1pt solid #e0e0e0; }
        .columna-derecha .titulo-seccion:nth-of-type(2) { margin-top: 35px; }
        .talon-bloque { width: 100%; border-collapse: collapse; margin-bottom: 6px; box-sizing: border-box; }
        .talon-bloque td { border: 0.5pt solid #000000; padding: 2px 4px; font-size: 7.8pt; height: 12px; }
        .talon-letra-caja { background-color: #000000; color: #ffffff; font-size: 13pt; font-weight: bold; text-align: center; width: 22px; }
        .talon-titulo { font-weight: bold; width: 45px; }
    </style>
</head>
<body>
    @foreach($folio->bandejas as $bandeja)
        <div class="salto-pagina">
            <span class="negrita">ORDEN DE PRODUCCIÓN</span>
            <div class="contenedor-principal">
                <div class="columna-izquierda">
<div class="grupo">I. Identificación</div>
                    <table class="cuadro-borde">
                        <tr>
                            <td colspan="6">
                                <table style="width: 100%; border-collapse: collapse; margin: 0;">
                                    <tr>
                                        <td style="width: 80%; border: none; padding: 5px 0;" class="centro">
                                            {{-- <img style="display: inline-block; vertical-align: middle;" src="data:image/png;base64,{{ DNS2D::getBarcodePNG((string) $folio->periodo.'-'.$folio->consecutivoMensual.'-'.$bandeja->numeroBandeja, 'QRCODE', 3, 3) }}"> --}}
                                            {{-- <img style="display: inline-block; vertical-align: middle;" src="data:image/png;base64,{{ DNS2D::getBarcodePNG((string) $bandeja->id, 'QRCODE', 3, 3) }}"> --}}
                                            <img style="display: inline-block; vertical-align: middle;" src="data:image/png;base64,{{ DNS1D::getBarcodePNG((string) $bandeja->id, 'EAN13', 2.5, 45, [0,0,0], false) }}">
                                        </td>
                                        <td style="width: 20%; border: none;" class="centro">
                                            @if(!empty($folio->Estilo?->foto))
                                                <img src="{{ public_path('storage/estilos/' . $folio->Estilo->foto) }}"
                                                    style="max-width: 100%; max-height: 60px;">
                                            @endif
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 12%;" class="gris negrita">Producto</td>
                            <td style="width: 55%;" colspan="3">{{ $folio->productoFinal ?? '1R/36D/1RF 14KP' }}</td>
                            <td style="width: 13%;" class="gris negrita">JobStyle</td>
                            <td style="width: 20%;">{{ $folio->jobStyle ?? '91970' }}</td>
                        </tr>
                        <tr>
                            <td class="gris negrita">Folio</td>
                            <td class="negrita">{{ $folio->periodo.'-'.$folio->consecutivoMensual.'-'.$bandeja->numeroBandeja }}</td>
                            <td class="gris negrita">Bandeja</td>
                            <td class="negrita">{{ $bandeja->numeroBandeja }} de {{ $folio->totalBandejas }}</td>
                            <td class="gris negrita">Lote</td>
                            <td>{{ $folio->lote?->lote }}</td>
                        </tr>
                        <tr>
                            <td class="gris negrita">Pzas</td>
                            <td class="negrita">{{ $bandeja->cantidad }}</td>
                            <td class="gris negrita">Pzas Folio</td>
                            <td>{{ $folio->cantidad }}</td>
                            <td class="gris negrita">Pzas Lote</td>
                            <td>{{ $folio->lote?->adicionales['piezasLote'] ?? '' }}</td>
                        </tr>
                        <tr>
                            <td class="gris negrita">Vence</td>
                            <td>{{ \Carbon\Carbon::parse($folio->fechaVen)->format('d/M/Y') }}</td>
                            <td class="gris negrita">Cliente</td>
                            <td colspan="3">{{ $folio->lote?->orden?->cliente?->cliente ?? '1388 85B' }}</td>
                        </tr>
                        @if(($folio->alertas['penalty'] ?? false) || ($folio->alertas['rush'] ?? false) || !empty($folio->alertas['alertaGeneral'] ?? ''))
                            <tr>
                                <td class="gris negrita">Alertas</td>
                                <td colspan="5">
                                    <table style="width: 100%; border-collapse: collapse; margin: 0;">
                                        <tr>
                                            <td style="border: none; padding: 0;">
                                                @if($folio->alertas['rush'] ?? false)
                                                    <span style="background-color: #dc3545; color: #ffffff; padding: 1px 4px; font-size: 7.5pt; font-weight: bold; border-radius: 2px; margin-right: 5px;">RUSH</span>
                                                @endif
                                                @if($folio->alertas['penalty'] ?? false)
                                                    <span style="background-color: #ffc107; color: #000000; padding: 1px 4px; font-size: 7.5pt; font-weight: bold; border-radius: 2px; margin-right: 5px;">PENALTY</span>
                                                @endif
                                                @if(!empty($folio->alertas['alertaGeneral'] ?? ''))
                                                    <span style="font-size: 8pt; color: #b00000; font-weight: bold;">{{ $folio->alertas['alertaGeneral'] }}</span>
                                                @endif
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        @endif
                    </table>
                    <div class="grupo">II. Observaciones de Manufactura</div>
                    <div>
                        <em>{{ $folio->adicionales['observaciones'] ?? '' }}</em>
                    </div>
                    <div class="grupo">III. Composición</div>
                    <table class="linea-abajo">
                        <thead>
                            <tr>
                                <th class="centro">Pza x U</th>
                                <th class="centro">Tot. Pzas</th>
                                <th>Material</th>
                                <th class="derecha">Peso Asignado</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($materialesAgrupados as $grupo)
                                @foreach($grupo as $mat)
                                    <tr class="{{ $loop->parent->iteration % 2 == 0 ? 'gris' : '' }}">
                                        <td class="centro">{{ number_format($mat->cantidad / max($folio->cantidad, 1), 2) }}</td>
                                        <td class="centro negrita">{{ number_format(($mat->cantidad / max($folio->cantidad, 1)) * $bandeja->cantidad, 0) }}</td>
                                        <td>{{ $mat->material?->material ?? '' }} {{ $mat->facimportsdet->propsTot ?? '' ?? '' }} </td>
                                        <td class="derecha">{{ number_format(($mat->pesoG / max($folio->cantidad, 1)) * $bandeja->cantidad, 4) }} g</td>
                                    </tr>
                                @endforeach
                            @endforeach
                        </tbody>
                    </table>
                    <div class="grupo">IV. Procesos</div>
                    <table class="cuadro-borde">
                        <thead>
                            <tr>
                                <th>Procesos</th>
                                <th class="centro">Peso Ent.</th>
                                <th class="centro">Fecha</th>
                                <th>Nombre / #</th>
                                <th class="centro">Peso Sal.</th>
                                <th class="centro">Peso Lim.</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($procesosEstandar as $proc)
                                <tr class="{{ $loop->iteration % 2 == 0 ? 'gris' : '' }}">
                                    <td class="negrita">{{ $proc->proceso }}</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="columna-derecha">
                    <div class="grupo">V. Pesos</div>
                        <table class="cuadro-borde">
                            <tbody>
                                <tr>
                                    <td style="width: 40%;" class="gris negrita">
                                        PESO ORO
                                    </td>

                                    <td style="width: 60%;" class="centro negrita">
                                    </td>
                                </tr>

                                <tr>
                                    <td class="gris negrita">PESO PIEDRAS</td>
                                    <td class="centro"></td>
                                </tr>

                                <tr>
                                    <td class="gris negrita">PESO DIAM.</td>
                                    <td class="centro"></td>
                                </tr>

                                <tr>
                                    <td class="gris negrita">PESO MISC.</td>
                                    <td class="centro"></td>
                                </tr>

                                <tr>
                                    <td class="gris negrita">PIED/DIAM/MIS</td>
                                    <td class="centro negrita"></td>
                                </tr>

                                <tr>
                                    <td class="gris negrita">PESO FINAL</td>
                                    <td class="centro"></td>
                                </tr>

                                <tr>
                                    <td class="gris negrita">PÉRDIDA NETA</td>
                                    <td></td>
                                </tr>

                                <tr>
                                    <td class="gris negrita">% PÉRDIDA</td>
                                    <td></td>
                                </tr>
                            </tbody>
                        </table>
                        <table style="width: 100%; margin-bottom: 10px; border-collapse: collapse;">
                            <tr>
                                <td style="width: 48%; vertical-align: top; border: none; padding: 0 8px 0 0;">

                                    <table style="width: 100%; border-collapse: collapse; margin-bottom: 8px;">
                                        <tr>
                                            <td class="centro negrita" style="width: 24px; border: 0.5pt solid #000; border-bottom: none;">
                                                A
                                            </td>

                                            <td style="border-bottom: 0.5pt solid #000;"></td>
                                        </tr>
                                    </table>

                                    <table style="width: 100%; border-collapse: collapse; margin-bottom: 14px;">
                                        <tr>
                                            <td style="height: 14px; border-bottom: 0.5pt solid #000;"></td>
                                        </tr>

                                        <tr>
                                            <td style="height: 14px; border-bottom: 0.5pt solid #000;"></td>
                                        </tr>
                                    </table>

                                    <table style="width: 100%; border-collapse: collapse; margin-bottom: 8px;">
                                        <tr>
                                            <td class="centro negrita" style="width: 24px; border: 0.5pt solid #000; border-bottom: none;">
                                                B
                                            </td>

                                            <td style="border-bottom: 0.5pt solid #000;"></td>
                                        </tr>
                                    </table>

                                    <table style="width: 100%; border-collapse: collapse;">
                                        <tr>
                                            <td style="height: 14px; border-bottom: 0.5pt solid #000;"></td>
                                        </tr>

                                        <tr>
                                            <td style="height: 14px; border-bottom: 0.5pt solid #000;"></td>
                                        </tr>
                                    </table>

                                </td>

                                <td style="width: 48%; vertical-align: top; border: none; padding: 0 0 0 8px;">

                                    <table style="width: 100%; border-collapse: collapse; margin-bottom: 8px;">
                                        <tr>
                                            <td class="centro negrita" style="width: 24px; border: 0.5pt solid #000; border-bottom: none;">
                                                C
                                            </td>

                                            <td style="border-bottom: 0.5pt solid #000;"></td>
                                        </tr>
                                    </table>

                                    <table style="width: 100%; border-collapse: collapse;">
                                        <tr>
                                            <td style="height: 14px; border-bottom: 0.5pt solid #000;"></td>
                                        </tr>

                                        <tr>
                                            <td style="height: 14px; border-bottom: 0.5pt solid #000;"></td>
                                        </tr>
                                    </table>

                                </td>
                            </tr>
                        </table>
                    <div class="grupo">VI. Reparaciones</div>
                    @foreach(['A', 'B', 'C'] as $letra)
                        <table class="talon-bloque">
                            <tr>
                                <td rowspan="2" class="talon-letra-caja">{{ $letra }}</td>
                                <td class="talon-titulo">Bandeja</td>
                                <td class="negrita">{{ $folio->periodo.'-'.$folio->consecutivoMensual.'-'.$bandeja->numeroBandeja }}</td>
                                <td class="negrita">{{ $folio->abreviatura ?? '-' }}</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td class="negrita">Proceso</td>
                                <td colspan="2" class="negrita">Reparación</td>
                                <td class="negrita">Hora; Fecha</td>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                                <td colspan="2"></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                                <td colspan="2"></td>
                                <td></td>
                            </tr>
                        </table>
                    @endforeach
                </div>
            </div>
        </div>
    @endforeach
</body>
</html>


