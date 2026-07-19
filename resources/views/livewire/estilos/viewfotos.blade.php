@section('title', __('Análisis de Fotos y Estilos'))
<div class="container-fluid p-0">
    <div class="row g-3 justify-content-center">
        <div class="col-md-4">
            <div class="cardPrin mb-3">
                <div class="cardPrin-header">
                    <span>Panel de Administración y Diagnóstico</span>
                </div>
                <div class="cardPrin-body">
                    <div class="row g-2 align-items-center mb-2">
                        <div class="col-9">
                            <button wire:click="cambiarVista('huerfanos')" class="bot {{ $vistaActual === 'huerfanos' ? 'botNegro' : 'botVerde' }} w-100" wire:loading.attr="disabled" wire:target="cambiarVista('huerfanos')">
                                <span wire:loading.remove wire:target="cambiarVista('huerfanos')">Detectar Archivos sin Correspondencia</span>
                                <span wire:loading wire:target="cambiarVista('huerfanos')">⏳ Procesando...</span>
                            </button>
                        </div>
                        <div class="col-3 text-end">
                            <span class="badge bg-danger fs-6 w-100 py-2">{{ $conteoHuerfanos }}</span>
                        </div>
                    </div>
<div class="row g-2 align-items-center mb-2">
    <div class="col-9">
        <button wire:click="cambiarVista('vinculables')" class="bot {{ $vistaActual === 'vinculables' ? 'botNegro' : 'botVerde' }} w-100" wire:loading.attr="disabled" wire:target="cambiarVista('vinculables')">
            <span wire:loading.remove wire:target="cambiarVista('vinculables')">Archivos vinculables</span>
            <span wire:loading wire:target="cambiarVista('vinculables')">⏳ Procesando...</span>
        </button>
    </div>
    <div class="col-3 text-end">
        <span class="badge bg-info text-dark fs-6 w-100 py-2">{{ $conteoVinculables }}</span>
    </div>
