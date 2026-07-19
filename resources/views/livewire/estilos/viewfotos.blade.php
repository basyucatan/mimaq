<div>
    @section('title', __('Análisis de Fotos y Estilos'))
    <div class="container-fluid p-0">
        <div class="row g-3 justify-content-center">
            <div class="col-md-4">
                <div class="cardPrin mb-3">
                    <div class="cardPrin-header">
                        <span>Panel de Administración</span>
                    </div>
                    <div class="cardPrin-body">
                        <button wire:click="cambiarVista('huerfanos')" class="bot {{ $vistaActual === 'huerfanos' ? 'botNegro' : 'botVerde' }} w-100 mb-2" wire:loading.attr="disabled">
                            Detectar Archivos sin Correspondencia
                        </button>
                        <button wire:click="cambiarVista('sin_foto')" class="bot {{ $vistaActual === 'sin_foto' ? 'botNegro' : 'botVerde' }} w-100 mb-2" wire:loading.attr="disabled">
                            Detectar Estilos sin Foto
                        </button>
                        <button wire:click="cambiarVista('con_foto')" class="bot {{ $vistaActual === 'con_foto' ? 'botNegro' : 'botVerde' }} w-100 mb-2" wire:loading.attr="disabled">
                            Detectar Estilos con Foto
                        </button>
                        <button wire:click="cambiarVista('vinculables')" class="bot {{ $vistaActual === 'vinculables' ? 'botNegro' : 'botVerde' }} w-100 mb-2" wire:loading.attr="disabled">
                            Detectar Fotos por Vincular
                        </button>
                        <button wire:click="cambiarVista('rotos')" class="bot {{ $vistaActual === 'rotos' ? 'botNegro' : 'botVerde' }} w-100 mb-3" wire:loading.attr="disabled">
                            Detectar Enlaces Rotos ({{ $conteoEnlacesRotos }})
                        </button>
                        @if($vistaActual === 'huerfanos' && $conteoHuerfanos > 0)
                            <hr class="my-2">
                            <button wire:click="eliminarArchivosHuerfanos" wire:confirm="¿Está seguro de que desea eliminar permanentemente todas las imágenes huérfanas?" class="bot botRojo w-100" wire:loading.attr="disabled">
                                Eliminar Archivos Huérfanos ({{ $conteoHuerfanos }})
                            </button>
                        @endif
                        @if($vistaActual === 'vinculables' && $conteoVinculables > 0)
                            <hr class="my-2">
                            <button wire:click="vincularFotosAutomaticas" wire:confirm="¿Está seguro de que desea vincular automáticamente estas imágenes a sus respectivos estilos?" class="bot botVerde w-100" wire:loading.attr="disabled">
                                Vincular Fotos Detectadas ({{ $conteoVinculables }})
                            </button>
                        @endif
                        @if($mensajeExito)
                            <div class="alert alert-success mt-3 mb-0 py-2 small" role="alert">
                                {{ $mensajeExito }}
                            </div>
                        @endif
                    </div>
                </div>
                <div class="cardPrin">
                    <div class="cardPrin-header">
                        <span>Diagnóstico de Integridad</span>
                    </div>
                    <div class="cardPrin-body p-0">
                        <ul class="list-group list-group-flush small">
                            <li class="list-group-item d-flex justify-content-between align-items-center py-2">
                                <span class="fs-6">Estilos sin foto asignada en BD</span>
                                <span class="badge bg-secondary fs-6">{{ $conteoEstilosSinFoto }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center py-2">
                                <span class="fs-6">Estilos con foto asignada en BD</span>
                                <span class="badge bg-success fs-6">{{ $conteoEstilosConFoto }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center py-2">
                                <span class="fs-6">Archivos físicos en carpeta</span>
                                <span class="badge bg-primary fs-6">{{ $conteoTotalArchivos }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center py-2 bg-light">
                                <span class="fs-6" class="text-danger fw-bold">Archivos huérfanos (Sin BD)</span>
                                <span class="badge bg-danger fs-6">{{ $conteoHuerfanos }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center py-2 bg-light">
                                <span class="fs-6" class="text-info fw-bold">Coincidencias por vincular</span>
                                <span class="badge bg-info text-dark fs-6">{{ $conteoVinculables }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center py-2 bg-light" wire:click="cambiarVista('rotos')" style="cursor: pointer;">
                                <span class="fs-6" class="text-warning fw-bold">Enlaces rotos (BD sin Archivo)</span>
                                <span class="badge bg-warning text-dark fs-6">{{ $conteoEnlacesRotos }}</span>
                            </li>
                        </ul>
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
                                                <th class="ps-3" width="100">ID Estilo</th>
                                                <th>Estilo</th>
                                                <th>Clase</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($resultados as $estilo)
                                                <tr>
                                                    <td class="ps-3 font-monospace fw-bold text-secondary">{{ $estilo->id }}</td>
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
                                                <th width="100">ID Estilo</th>
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
                                                    <td class="font-monospace fw-bold text-secondary">{{ $estilo->id }}</td>
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
                                                <th width="120">ID Estilo</th>
                                                <th>Nombre Estilo (BD)</th>
                                                <th>Archivo Físico Encontrado</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($resultados as $item)
                                                <tr>
                                                    <td class="ps-3">
                                                        <img src="{{ asset('storage/estilos/' . $item['archivo']) }}" alt="{{ $item['archivo'] }}" class="img-thumbnail" style="width: 40px; height: 40px; object-fit: cover; cursor: pointer;" data-bs-toggle="modal" data-bs-target="#modalVisorFoto" onclick="document.getElementById('imgVisorContenedor').src=this.src; document.getElementById('tituloVisorFoto').innerText='{{ $item['archivo'] }}';">
                                                    </td>
                                                    <td class="font-monospace fw-bold text-secondary">{{ $item['IdEstilo'] }}</td>
                                                    <td class="fw-bold text-dark">{{ $item['estilo'] }}</td>
                                                    <td class="font-monospace text-success fw-bold">{{ $item['archivo'] }}</td>
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
                                    <span class="text-muted d-block fw-bold">No se encontraron archivos sueltos para vincular en esta página.</span>
                                </div>
                            @endif
                        </div>
                    @elseif($vistaActual === 'rotos')
                        <div class="cardPrin-header d-flex justify-content-between align-items-center">
                            <span>Resultado: Enlaces Rotos (Asignados en BD pero faltantes en disco)</span>
                            <span class="badge bg-danger text-white font-monospace fw-bold px-2 py-1" style="font-size: 11px;">PÁGINA {{ $resultados->currentPage() }}</span>
                        </div>
                        <div class="cardPrin-body">
                            @if($resultados->count() > 0)
                                <div class="tablaCont">
                                    <table class="table tabBase ch mb-0">
                                        <thead>
                                            <tr>
                                                <th class="ps-3" width="100">ID Estilo</th>
                                                <th>Estilo (BD)</th>
                                                <th>Ruta Buscada en Carpeta</th>
                                                <th>Clase</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($resultados as $estilo)
                                                <tr>
                                                    <td class="ps-3 font-monospace fw-bold text-secondary">{{ $estilo->id }}</td>
                                                    <td class="fw-bold text-dark">{{ $estilo->estilo }}</td>
                                                    <td class="font-monospace text-danger fw-bold small">estilos/{{ $estilo->foto }}</td>
                                                    <td class="text-muted small">{{ $estilo->clase->clase ?? 'Sin Clase' }}</td>
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
                                    <span class="text-muted d-block fw-bold">Excelente, no tienes registros con enlaces rotos.</span>
                                </div>
                            @endif
                        </div>
                    @endif
                </div>
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