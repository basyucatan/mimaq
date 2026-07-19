<?php
namespace App\Livewire;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Estilo;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
class Fotosindex extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $mensajeExito = '';
    public $vistaActual = 'huerfanos';
    public $conteoEstilosSinFoto = 0;
    public $conteoEstilosConFoto = 0;
    public $conteoTotalArchivos = 0;
    public $conteoHuerfanos = 0;
    public $conteoVinculables = 0;
    public $conteoEnlacesRotos = 0;
    public function mount()
    {
        $this->calcularEstadisticas();
    }
    public function updatingVistaActual()
    {
        $this->resetPage();
    }
    public function paginationView()
    {
        return 'livewire.paginacionBase';
    }
    public function calcularEstadisticas()
    {
        $this->conteoEstilosSinFoto = Estilo::whereNull('foto')->orWhere('foto', '')->count();
        $this->conteoEstilosConFoto = Estilo::whereNotNull('foto')->where('foto', '<>', '')->count();
        $archivosFisicos = Storage::disk('public')->files('estilos');
        $extensionesPermitidas = ['png', 'jpg', 'jpeg', 'webp', 'gif', 'bmp'];
        $archivosValidos = [];
        foreach ($archivosFisicos as $ruta) {
            $extension = strtolower(pathinfo($ruta, PATHINFO_EXTENSION));
            if (in_array($extension, $extensionesPermitidas)) {
                $archivosValidos[] = basename($ruta);
            }
        }
        $this->conteoTotalArchivos = count($archivosValidos);
        $fotosEnBaseDatos = Estilo::whereNotNull('foto')->where('foto', '<>', '')->pluck('foto')->map(fn($item) => strtolower(trim($item)))->toArray();
        $huerfanos = 0;
        foreach ($archivosValidos as $archivo) {
            if (!in_array(strtolower($archivo), $fotosEnBaseDatos)) {
                $huerfanos++;
            }
        }
        $this->conteoHuerfanos = $huerfanos;
        $estilosSinFoto = Estilo::whereNull('foto')->orWhere('foto', '')->get();
        $vinculables = 0;
        foreach ($archivosValidos as $archivo) {
            $nombreSinExtension = Str::lower(pathinfo($archivo, PATHINFO_FILENAME));
            foreach ($estilosSinFoto as $estilo) {
                if (Str::lower($estilo->estilo) === $nombreSinExtension) {
                    $vinculables++;
                    break;
                }
            }
        }
        $this->conteoVinculables = $vinculables;
        $enlacesRotos = 0;
        $archivosFisicosEnMinusculas = array_map('strtolower', $archivosValidos);
        $estilosConFoto = Estilo::whereNotNull('foto')->where('foto', '<>', '')->get();
        foreach ($estilosConFoto as $estilo) {
            if (!in_array(strtolower(trim($estilo->foto)), $archivosFisicosEnMinusculas)) {
                $enlacesRotos++;
            }
        }
        $this->conteoEnlacesRotos = $enlacesRotos;
    }
    public function cambiarVista($vista)
    {
        $this->mensajeExito = '';
        $this->vistaActual = $vista;
        $this->calcularEstadisticas();
        $this->resetPage();
    }
    public function eliminarArchivosHuerfanos()
    {
        $archivosFisicos = Storage::disk('public')->files('estilos');
        $extensionesPermitidas = ['png', 'jpg', 'jpeg', 'webp', 'gif', 'bmp'];
        $fotosEnBaseDatos = Estilo::whereNotNull('foto')->where('foto', '<>', '')->pluck('foto')->map(fn($item) => strtolower(trim($item)))->toArray();
        $eliminados = 0;
        foreach ($archivosFisicos as $ruta) {
            $extension = strtolower(pathinfo($ruta, PATHINFO_EXTENSION));
            if (in_array($extension, $extensionesPermitidas)) {
                $archivo = basename($ruta);
                if (!in_array(strtolower($archivo), $fotosEnBaseDatos)) {
                    Storage::disk('public')->delete('estilos/' . $archivo);
                    $eliminados++;
                }
            }
        }
        $this->mensajeExito = 'Se eliminaron ' . $eliminados . ' archivos huérfanos.';
        $this->calcularEstadisticas();
        $this->resetPage();
    }
    public function asignarArchivosConCoincidencias()
    {
        $archivosFisicos = Storage::disk('public')->files('estilos');
        $extensionesPermitidas = ['png', 'jpg', 'jpeg', 'webp', 'gif', 'bmp'];
        $estilosSinFoto = Estilo::whereNull('foto')->orWhere('foto', '')->get();
        $vinculados = 0;
        foreach ($archivosFisicos as $ruta) {
            $extension = strtolower(pathinfo($ruta, PATHINFO_EXTENSION));
            if (in_array($extension, $extensionesPermitidas)) {
                $archivo = basename($ruta);
                $nombreSinExtension = Str::lower(pathinfo($archivo, PATHINFO_FILENAME));
                foreach ($estilosSinFoto as $estilo) {
                    if (Str::lower($estilo->estilo) === $nombreSinExtension) {
                        $estiloActualizado = Estilo::find($estilo->id);
                        if ($estiloActualizado) {
                            $estiloActualizado->foto = $archivo;
                            $estiloActualizado->save();
                            $vinculados++;
                        }
                    }
                }
            }
        }
        $this->mensajeExito = 'Se han asignado ' . $vinculados . ' archivos con coincidencias exitosamente.';
        $this->calcularEstadisticas();
        $this->cambiarVista('con_foto');
    }
    public function render()
    {
        $paginacion = 30;
        $elementosPaginados = [];
        $paginaActual = $this->paginators['page'] ?? 1;
        if ($this->vistaActual === 'huerfanos') {
            $archivosFisicos = Storage::disk('public')->files('estilos');
            $extensionesPermitidas = ['png', 'jpg', 'jpeg', 'webp', 'gif', 'bmp'];
            $fotosEnBaseDatos = Estilo::whereNotNull('foto')->where('foto', '<>', '')->pluck('foto')->map(fn($item) => strtolower(trim($item)))->toArray();
            $coleccion = [];
            foreach ($archivosFisicos as $ruta) {
                $extension = strtolower(pathinfo($ruta, PATHINFO_EXTENSION));
                if (in_array($extension, $extensionesPermitidas)) {
                    $archivo = basename($ruta);
                    if (!in_array(strtolower($archivo), $fotosEnBaseDatos)) {
                        $coleccion[] = $archivo;
                    }
                }
            }
            $elementosPaginados = new \Illuminate\Pagination\LengthAwarePaginator(array_slice($coleccion, ($paginaActual - 1) * $paginacion, $paginacion), count($coleccion), $paginacion, $paginaActual, ['path' => url()->current()]);
        } elseif ($this->vistaActual === 'sin_foto') {
            $elementosPaginados = Estilo::with('clase')->whereNull('foto')->orWhere('foto', '')->paginate($paginacion);
        } elseif ($this->vistaActual === 'con_foto') {
            $elementosPaginados = Estilo::with('clase')->whereNotNull('foto')->where('foto', '<>', '')->paginate($paginacion);
        } elseif ($this->vistaActual === 'vinculables') {
            $archivosFisicos = Storage::disk('public')->files('estilos');
            $extensionesPermitidas = ['png', 'jpg', 'jpeg', 'webp', 'gif', 'bmp'];
            $estilosSinFoto = Estilo::whereNull('foto')->orWhere('foto', '')->get();
            $coleccion = [];
            foreach ($archivosFisicos as $ruta) {
                $extension = strtolower(pathinfo($ruta, PATHINFO_EXTENSION));
                if (in_array($extension, $extensionesPermitidas)) {
                    $archivo = basename($ruta);
                    $nombreSinExtension = Str::lower(pathinfo($archivo, PATHINFO_FILENAME));
                    foreach ($estilosSinFoto as $estilo) {
                        if (Str::lower($estilo->estilo) === $nombreSinExtension) {
                            $coleccion[] = ['IdEstilo' => $estilo->id, 'estilo' => $estilo->estilo, 'archivo' => $archivo];
                        }
                    }
                }
            }
            $elementosPaginados = new \Illuminate\Pagination\LengthAwarePaginator(array_slice($coleccion, ($paginaActual - 1) * $paginacion, $paginacion), count($coleccion), $paginacion, $paginaActual, ['path' => url()->current()]);
        } elseif ($this->vistaActual === 'rotos') {
            $archivosFisicos = Storage::disk('public')->files('estilos');
            $extensionesPermitidas = ['png', 'jpg', 'jpeg', 'webp', 'gif', 'bmp'];
            $archivosValidosEnMinusculas = [];
            foreach ($archivosFisicos as $ruta) {
                $extension = strtolower(pathinfo($ruta, PATHINFO_EXTENSION));
                if (in_array($extension, $extensionesPermitidas)) {
                    $archivosValidosEnMinusculas[] = strtolower(basename($ruta));
                }
            }
            $estilosConFoto = Estilo::with('clase')->whereNotNull('foto')->where('foto', '<>', '')->get();
            $coleccion = [];
            foreach ($estilosConFoto as $estilo) {
                if (!in_array(strtolower(trim($estilo->foto)), $archivosValidosEnMinusculas)) {
                    $coleccion[] = $estilo;
                }
            }
            $elementosPaginados = new \Illuminate\Pagination\LengthAwarePaginator(array_slice($coleccion, ($paginaActual - 1) * $paginacion, $paginacion), count($coleccion), $paginacion, $paginaActual, ['path' => url()->current()]);
        }
        return view('livewire.estilos.viewfotos', ['resultados' => $elementosPaginados]);
    }
}