<?php
namespace App\Livewire;
use Livewire\Component;
use Illuminate\Support\Facades\Http;
class Goaduanas extends Component
{
    public $datosJson = '';
    public $respuestaApi = '';
    public $mensajeExito = '';
    public $mensajeError = '';
    public function mount()
    {
        $this->cargarDatosLocales();
    }
    public function cargarDatosLocales()
    {
        $rutaArchivo = base_path('factura_prueba.json');
        if (file_exists($rutaArchivo)) {
            $this->datosJson = file_get_contents($rutaArchivo);
        }
    }
public function enviarDatosApi()
{
    $this->mensajeExito = '';
    $this->mensajeError = '';
    if (empty($this->datosJson)) {
        $this->mensajeError = 'No hay datos cargados para enviar';
        return;
    }
    $urlQa = 'https://api-qa.1smart.mx/ema-api/REG_COM_INT.pro';
    $token = '02f53bf3-2603-4707-9ac7-c1f4c3ceb562';
    $cuerpoDatos = json_decode($this->datosJson, true);
    $respuesta = Http::withHeaders([
        'Authorization' => $token,
        'Content-Type' => 'application/json'
    ])->post($urlQa, $cuerpoDatos);
    $datosRespuesta = $respuesta->json();
    $this->respuestaApi = json_encode($datosRespuesta, JSON_PRETTY_PRINT) ?: $respuesta->body();
    if (isset($datosRespuesta['estado']) && $datosRespuesta['estado'] == 200) {
        $this->mensajeExito = $datosRespuesta['mensaje'] ?? 'Registro procesado exitosamente';
    } else {
        $this->mensajeError = $datosRespuesta['mensaje'] ?? 'Error en la respuesta del servidor';
    }
}
    public function render()
    {
        return view('livewire.goaduanas.view');
    }
}