<div class="table-responsive">
    <table class="table tabBase ch">
        <thead>
            <tr>
                <th width="70"class="text-center">Cant.</th>
                <th>Descripción</th>
                <th class="text-end text-muted d-none d-md-block">P. Orig</th>
                <th width="70" class="text-end">+Iva</th>
                <th width="70" class="text-end">Neto</th>
                <th width="80" class="text-end">Importe</th>
                <th width="20"></th>
            </tr>
        </thead>
        <tbody>
            @foreach($detalles as $idx => $det)
            <tr class="align-middle">
                <td><input type="number" step="1"  onfocus="this.select()" class="inpSolo" wire:model.blur="detalles.{{$idx}}.cantidad"></td>
                <td>
                    <div class="d-flex gap-2">
                        <span class="fw-bold small">{{ $det['nombre'] }}</span>
                    </div>
                </td>
                <td class="text-end text-muted small d-none d-md-block">{{ $det['simbolo'] }}{{ number_format($det['precioOrig'], 2) }}</td>
                <td><input type="number" wire:model.blur="detalles.{{$idx}}.costoU" onfocus="this.select()" class="inpSolo text-end p-1"></td>
                <td><input type="number" wire:model.blur="detalles.{{$idx}}.costoN" onfocus="this.select()" class="inpSolo text-end p-1"></td>
                <td class="text-end fw-bold text-dark">
                    ${{ number_format((float)$det['cantidad'] * (float)$det['costoN'], 2) }}
                </td>
                <td class="text-center">
                    <button type="button" wire:click="removeDetalle({{$idx}})" class="bot botRojo" onclick="confirm('¿Estás seguro?') || event.stopImmediatePropagation()">
                        <i class="bi bi-trash"></i>
                    </button>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @error('detalles') <span class="text-danger">{{ $message }}</span> @enderror
</div>