</div>
                    <div class="row g-2 align-items-center mb-2">
                        <div class="col-9">
                            <button wire:click="cambiarVista('sin_foto')" class="bot {{ $vistaActual === 'sin_foto' ? 'botNegro' : 'botVerde' }} w-100" wire:loading.attr="disabled" wire:target="cambiarVista('sin_foto')">
                                <span wire:loading.remove wire:target="cambiarVista('sin_foto')">Detectar Estilos sin Foto</span>
                                <span wire:loading wire:target="cambiarVista('sin_foto')">⏳ Procesando...</span>
                            </button>
                        </div>
                        <div class="col-3 text-end">
                            <span class="badge bg-secondary fs-6 w-100 py-2">{{ $conteoEstilosSinFoto }}</span>
                        </div>
                    </div>
                    <div class="row g-2 align-items-center mb-2">
                        <div class="col-9">
                            <button wire:click="cambiarVista('con_foto')" class="bot {{ $vistaActual === 'con_foto' ? 'botNegro' : 'botVerde' }} w-100" wire:loading.attr="disabled" wire:target="cambiarVista('con_foto')">
                                <span wire:loading.remove wire:target="cambiarVista('con_foto')">Detectar Estilos con Foto</span>
                                <span wire:loading wire:target="cambiarVista('con_foto')">⏳ Procesando...</span>
                            </button>
                        </div>
                        <div class="col-3 text-end">
                            <span class="badge bg-success fs-6 w-100 py-2">{{ $conteoEstilosConFoto }}</span>
                        </div>
                    </div>
                    <div class="row g-2 align-items-center mb-3">
                        <div class="col-9">
                            <button wire:click="cambiarVista('rotos')" class="bot {{ $vistaActual === 'rotos' ? 'botNegro' : 'botVerde' }} w-100" wire:loading.attr="disabled" wire:target="cambiarVista('rotos')">
                                <span wire:loading.remove wire:target="cambiarVista('rotos')">Detectar Enlaces Rotos</span>
                                <span wire:loading wire:target="cambiarVista('rotos')">⏳ Procesando...</span>
                            </button>
                        </div>
                        <div class="col-3 text-end">
                            <span class="badge bg-warning text-dark fs-6 w-100 py-2">{{ $conteoEnlacesRotos }}</span>
                        </div>
                    </div>
                    <ul class="list-group list-group-flush small border-top pt-2">
                        <li class="list-group-item d-flex justify-content-between align-items-center py-2 px-1">
                            <span class="fs-6 text-muted">Archivos físicos en carpeta (Total)</span>
                            <span class="badge bg-primary fs-6">{{ $conteoTotalArchivos }}</span>
                        </li>
                    </ul>
                    @if($vistaActual === 'huerfanos' && $conteoHuerfanos > 0)
                        <hr class="my-2">
                        <button wire:click="eliminarArchivosHuerfanos" wire:confirm="¿Está seguro de que desea eliminar permanentemente todas las imágenes huérfanas?" class="bot botRojo w-100 mb-2" wire:loading.attr="disabled" wire:target="eliminarArchivosHuerfanos">
                            <span wire:loading.remove wire:target="eliminarArchivosHuerfanos">Eliminar Archivos Huérfanos ({{ $conteoHuerfanos }})</span>
                            <span wire:loading wire:target="eliminarArchivosHuerfanos">⏳ Eliminando archivos...</span>
                        </button>
                    @endif
                    @if(($vistaActual === 'huerfanos' || $vistaActual === 'vinculables') && $conteoVinculables > 0)
                        @if($vistaActual === 'huerfanos')
                            <hr class="my-2">
                        @endif
                        <button wire:click="asignarArchivosConCoincidencias" wire:confirm="¿Está seguro de que desea asignar automáticamente estas imágenes a sus respectivos estilos?" class="bot botVerde w-100" wire:loading.attr="disabled" wire:target="asignarArchivosConCoincidencias">
                            <span wire:loading.remove wire:target="asignarArchivosConCoincidencias">Vincular archivos ({{ $conteoVinculables }})</span>
                            <span wire:loading wire:target="asignarArchivosConCoincidencias">⏳ Vinculando elementos...</span>
                        </button>
                    @endif
                    @if($mensajeExito)
                        <div class="alert alert-success mt-3 mb-0 py-2 small" role="alert">
                            {{ $mensajeExito }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="cardPrin">
                @if($vistaActual === 'huerfanos')
                    <div class="cardPrin-header d-flex justify-content-between align-items-center">
                        <span>Resultado: Fotos sin Correspondencia en BD</span>
                        <span class="badge bg-warning text-dark font-monospace fw-bold px-2 py-1" style="font-size: 11px;">PÁGINA {{ $resultados->currentPage() }}</span>
                    </div>
                    <div class="cardPrin-body">
                        @if($resultados->count() > 0)
                            <div class="tablaCont">
                                <table class="table tabBase ch mb-0">
                                    <thead>
                                        <tr>
                                            <th class="ps-3" width="80">Miniatura</th>
                                            <th>Nombre del Archivo Físico</th>
                                            <th width="120">Extensión</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($resultados as $archivo)
                                            <tr>
                                                <td class="ps-3">
                                                    <img src="{{ asset('storage/estilos/' . $archivo) }}" alt="{{ $archivo }}" class="img-thumbnail" style="width: 40px; height: 40px; object-fit: cover; cursor: pointer;" data-bs-toggle="modal" data-bs-target="#modalVisorFoto" onclick="document.getElementById('imgVisorContenedor').src=this.src; document.getElementById('tituloVisorFoto').innerText='{{ $archivo }}';">
                                                </td>
                                                <td class="font-monospace text-danger fw-bold">{{ $archivo }}</td>
                                                <td>
                                                    <span class="badge bg-secondary font-monospace">{{ strtoupper(pathinfo($archivo, PATHINFO_EXTENSION)) }}</span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="mt-3 d-flex justify-content-end mb-2">
                                {{ $resultados->links() }}
                            </div>
                        @else
                            <div class="text-center py-5">
                                <span class="text-muted d-block fw-bold">No hay archivos huérfanos en esta sección.</span>
                            </div>
                        @endif
                    </div>
                @elseif($vistaActual === 'sin_foto')
                    <div class="cardPrin-header d-flex justify-content-between align-items-center">
                        <span>Resultado: Estilos sin Imagen</span>
                        <span class="badge bg-danger text-white font-monospace fw-bold px-2 py-1" style="font-size: 11px;">PÁGINA {{ $resultados->currentPage() }}</span>
                    </div>
                    <div class="cardPrin-body">
                        @if($resultados->count() > 0)
                            <div class="tablaCont">
                                <table class="table tabBase ch mb-0">
                                    <thead>
                                        <tr>
                                            <th>Estilo</th>
                                            <th>Clase</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($resultados as $estilo)
                                            <tr>
                                                <td class="fw-bold text-dark">{{ $estilo->estilo }}</td>
                                                <td class="text-muted small">{{ $estilo->clase->clase ?? 'Sin Clase' }} (Id: {{ $estilo->IdClase }})</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="mt-3 d-flex justify-content-end mb-2">
                                {{ $resultados->links() }}
                            </div>
                        @else
                            <div class="text-center py-5">
                                <span class="text-muted d-block fw-bold">Todos los estilos cuentan con una foto vinculada.</span>
                            </div>
                        @endif
                    </div>
                @elseif($vistaActual === 'con_foto')
                    <div class="cardPrin-header d-flex justify-content-between align-items-center">
                        <span>Resultado: Estilos con Imagen</span>
                        <span class="badge bg-success text-white font-monospace fw-bold px-2 py-1" style="font-size: 11px;">PÁGINA {{ $resultados->currentPage() }}</span>
                    </div>
                    <div class="cardPrin-body">
                        @if($resultados->count() > 0)
                            <div class="tablaCont">
                                <table class="table tabBase ch mb-0">
                                    <thead>
                                        <tr>
                                            <th class="ps-3" width="70">Foto</th>
                                            <th>Estilo</th>
                                            <th>Nombre de la Imagen</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($resultados as $estilo)
                                            <tr>
                                                <td class="ps-3">
                                                    <img src="{{ asset('storage/estilos/' . $estilo->foto) }}" alt="{{ $estilo->estilo }}" class="img-thumbnail" style="width: 35px; height: 35px; object-fit: cover; cursor: pointer;" data-bs-toggle="modal" data-bs-target="#modalVisorFoto" onclick="document.getElementById('imgVisorContenedor').src=this.src; document.getElementById('tituloVisorFoto').innerText='{{ $estilo->estilo }} ({{ $estilo->foto }})';">
                                                </td>
                                                <td class="fw-bold text-dark">{{ $estilo->estilo }}</td>
                                                <td class="font-monospace text-muted small">{{ $estilo->foto }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="mt-3 d-flex justify-content-end mb-2">
                                {{ $resultados->links() }}
                            </div>
                        @else
                            <div class="text-center py-5">
                                <span class="text-muted d-block fw-bold">No existen estilos con imágenes registradas en esta vista.</span>
                            </div>
                        @endif
                    </div>
                @elseif($vistaActual === 'vinculables')
                    <div class="cardPrin-header d-flex justify-content-between align-items-center">
                        <span>Resultado: Fotos con Nombre Coincidente</span>
                        <span class="badge bg-info text-dark font-monospace fw-bold px-2 py-1" style="font-size: 11px;">PÁGINA {{ $resultados->currentPage() }}</span>
                    </div>
                    <div class="cardPrin-body">
                        @if($resultados->count() > 0)
                            <div class="tablaCont">
                                <table class="table tabBase ch mb-0">
                                    <thead>
                                        <tr>
                                            <th class="ps-3" width="80">Miniatura</th>
                                            <th>Estilo Coincidente</th>
                                            <th>Nombre del Archivo</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($resultados as $item)
                                            <tr>
                                                <td class="ps-3">
                                                    <img src="{{ asset('storage/estilos/' . $item['archivo']) }}" alt="{{ $item['archivo'] }}" class="img-thumbnail" style="width: 40px; height: 40px; object-fit: cover; cursor: pointer;" data-bs-toggle="modal" data-bs-target="#modalVisorFoto" onclick="document.getElementById('imgVisorContenedor').src=this.src; document.getElementById('tituloVisorFoto').innerText='{{ $item['archivo'] }}';">
                                                </td>
                                                <td class="fw-bold text-dark">{{ $item['estilo'] }}</td>
                                                <td class="font-monospace text-info fw-bold">{{ $item['archivo'] }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="mt-3 d-flex justify-content-end mb-2">
                                {{ $resultados->links() }}
                            </div>
                        @else
                            <div class="text-center py-5">
                                <span class="text-muted d-block fw-bold">No hay archivos con coincidencias pendientes por asignar.</span>
                            </div>
                        @endif
                    </div>
                @elseif($vistaActual === 'rotos')
                    <div class="cardPrin-header d-flex justify-content-between align-items-center">
                        <span>Resultado: Enlaces Rotos</span>
                        <span class="badge bg-warning text-dark font-monospace fw-bold px-2 py-1" style="font-size: 11px;">PÁGINA {{ $resultados->currentPage() }}</span>
                    </div>
                    <div class="cardPrin-body">
                        @if($resultados->count() > 0)
                            <div class="tablaCont">
                                <table class="table tabBase ch mb-0">
                                    <thead>
                                        <tr>
                                            <th>Estilo</th>
                                            <th>Imagen Faltante</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($resultados as $estilo)
                                            <tr>
                                                <td class="fw-bold text-dark">{{ $estilo->estilo }}</td>
                                                <td class="font-monospace text-danger fw-bold">{{ $estilo->foto }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="mt-3 d-flex justify-content-end mb-2">
                                {{ $resultados->links() }}
                            </div>
                        @else
                            <div class="text-center py-5">
                                <span class="text-muted d-block fw-bold">No se detectaron enlaces rotos en la base de datos.</span>
                            </div>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>
    <div class="modal fade" id="modalVisorFoto" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content border-0 bg-transparent shadow-none">
                <div class="modal-header border-0 p-2 justify-content-between align-items-center bg-dark text-white rounded-top">
                    <span id="tituloVisorFoto" class="fw-bold small ps-2 font-monospace">Previsualización</span>
                    <button type="button" class="btn-close btn-close-white shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-0 d-flex justify-content-center align-items-center bg-black bg-opacity-25 rounded-bottom" style="height: 80vh; overflow: hidden;">
                    <img id="imgVisorContenedor" src="" alt="Previsualización" class="w-100 h-100" style="max-height: 80vh; max-width: 100%; object-fit: contain; transform: scale(1.0);">
                </div>
            </div>
        </div>
    </div>
</div>