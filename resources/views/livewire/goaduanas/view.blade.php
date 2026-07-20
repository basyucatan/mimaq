<div class="row">
    <div class="col-md-6">
        <div class="card p-3">
            <h5 class="mb-3">Configuracion del Modulo</h5>
            <p>Este panel izquierdo le permite gestionar la preparacion de las facturas comerciales con integracion a Go Aduanas.</p>
            <button wire:click="cargarDatosLocales" class="bot botVerde mb-2 w-100">Recargar Datos Locales</button>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card p-3">
            <h5 class="mb-3">Previsualizacion de Datos y Envio</h5>
            <div class="mb-3">
                <label class="form-label fw-bold">Estructura JSON:</label>
                <textarea class="form-control bg-light text-dark font-monospace" rows="10" readonly>{{ $datosJson }}</textarea>
            </div>
            <button wire:click="enviarDatosApi" wire:loading.attr="disabled" class="bot botNegro w-100 mb-3">
                <span wire:loading.remove wire:target="enviarDatosApi">Enviar a Go Aduanas</span>
                <span wire:loading wire:target="enviarDatosApi">⏳ Transmitiendo datos...</span>
            </button>
            @if($mensajeExito)
                <div class="alert alert-success py-2">{{ $mensajeExito }}</div>
            @endif
            @if($mensajeError)
                <div class="alert alert-danger py-2">{{ $mensajeError }}</div>
            @endif
            @if($respuestaApi)
                <div class="mt-2">
                    <label class="form-label fw-bold text-secondary">Respuesta de la API:</label>
                    <pre class="bg-dark text-success p-2 rounded fs-6">{{ $respuestaApi }}</pre>
                </div>
            @endif
        </div>
    </div>
</div>