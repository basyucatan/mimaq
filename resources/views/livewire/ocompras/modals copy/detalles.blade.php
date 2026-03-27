<div class="table-responsive">
    @php
        $modoRecepcion = $oCompra?->estatus === 'recibido';
    @endphp    
    <table class="table tabBase ch">
        <thead>
            <tr>
                <th width="70"class="text-center">Cant.</th>
                <th>Descripción</th>
                <th width="70" class="text-end">+Iva</th>
                <th width="70" class="text-end">Neto</th>
                <th width="80" class="text-end">Importe</th>
                <th width="20"></th>
            </tr>
        </thead>
        <tbody>
            @foreach($detalles as $id => $det)
            <tr class="align-middle">
                <td>
                    @if($oCompra?->estatus === 'ordenado')
                        <input type="number" step="1" class="inpSolo" onfocus="this.select()" wire:model.blur="detalles.{{$id}}.cantidadRec">
                    @else
                        <input type="number" step="1" class="inpSolo" onfocus="this.select()" wire:model.blur="detalles.{{$id}}.cantidad">
                    @endif
                </td>
                <td colspan="5">                  
                    <div class="d-flex gap-2">
                        @if($det['colorRgba'])
                            <span class="cuadroColor" style="background-color: {{ $det['colorRgba'] }}; 
                                width: 12px; height: 12px; border-radius: 2px;"></span>
                        @endif                          
                        <span class="fw-bold small">{{ $det['nombre'] }}</span>
                    </div>
                </td>
            </tr>
            <tr class="align-middle">
                <td></td>
                <td></td>
                <td>
                    <input type="number" wire:model.blur="detalles.{{$id}}.costoU" onfocus="this.select()" class="inpSolo text-end p-1">
                </td>
                <td>
                    <input type="number" wire:model.blur="detalles.{{$id}}.costoN" onfocus="this.select()" class="inpSolo text-end p-1">
                </td>
                <td class="text-end fw-bold">
                    ${{ number_format((float)$det['cantidad'] * (float)$det['costoN'], 2) }}
                </td>
                <td class="text-center">
                    <button type="button" wire:click="removeDetalle({{$id}})" class="bot botRojo" onclick="confirm('¿Estás seguro?') || event.stopImmediatePropagation()">
                        <i class="bi bi-trash"></i>
                    </button>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @error('detalles') <span class="text-danger">{{ $message }}</span> @enderror
</div>