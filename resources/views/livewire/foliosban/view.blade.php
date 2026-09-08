@section('title', __('folios bandejas'))
<div class="container-fluid p-0">
    <div class="row g-0 justify-content-center">
        <div class="col-12">
            <div class="cardPrin">
                <div class="cardPrin-header d-flex justify-content-between align-items-center" style="cursor: move;">
                    <span>Bandejas del folio</span>
                    <div class="d-flex align-items-center gap-2">
                        @if($foliosban->first()?->folio?->estatus == 'abierto')
                            @php
                                $folioObj = $foliosban->first()?->folio;
                                $esIntegro = $folioObj?->adicionales['integro'] ?? true;
                            @endphp
                            @if(!$esIntegro)
                                <button wire:click="validarYCorregir()" class="bot botRojo botChico" >
                                    <i class="bi bi-exclamation-triangle-fill"></i>
                                    Corregir
                                </button>
                            @endif
                            <button class="bot botVerde botChico" wire:click="abrirModalCrear"><i class="bi bi-file-earmark-plus"></i></button>
                            <button wire:click="abrirEdicionLote()" class="bot botNaranja botChico" title="Editar bandejas">
                                <i class="bi bi-pencil-square"></i>
                            </button>
                        @endif
                        <button type="button" class="bot botAzul botChico" wire:click="imprimir" wire:loading.attr="disabled">
                            <span wire:loading.remove wire:target="imprimir">
                                <span class="text-success">🖨️</span> 
                                <small class="fw-bold">Imprimir</small>
                            </span>
                            <span wire:loading wire:target="imprimir">
                                <span class="spinner-border spinner-border-sm text-success" role="status"></span>
                                <small>Imprimiendo...</small>
                            </span>
                        </button>
                    </div>
                </div>
                <div class="cardPrin-body">
                    <div class="d-flex justify-content-end mb-2">
                        {{ $foliosban->links() }}
                    </div>
                    @include('livewire.foliosban.modals')
                    <div class="tablaCont">
                        <table class="table tabBase ch">
                            <thead>
                                <tr>
                                    <th>Bandeja</th>
                                    <th>Piezas</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($foliosban as $row)
                                    <tr>
                                        <td>{{ $row->codigoBandeja }}</td>
                                        <td>{{ $row->cantidad }}</td>
                                        <td width="60">
                                            @if($row->folio?->estatus == 'abierto')
                                                <div class="d-flex justify-content-center align-items-center">
                                                    <button wire:click="destroy({{ $row->id }})"
                                                        class="bot botChico botRojo" title="Eliminar"
                                                        onclick="confirm('⚠️ ADVERTENCIA: Al eliminar esta bandeja se alterará la cantidad total asignada al folio. ¿Deseas continuar?') || event.stopImmediatePropagation()">
                                                        <i class="bi-trash3-fill"></i>
                                                    </button>
                                                </div>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center text-muted">
                                            No hay bandejas registradas para este folio.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>