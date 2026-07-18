<div class="row g-3">
    <div class="col-12 col-lg-5">
        <div class="cardSec">
            <div class="cardSec-header">
                <span class="fw-bold">Configuración del Análisis</span>
            </div>
            <div class="cardSec-body">
                <div class="row g-2">
                    <div class="col-md-6 border-end">
                        <div class="mb-2">
                            <label class="etiBase">Tabla Padre (Principal)</label>
                            <select wire:model.live="modeloPadreSeleccionado" class="inpBase">
                                <option value="">Seleccione un modelo...</option>
                                @foreach($opcionesModelos as $modelo)
                                    <option value="{{ $modelo }}">{{ $modelo }}</option>
                                @endforeach
                            </select>
                            @error('modeloPadreSeleccionado') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>
                        <div class="mb-2">
                            <label class="etiBase">Tabla Hijo (Relacionado)</label>
                            <select wire:model.live="modeloHijoSeleccionado" class="inpBase">
                                <option value="">Seleccione un modelo...</option>
                                @foreach($opcionesModelos as $modelo)
                                    <option value="{{ $modelo }}">{{ $modelo }}</option>
                                @endforeach
                            </select>
                            @error('modeloHijoSeleccionado') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>
                        <div class="mb-2">
                            <label class="etiBase">Campo de Relación (FK en Hijo)</label>
                            <select wire:model.live="campoRelacionSeleccionado" class="inpBase" {{ empty($camposDisponiblesHijo) ? 'disabled' : '' }}>
                                <option value="">Seleccione un campo...</option>
                                @foreach($camposDisponiblesHijo as $campo)
                                    <option value="{{ $campo }}">{{ $campo }}</option>
                                @endforeach
                            </select>
                            @error('campoRelacionSeleccionado') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="etiBase text-primary">Representación Padre (Campos)</label>
                            <div class="border rounded p-2 bg-light" style="max-height: 105px; overflow-y: auto;">
                                @forelse($camposDisponiblesPadre as $campo)
                                    <div class="form-check py-0" wire:key="padre-campo-{{ $campo }}">
                                        <input class="form-check-input" type="checkbox" wire:model.live="camposPadreSeleccionados" value="{{ $campo }}" id="chkP-{{ $campo }}">
                                        <label class="form-check-label small text-dark" for="chkP-{{ $campo }}">
                                            {{ $campo }}
                                        </label>
                                    </div>
                                @empty
                                    <span class="text-muted small d-block py-2 text-center">Seleccione un modelo padre</span>
                                @endforelse
                            </div>
                        </div>
                        <div class="mb-1">
                            <label class="etiBase text-info">Representación Hijo (Campos)</label>
                            <div class="border rounded p-2 bg-light" style="max-height: 105px; overflow-y: auto;">
                                @forelse($camposDisponiblesHijo as $campo)
                                    <div class="form-check py-0" wire:key="hijo-campo-{{ $campo }}">
                                        <input class="form-check-input" type="checkbox" wire:model.live="camposHijoSeleccionados" value="{{ $campo }}" id="chkH-{{ $campo }}">
                                        <label class="form-check-label small text-dark" for="chkH-{{ $campo }}">
                                            {{ $campo }}
                                        </label>
                                    </div>
                                @empty
                                    <span class="text-muted small d-block py-2 text-center">Seleccione un modelo hijo</span>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
                <button wire:click="analizar" class="bot botVerde w-100 justify-content-center mt-3">
                    <i class="bi bi-gear-fill me-2"></i> Analizar Estructura
                </button>
            </div>
        </div>
        @if($analisisRealizado && $estadisticas)
            <div class="cardSec mt-3">
                <div class="cardSec-header bg-white">
                    <span class="fw-bold">Métricas del Análisis</span>
                </div>
                <div class="cardSec-body p-0">
                    <table class="table table-sm mb-0 table-striped">
                        <tbody>
                            <tr>
                                <td class="p-2 fw-bold text-muted">Total Reg. Padre:</td>
                                <td class="p-2 text-end fw-bold">{{ $estadisticas['totalPadre'] }}</td>
                            </tr>
                            <tr>
                                <td class="p-2 fw-bold text-muted">Reg. Referenciados:</td>
                                <td class="p-2 text-end text-success fw-bold">{{ $estadisticas['totalReferenciados'] }}</td>
                            </tr>
                            <tr>
                                <td class="p-2 fw-bold text-muted">Reg. Huérfanos:</td>
                                <td class="p-2 text-end text-danger fw-bold">{{ $estadisticas['totalHuerfanos'] }}</td>
                            </tr>
                            <tr>
                                <td class="p-2 fw-bold text-muted">Relaciones Válidas:</td>
                                <td class="p-2 text-end text-primary fw-bold">{{ $estadisticas['relacionesExistentes'] }}</td>
                            </tr>
                            <tr>
                                <td class="p-2 fw-bold text-muted">Relaciones Inexistentes:</td>
                                <td class="p-2 text-end text-warning fw-bold">{{ $estadisticas['relacionesInexistentes'] }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                @if($estadisticas['totalHuerfanos'] > 0)
                    <div class="cardSec-footer p-2">
                        <button wire:click="eliminarHuerfanos" class="bot botRojo w-100 justify-content-center" onclick="confirm('¿Estás seguro de que deseas eliminar permanentemente todos los registros huérfanos detectados?') || event.stopImmediatePropagation()">
                            <i class="bi bi-trash-fill me-2"></i> Eliminar Huérfanos
                        </button>
                    </div>
                @endif
            </div>
        @endif
    </div>
    <div class="col-12 col-lg-7">
        @if(session()->has('mensaje'))
            <div class="alert alert-success alert-dismissible fade show p-2 mb-3" role="alert">
                {{ session('mensaje') }}
            </div>
        @endif
        @if(session()->has('error'))
            <div class="alert alert-danger alert-dismissible fade show p-2 mb-3" role="alert">
                {{ session('error') }}
            </div>
        @endif
        @if($analisisRealizado && !empty($campoRelacionSeleccionado))
            <div class="cardSec">
                <div class="cardSec-header bg-white d-flex justify-content-between align-items-center flex-wrap gap-2 py-2">
                    <span class="fw-bold text-primary"><i class="bi bi-diagram-3-fill me-2"></i>Estructura Jerárquica e Integridad</span>
                    <div class="d-flex align-items-center gap-2">
                        <div class="position-relative" style="display:inline-block;">
                            <input wire:model.lazy="keyWord" class="inpSolo" wire:keydown.escape="$set('keyWord','')" onfocus="this.select()" placeholder="Buscar..." style="padding-right: 30px;">
                            @if($keyWord)
                                <span wire:click="$set('keyWord','')" class="badge bg-secondary p-1" style="position: absolute; right: 8px; top: 50%; transform: translateY(-50%); cursor: pointer; font-size: 0.7rem;">X</span>
                            @endif
                        </div>
                        <button wire:click="abrirCrearPadre" class="bot botVerde btn-sm">
                            <i class="bi bi-plus-circle-fill me-1"></i> Agregar Padre
                        </button>
                    </div>
                </div>
                <div class="cardSec-body">
                    <div class="accordion" id="acordeonPadres">
                        @forelse($arbolPadres as $indice => $padre)
                            @php
                                $hijosAsociados = $padre->hijosDirectosCargados ?? collect();
                                $totalHijos = count($hijosAsociados);
                            @endphp
                            <div class="accordion-item mb-2 border rounded shadow-sm" x-data="{ abierto: false }" wire:key="item-padre-{{ $padre->getKey() }}">
                                <h2 class="accordion-header d-flex align-items-center bg-light p-1">
                                    <button class="accordion-button flex-grow-1 border-0 bg-transparent text-dark py-2 px-3 fw-semibold" :class="{ 'collapsed': !abierto }" type="button" @click="abierto = !abierto">
                                        <i class="bi bi-folder-fill text-warning me-2"></i>
                                        ID: {{ $padre->getKey() }} - {{ $this->obtenerTextoRepresentativoPadre($padre) }}
                                        <span class="badge bg-secondary ms-2">{{ $totalHijos }} hijos</span>
                                    </button>
                                    <div class="pe-3 d-flex gap-1">
                                        <button wire:click="abrirCrearHijo({{ $padre->getKey() }})" class="btn btn-outline-success btn-xs" title="Agregar Hijo">
                                            <i class="bi bi-plus-lg"></i>
                                        </button>
                                        <button wire:click="abrirEditarPadre({{ $padre->getKey() }})" class="btn btn-outline-primary btn-xs" title="Editar Padre">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        <button wire:click="prepararEliminar({{ $padre->getKey() }}, 'padre')" class="btn btn-outline-danger btn-xs" title="Eliminar Padre">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </h2>
                                <div class="accordion-collapse collapse" :class="{ 'show': abierto }" wire:ignore.self>
                                    <div class="accordion-body bg-white p-2 border-top">
                                        @if($totalHijos > 0)
                                            <div class="table-responsive">
                                                <table class="table table-sm table-hover mb-0 align-middle">
                                                    <thead class="table-light">
                                                        <tr>
                                                            <th>ID Hijo</th>
                                                            <th>Representación</th>
                                                            <th>Relación (FK)</th>
                                                            <th class="text-end">Acciones</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($hijosAsociados as $hijo)
                                                            <tr wire:key="fila-hijo-{{ $hijo->getKey() }}">
                                                                <td class="fw-bold">{{ $hijo->getKey() }}</td>
                                                                <td>{{ $this->obtenerTextoRepresentativoHijo($hijo) }}</td>
                                                                <td>
                                                                    <span class="badge bg-info text-dark">{{ $hijo->{$campoRelacionSeleccionado} }}</span>
                                                                </td>
                                                                <td class="text-end">
                                                                    <button wire:click="abrirEditarHijo({{ $hijo->getKey() }})" class="btn btn-link btn-xs p-0 text-primary me-2" title="Editar Hijo">
                                                                        <i class="bi bi-pencil-fill"></i>
                                                                    </button>
                                                                    <button wire:click="prepararEliminar({{ $hijo->getKey() }}, 'hijo')" class="btn btn-link btn-xs p-0 text-danger" title="Eliminar Hijo">
                                                                        <i class="bi bi-trash-fill"></i>
                                                                    </button>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        @else
                                            <div class="text-center text-muted py-2 small">
                                                <i class="bi bi-info-circle me-1"></i> Sin registros hijos asignados o que coincidan con la búsqueda.
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center text-muted py-5">No existen registros que coincidan con la búsqueda.</div>
                        @endforelse
                    </div>
                </div>
                <div class="cardSec-footer p-2">
                    {{ $arbolPadres->links() }}
                </div>
            </div>
        @else
            <div class="h-100 d-flex flex-column justify-content-center align-items-center text-muted border rounded bg-light" style="min-height: 450px; border-style: dashed !important;">
                <i class="bi bi-tree-fill display-4 text-success"></i>
                <p class="mt-2 fw-bold">Estructurador Jerárquico de Tablas</p>
                <small>Seleccione las tablas e índices en el panel izquierdo y pulse "Analizar Estructura" para desplegar el árbol de datos.</small>
            </div>
        @endif
    </div>
    @if($mostrarModalPadre)
        <div class="modal-overlay">
            <div x-data="{}" x-init="dragModal($el)" class="modal-dialog" wire:ignore.self>
                <div class="modal-content">
                    <div class="cardPrin">
                        <div class="cardPrin-header" style="cursor: move;">
                            <span>{{ $modoEdicionPadre ? 'Editar Registro Padre' : 'Crear Registro Padre' }}</span>
                        </div>
                        <div class="cardPrin-body" style="padding: 10px; max-height: 400px; overflow-y: auto;">
                            <form gy-2>
                                <div class="row">
                                    @foreach($datosFormularioPadre as $campo => $valor)
                                        <div class="col-md-6 mb-2" wire:key="campo-padre-{{ $campo }}">
                                            <label class="etiBase">{{ $campo }}</label>
                                            <input type="text" wire:model.defer="datosFormularioPadre.{{ $campo }}" class="inpBase" onfocus="this.select()" {{ $modoEdicionPadre && $campo === 'id' ? 'disabled' : '' }}>
                                            @error("datosFormularioPadre.{$campo}") <span class="text-danger small">{{ $message }}</span> @enderror
                                        </div>
                                    @endforeach
                                </div>
                            </form>
                        </div>
                        <div class="cardPrin-footer mt-3 d-flex justify-content-end gap-2">
                            <button wire:click.prevent="$set('mostrarModalPadre', false)" class="bot botNegro botChico">Cerrar</button>
                            <button wire:click.prevent="guardarPadre" class="bot botVerde botChico">Guardar</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
    @if($mostrarModalHijo)
        <div class="modal-overlay">
            <div x-data="{}" x-init="dragModal($el)" class="modal-dialog" wire:ignore.self>
                <div class="modal-content">
                    <div class="cardPrin">
                        <div class="cardPrin-header" style="cursor: move;">
                            <span>{{ $modoEdicionHijo ? 'Editar Registro Hijo' : 'Crear Registro Hijo' }}</span>
                        </div>
                        <div class="cardPrin-body" style="padding: 10px; max-height: 400px; overflow-y: auto;">
                            <form gy-2>
                                <div class="row">
                                    @foreach($datosFormularioHijo as $campo => $valor)
                                        <div class="col-md-6 mb-2" wire:key="campo-hijo-{{ $campo }}">
                                            <label class="etiBase">{{ $campo }}</label>
                                            <input type="text" wire:model.defer="datosFormularioHijo.{{ $campo }}" class="inpBase" onfocus="this.select()" {{ $modoEdicionHijo && $campo === 'id' ? 'disabled' : '' }}>
                                            @error("datosFormularioHijo.{$campo}") <span class="text-danger small">{{ $message }}</span> @enderror
                                        </div>
                                    @endforeach
                                </div>
                            </form>
                        </div>
                        <div class="cardPrin-footer mt-3 d-flex justify-content-end gap-2">
                            <button wire:click.prevent="$set('mostrarModalHijo', false)" class="bot botNegro botChico">Cerrar</button>
                            <button wire:click.prevent="guardarHijo" class="bot botVerde botChico">Guardar</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
    <div wire:ignore.self class="modal fade" id="modalConfirmarEliminar" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title"><i class="bi bi-exclamation-octagon-fill me-2"></i>Confirmación de Eliminación Física</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="fw-bold text-dark">¿Estás completamente seguro de eliminar físicamente este registro con ID: <span class="text-danger">{{ $registroAEliminar }}</span>?</p>
                    <p class="text-muted small">Esta acción no se puede deshacer y borrará el registro directamente del disco mediante sentencia DELETE.</p>
                    @if(count($tablasReferenciandoAlerta) > 0)
                        <div class="alert alert-warning p-2 mt-3" role="alert">
                            <h6 class="fw-bold mb-1"><i class="bi bi-shield-fill-exclamation me-1"></i>¡Alerta de Claves Foráneas Activas!</h6>
                            <p class="small mb-2">MySQL bloqueará esta eliminación si continúas. Las siguientes tablas tienen registros activos relacionados con este ID:</p>
                            <ul class="mb-0 small ps-3">
                                @foreach($tablasReferenciandoAlerta as $alerta)
                                    <li>Tabla <strong class="text-dark">"{{ $alerta['tabla'] }}"</strong> (columna: <code>{{ $alerta['columna'] }}</code>) tiene <strong class="text-danger">{{ $alerta['registros'] }}</strong> registro(s).</li>
                                @endforeach
                            </ul>
                        </div>
                    @else
                        <div class="alert alert-success p-2 mt-3 small" role="alert">
                            <i class="bi bi-check-circle-fill me-1"></i> Análisis limpio: No se detectaron restricciones de claves foráneas activas para este ID en otras tablas del sistema.
                        </div>
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" wire:click="ejecutarEliminar" class="btn btn-danger" {{ count($tablasReferenciandoAlerta) > 0 ? 'disabled' : '' }}>
                        Eliminar Definitivamente
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const modalElement = document.getElementById('modalConfirmarEliminar');
        const bsModal = new bootstrap.Modal(modalElement);
        window.addEventListener('abrirModalConfirmacionEliminar', () => {
            bsModal.show();
        });
        window.addEventListener('cerrarModalConfirmacionEliminar', () => {
            bsModal.hide();
        });
    });
</script>