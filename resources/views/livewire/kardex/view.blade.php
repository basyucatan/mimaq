<div class="card shadow-sm border-0">
    <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Kardex General de Movimientos</h5>
        <div class="d-flex gap-2">
            <select wire:model.live="filtroTipoDoc" class="form-select form-select-sm w-auto">
                <option value="">Todos los Movimientos</option>
                <option value="import">Importaciones</option>
                <option value="folioSal">Salidas a Producción</option>
                <option value="export">Exportaciones</option>
            </select>
        </div>
    </div>
    <div class="card-body">
        <input type="text" wire:model.live="buscar" class="form-control mb-3" placeholder="Filtrar por Material, Estilo, EntradaMex, Forma o Medida...">
        <div class="table-responsive">
            <table class="table table-hover align-middle border">
                <thead class="table-light text-secondary">
                    <tr>
                        <th>Fecha</th>
                        <th>Concepto</th>
                        <th>Referencia / EntradaMex</th>
                        <th>Material / Estilo</th>
                        <th class="text-end">Cantidad</th>
                        <th class="text-end">Peso (g)</th>
                        <th>Estatus</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($this->movimientos as $mov)
                    <tr>
                        <td class="small">{{ $mov->created_at }}</td>
                        <td>
                            <span class="badge @if($mov->tipoDoc == 'import') bg-success @elseif($mov->tipoDoc == 'export') bg-info @else bg-warning text-dark @endif">
                                {{ strtoupper($mov->tipoDoc) }}
                            </span>
                        </td>
                        <td>
                            <span class="fw-bold text-primary">{{ $mov->Referencia->IdEntradaMex }}</span><br>
                            <small class="text-muted">Doc ID: #{{ $mov->IdDoc }}</small>
                        </td>
                        <td>
                            <div class="d-flex flex-column">
                                <span><strong>Mat:</strong> {{ $mov->Referencia->material->material }}</span>
                                <span class="small text-muted"><strong>Est:</strong> {{ $mov->Referencia->Estilo->estilo ?? $mov->Referencia->estiloY }}</span>
                            </div>
                        </td>
                        <td class="text-end fw-bold">{{ number_format($mov->cantidad, 0) }}</td>
                        <td class="text-end text-dark fw-bold">{{ number_format($mov->pesoG, 4) }}</td>
                        <td>
                            <span class="badge @if($mov->estatus == 'operado') bg-light text-success border border-success @else bg-light text-muted border @endif">
                                {{ ucfirst($mov->estatus) }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-3">
            {{ $this->movimientos->links() }}
        </div>
    </div>
</div>