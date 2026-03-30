<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Orden de Compra #{{ $orden->id }}</title>
    <style>
        @page { margin: 0.5cm 1cm; }
        body { font-family: 'Helvetica', 'Arial', sans-serif; font-size: 11px; line-height: 1.2; color: #333; background-color: #fff; margin: 0; padding: 0; }
        .tabla-full { width: 100%; border-collapse: collapse; margin-bottom: 8px; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .text-bold { font-weight: bold; }
        .bg-oscuro { background-color: #34495e; color: white; padding: 4px 8px; font-size: 11px; text-transform: uppercase; letter-spacing: 1px; }
        .tabla-info td { padding: 3px 5px; border: 1px solid #ddd; }
        .etiqueta { background-color: #f2f2f2; color: #444; font-weight: bold; width: 18%; font-size: 9px; text-transform: uppercase; }
        .valor { width: 32%; }
        .tabla-materiales th { background-color: #f8f9fa; border: 1px solid #444; padding: 5px; font-size: 10px; text-transform: uppercase; }
        .tabla-materiales td { border: 1px solid #ccc; padding: 4px; vertical-align: middle; }
        .zebra tr:nth-child(even) { background-color: #fafafa; }
        .col-pie { vertical-align: top; padding-top: 10px; width: 33%; }
        .titulo-pie { display: block; color: #001859; font-weight: bold; border-bottom: 1px solid #001859; margin-bottom: 3px; font-size: 9px; text-transform: uppercase; }
        .datos-texto { font-size: 9px; color: #333; line-height: 1.3; }
        .tabla-totales td { padding: 3px; border: 1px solid #ddd; }
        .total-fila { background-color: #f2f2f2; font-size: 13px; }
        .contenedor-totales { position: relative; }
        .marca-agua {
            position: fixed;
            top: 20%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-15deg);
            font-size: 3cm;
            line-height: 7cm;
            color: rgba(231, 76, 60, 0.15);
            font-weight: bold;
            text-align: center;
            width: 100%;
            z-index: 1000;
            pointer-events: none;
        }
    </style>
</head>
<body>
    @if($orden->estatus == 'cancelado')
        <div class="marca-agua">
            CANCELADO
        </div>
    @endif    
    <table class="tabla-full">
        <tr>
            <td style="width: 20%; vertical-align: middle;">
                <img src="{{ public_path('img/' . $negocio->logo) }}" style="max-width: 90px; height: auto;">
            </td>
            <td class="text-right" style="width: 80%;">
                <h1 style="margin: 0; font-size: 22px; color: #2c3e50;">ORDEN DE COMPRA</h1>
                <span style="font-size: 14px; font-weight: bold;">Folio: #{{ $orden->id }}</span><br>
                <span style="color: #666;">Fecha: {{ $orden->fechaHSol }}</span>
            </td>
        </tr>
    </table>
    <div class="bg-oscuro text-bold">Datos Generales</div>
    <table class="tabla-full tabla-info">
        <tr>
            <td class="etiqueta text-right">Proveedor</td>
            <td class="valor text-bold">{{ $orden->Proveedor->empresa ?? '' }}</td>
            <td class="etiqueta text-right">Estatus</td>
            <td class="valor text-bold">{{ strtoupper($orden->estatus) }}</td>
        </tr>
        <tr>
            <td class="etiqueta text-right">Obra</td>
            <td class="valor">{{ $orden->obra->obra ?? '' }}</td>
            <td class="etiqueta text-right">Solicitó</td>
            <td class="valor">{{ $orden->Solicito->name ?? '' }}</td>
        </tr>
        <tr>
            <td class="etiqueta text-right">Concepto</td>
            <td class="valor">{{ $orden->concepto }}</td>
            <td class="etiqueta text-right">Teléfono</td>
            <td class="valor">{{ $orden->Solicito->telefono ?? '' }}</td>
        </tr>
        <tr>
            <td class="etiqueta text-right">Cond. Pago</td>
            <td class="valor">{{ $condsPago[$orden->IdCondPago] ?? '' }}</td>
            <td class="etiqueta text-right">Email</td>
            <td class="valor text-lowercase">{{ $orden->Solicito->email ?? '' }}</td>
        </tr>
        <tr>
            <td class="etiqueta text-right">Cond. Flete</td>
            <td class="valor">{{ $condsFlete[$orden->IdCondFlete] ?? '' }}</td>
            <td colspan="2"></td>
        </tr>
    </table>
    <div class="bg-oscuro text-bold">Detalle de Materiales</div>
    <table class="tabla-full tabla-materiales zebra">
        <thead>
            <tr class="text-center">
                <th style="width: 15%;">Referencia</th>
                <th style="width: 10%;">Unidad</th>
                <th>Descripción</th>
                <th style="width: 10%;">Cant.</th>
                <th style="width: 15%;">Costo U.</th>
                <th style="width: 15%;">Importe</th>
            </tr>
        </thead>
        <tbody>
            @foreach($orden->ocomprasdets as $det)
                <tr>
                    <td class="text-center" style="font-family: 'Helvetica', sans-serif; font-weight: 900; letter-spacing: -0.5px; font-size: 10px; color: #000;">
                        {{ $det->materialscosto->referencia ?? '' }}
                    </td>
                    <td class="text-center">{{ $det->materialscosto->unit ?? 'pz' }}</td>
                    <td>{{ $det->materialscosto->material->material ?? 'Sin nombre' }}</td>
                    <td class="text-center">{{ number_format($det->cantidad, 2) }}</td>
                    <td class="text-right">{{ number_format($det->costoU, 2) }}</td>
                    <td class="text-right text-bold">{{ number_format($det->cantidad * $det->costoU, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <table class="tabla-full">
        <tr>
            <td class="col-pie">
                <span class="titulo-pie">Facturación</span>
                <div class="datos-texto">
                    <strong>{{ $datosFac[1]['razonSocial'] }}</strong><br>
                    RFC: {{ $datosFac[1]['RFC'] }}<br>
                    {{ $datosFac[1]['direccion'] }}<br>
                    {{ $datosFac[1]['ciudad'] }}<br>
                    {{ $datosFac[1]['email'] }}
                </div>
                <div class="mt-2">
                    <span class="titulo-pie">Pagos</span>
                    <div class="datos-texto">
                        {{ $cajero[1]['cajero'] }}<br>
                        Tel: {{ $cajero[1]['telefono'] }}<br>
                        {{ $cajero[1]['email'] }}
                    </div>
                </div>
            </td>
            <td class="col-pie">
                <span class="titulo-pie">Proveedor</span>
                <div class="datos-texto">
                    <strong>{{ $proveedorInfo->razonSocial ?? '' }}</strong><br>
                    RFC: {{ $proveedorInfo->rfc ?? '' }}<br>
                    Tel: {{ $proveedorInfo->telefono ?? '' }}<br>
                    {{ $proveedorInfo->email ?? '' }}
                </div>
                @if($cuentaProv)
                <div class="mt-2">
                    <span class="titulo-pie">Depósito Bancario</span>
                    <div class="datos-texto">
                        Banco: {{ $cuentaProv->banco }}<br>
                        Cuenta: {{ $cuentaProv->cuenta }}<br>
                        CLABE: {{ $cuentaProv->cuentaClabe }}
                    </div>
                </div>
                @endif
            </td>
            <td class="col-pie" style="width: 34%;">
                <table class="tabla-full tabla-totales">
                    {{-- @if($orden->estatus == 'edicion')
                    <tr>
                        <td colspan="2" style="border: 1.5px dashed #e74c3c; background-color: #fff5f4; padding: 8px; text-align: center;">
                            <div style="color: #e74c3c; font-weight: bold; font-size: 13px; text-transform: uppercase;">
                                No Autorizado !!!
                            </div>
                        </td>
                    </tr>
                    @endif --}}
                    <tr>
                        <td class="text-right text-bold" style="background-color: #f8f9fa;">Subtotal</td>
                        <td class="text-right" style="width: 45%;">{{ number_format($orden->subtotal, 2) }}</td>
                    </tr>
                    @if($orden->porDescuento > 0)
                    <tr>
                        <td class="text-right text-bold">Desc. ({{ $orden->porDescuento }}%)</td>
                        <td class="text-right">-{{ number_format($orden->monto_descuento, 2) }}</td>
                    </tr>
                    @endif
                    <tr>
                        <td class="text-right text-bold">IVA ({{ \App\Models\Util::getArrayJS('datosFacturacion')[1]['factorIva'] * 100 }}%)</td>
                        <td class="text-right">{{ number_format($orden->monto_iva, 2) }}</td>
                    </tr>
                    <tr @if($orden->estatus == 'edicion') style="background-color: #bd6e6c; color: white;" @else class="total-fila" @endif>
                        <td class="text-right text-bold">TOTAL</td>
                        <td class="text-right text-bold">${{ number_format($orden->total, 2) }}</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>