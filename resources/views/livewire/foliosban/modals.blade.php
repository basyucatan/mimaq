@if ($verModalFoliosban)
    <div class="modal-overlay">
        <div x-data="{}" x-init="dragModal($el)" class="modal-dialog modal-lg" wire:ignore.self>
            <div class="modal-content">
                <div class="cardPrin">
                    <div class="cardPrin-header" style="cursor: move;">
                        <span>Editar Cantidades de Bandejas (Folio #{{ $IdFolio }})</span>
                    </div>
                    <div class="cardPrin-body" style="padding: 10px; max-height: 400px; overflow-y: auto;">
                        <form gy-2>
                            <div class="alert alert-warning py-2 px-3 mb-3 shadow-sm" style="font-size: 0.85rem; border-left: 4px solid #ffc107;">
                                <i class="bi bi-exclamation-triangle-fill me-1"></i>
                                Revisa la integridad de las cantidades.
                            </div>

                            <div class="row g-2">
                                @foreach($bandejasEdit as $index => $item)
                                    <div class="col-12 col-md-6">
                                        <div class="p-2 border rounded bg-light">
                                            <label class="etiBase fw-bold text-dark mb-1">
                                                Bandeja #{{ $item['numeroBandeja'] ?? ($index + 1) }}
                                            </label>
                                            <input type="number" 
                                                   wire:model="bandejasEdit.{{ $index }}.cantidad" 
                                                   class="inpBase @error('bandejasEdit.'.$index.'.cantidad') is-invalid @enderror" 
                                                   onfocus="this.select()"
                                                   min="1">
                                            @error('bandejasEdit.'.$index.'.cantidad')
                                                <span class="invalid-feedback small d-block">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </form>
                    </div>
                    <div class="cardPrin-footer mt-3 d-flex justify-content-end gap-2">
                        <button wire:click.prevent="cancel()" class="bot botNegro botChico">Cerrar</button>
                        <button wire:click.prevent="save()" class="bot botVerde botChico">
                            Guardar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif

@if ($verModalCrear)
    <div class="modal-overlay">
        <div x-data="{}" x-init="dragModal($el)" class="modal-dialog" wire:ignore.self>
            <div class="modal-content">
                <div class="cardPrin">
                    <div class="cardPrin-header" style="cursor: move;">
                        <span>Agregar Nueva Bandeja (Folio #{{ $IdFolio }})</span>
                    </div>
                    <div class="cardPrin-body" style="padding: 10px;">
                        <form gy-2>
                            <div class="mb-3">
                                <label class="etiBase">Cantidad de piezas</label>
                                <input type="number" 
                                       wire:model="nuevaCantidad" 
                                       class="inpBase @error('nuevaCantidad') is-invalid @enderror" 
                                       onfocus="this.select()" 
                                       min="1">
                                @error('nuevaCantidad')
                                    <span class="invalid-feedback small d-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </form>
                    </div>
                    <div class="cardPrin-footer mt-3 d-flex justify-content-end gap-2">
                        <button wire:click.prevent="cancel()" class="bot botNegro botChico">Cerrar</button>
                        <button wire:click.prevent="createBandeja()" class="bot botVerde botChico">
                            Agregar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif