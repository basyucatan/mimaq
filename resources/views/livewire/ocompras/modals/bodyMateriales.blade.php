<div class="cardPrin-body p-2">
    <div class="card mb-2 border-0 bg-light p-2">
        <div class="row g-2">
            <div class="col-2">
                <input type="number" step="1" wire:model="cantidadMat" 
                    onfocus="this.select()" class="inpSolo border-2 border-primary" placeholder="Cant.">
            </div>
            <div class="col-10 position-relative">
                <div class="input-group">
                    <input wire:model.live="keyWordMat" type="text" 
                        onfocus="this.select()" class="form-control inpBase" placeholder="🔍 Buscar material...">
                    <button type="button" wire:click="toggleNuevoMaterial" class="bot {{ $verNuevoMat ? 'botRojo' : 'botAzul' }}">✚</button>
                </div>
                @if (count($mats) > 0)
                <div class="position-absolute bg-white border shadow-lg w-100 mt-1 rounded-3" style="z-index: 2000; max-height: 250px; overflow-y: auto;">
                    @foreach ($mats as $m)
                    <a href="javascript:void(0)" wire:click="elegirMaterial({{ $m->id }})" class="d-block p-2 border-bottom text-decoration-none list-group-item-action small">
                        <div class="d-flex gap-2 align-items-center">
                            @if ($m->color)<span class="cuadroColor" style="background-color: {{ $m->color->colorRgba }}; width: 12px; height: 12px; border-radius: 2px; flex-shrink: 0; display: inline-block;"></span>@endif
                            <span class="fw-bold text-dark small text-truncate">{{ $m->material->material }} ({{ $m->unidad }})</span>
                            <span class="badge bg-secondary ms-auto" style="font-size: 0.55rem; flex-shrink: 0;">{{ $m->referencia }}</span>
                        </div>
                    </a>
                    @endforeach
                </div>
                @endif              
            </div>
            @if($verNuevoMat)
                @include('livewire.ocompras.modals.nuevoMaterial')
            @endif              
        </div>
    </div>
    <div class="overflow-auto px-1" style="max-height: 65vh;">
        <div class="d-none d-lg-block">
            <table class="table tabBase align-middle">
                <thead>
                    <tr class="small text-muted">
                        <th width="70">Cant.</th>
                        <th>Material</th>
                        <th width="100" class="text-end">+Iva</th>
                        <th width="100" class="text-end">Neto</th>
                        <th width="110" class="text-end">Importe</th>
                        <th width="40"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($detalles as $id => $det)
                    <tr>
                        <td>
                            @php $campoCant = ($oCompra?->estatus === 'ordenado') ? 'cantidadRec' : 'cantidad'; @endphp
                            <input type="number" step="1" class="inpSolo border-2 border-success w-100" 
                                 onfocus="this.select()" wire:model.blur="detalles.{{ $id }}.{{ $campoCant }}">
                        </td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                @if ($det['colorRgba'])<span class="cuadroColor border-black" style="background-color: {{ $det['colorRgba'] }}; width: 12px; height: 12px; border-radius: 2px; flex-shrink: 0; display: inline-block;"></span>@endif
                                <span class="fw-bold small text-truncate text-success" title="{{ $det['nombre'] }}">{{ $det['nombre'] }}</span>
                            </div>
                        </td>
                        <td><input type="number" wire:model.blur="detalles.{{ $id }}.costoU" 
                             onfocus="this.select()" class="inpSolo text-end border-2 border-black w-100 p-1">
                        </td>
                        <td><input type="number" wire:model.blur="detalles.{{ $id }}.costoN" 
                             onfocus="this.select()" class="inpSolo text-end border-2 border-black w-100 p-1">
                        </td>
                        <td class="text-end fw-bold small">${{ number_format((float) ($det['cantidad'] ?? 0) * (float) ($det['costoN'] ?? 0), 2) }}</td>
                        <td class="text-center">
                            <button type="button" wire:click="removeDetalle({{ $id }})" class="bot botRojo" onclick="confirm('¿Borrar?') || event.stopImmediatePropagation()"><i class="bi bi-trash"></i></button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="d-lg-none">
            @foreach ($detalles as $id => $det)
                <div class="py-2 px-1 border-bottom {{ $loop->even ? 'bg-light' : '' }}">
                    <div class="d-flex align-items-center gap-2 mb-1">
                        <div style="width: 55px; flex-shrink: 0;">
                            <input type="number" step="1" 
                                onfocus="this.select()" class="inpSolo border-2 border-success w-100 text-center" 
                                wire:model.blur="detalles.{{ $id }}.{{ $campoCant }}">
                        </div>
                        <div class="flex-grow-1 overflow-hidden">
                            <div class="d-flex align-items-center gap-2">
                                @if ($det['colorRgba'])
                                    <span class="cuadroColor shadow-sm  border-black" style="background-color: {{ $det['colorRgba'] }}; width: 12px !important; height: 12px !important; border-radius: 3px; flex-shrink: 0 !important; display: inline-block !important;"></span>
                                @endif
                                <span class="fw-bold text-truncate text-success" style="font-size: 0.75rem; white-space: nowrap;">
                                    {{ \Illuminate\Support\Str::limit($det['nombre'], 50, '...') }}
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex align-items-end gap-2">
                        <div class="flex-grow-1">
                            <small class="text-muted d-block text-center" style="font-size: 0.6rem;">+IVA</small>
                            <input type="number" wire:model.blur="detalles.{{ $id }}.costoU" 
                                onfocus="this.select()" class="inpSolo text-end border-2 border-black w-100 p-1">
                        </div>
                        <div class="flex-grow-1">
                            <small class="text-muted d-block text-center" style="font-size: 0.6rem;">NETO</small>
                            <input type="number" wire:model.blur="detalles.{{ $id }}.costoN" 
                                onfocus="this.select()" class="inpSolo text-end border-2 border-black w-100 p-1">
                        </div>
                        <div class="text-end" style="min-width: 85px; flex-shrink: 0;">
                            <small class="text-muted d-block text-center" style="font-size: 0.6rem;">TOTAL</small>
                            <span class="fw-bold d-block text-end" style="font-size: 1rem;">${{ number_format((float) ($det['cantidad'] ?? 0) * (float) ($det['costoN'] ?? 0), 2) }}</span>
                        </div>
                        <button type="button" wire:click="removeDetalle({{ $id }})" class="bot botRojo p-1 px-2 flex-shrink-0" onclick="confirm('¿Borrar?') || event.stopImmediatePropagation()"><i class="bi bi-trash"></i></button>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>