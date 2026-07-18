<?php
namespace App\Livewire;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
class Homologacion extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $modeloPadreSeleccionado = '';
    public $modeloHijoSeleccionado = '';
    public $campoRelacionSeleccionado = '';
    public $camposDisponiblesPadre = [];
    public $camposDisponiblesHijo = [];
    public $camposPadreSeleccionados = [];
    public $camposHijoSeleccionados = [];
    public $keyWord = '';
    public $estadisticas = null;
    public $analisisRealizado = false;
    protected $configuracionModelos = [];
    public $mostrarModalPadre = false;
    public $modoEdicionPadre = false;
    public $IdPadreSeleccionado = null;
    public $datosFormularioPadre = [];
    public $mostrarModalHijo = false;
    public $modoEdicionHijo = false;
    public $IdHijoSeleccionado = null;
    public $datosFormularioHijo = [];
    public $registroAEliminar = null;
    public $tipoRegistroAEliminar = '';
    public $tablasReferenciandoAlerta = [];
    public $padresExpandidos = [];
    public function boot()
    {
        $this->configuracionModelos = File::exists(app_path('Models'))
            ? collect(File::files(app_path('Models')))
                ->map(function ($archivo) {
                    return 'App\\Models\\' . pathinfo($archivo->getFilename(), PATHINFO_FILENAME);
                })
                ->filter(fn($modelo) => class_exists($modelo))
                ->keyBy(fn($modelo) => class_basename($modelo))
                ->sortKeys()
                ->toArray()
            : [];
    }
    public function updatedModeloPadreSeleccionado($valor)
    {
        $this->analisisRealizado = false;
        $this->estadisticas = null;
        $this->camposPadreSeleccionados = [];
        $this->camposDisponiblesPadre = [];
        $this->padresExpandidos = [];
        if (isset($this->configuracionModelos[$valor])) {
            $instancia = new $this->configuracionModelos[$valor]();
            $tabla = $instancia->getTable();
            $this->camposDisponiblesPadre = Schema::getColumnListing($tabla);
        }
        $this->resetPage();
    }
    public function updatedModeloHijoSeleccionado($valor)
    {
        $this->analisisRealizado = false;
        $this->estadisticas = null;
        $this->campoRelacionSeleccionado = '';
        $this->camposHijoSeleccionados = [];
        $this->camposDisponiblesHijo = [];
        $this->padresExpandidos = [];
        if (isset($this->configuracionModelos[$valor])) {
            $instancia = new $this->configuracionModelos[$valor]();
            $tabla = $instancia->getTable();
            $this->camposDisponiblesHijo = Schema::getColumnListing($tabla);
        }
        $this->resetPage();
    }
    public function updatedCampoRelacionSeleccionado($valor)
    {
        $this->analisisRealizado = false;
        $this->estadisticas = null;
        $this->resetPage();
    }
    public function updatedKeyWord()
    {
        $this->resetPage();
    }
    private function limpiarEstadoAnalisis()
    {
        $this->analisisRealizado = false;
        $this->estadisticas = null;
        $this->campoRelacionSeleccionado = '';
        $this->camposDisponiblesPadre = [];
        $this->camposDisponiblesHijo = [];
        $this->camposPadreSeleccionados = [];
        $this->camposHijoSeleccionados = [];
        $this->keyWord = '';
        $this->padresExpandidos = [];
        $this->resetPage();
    }
    public function alternarPadre($id)
    {
        if (in_array($id, $this->padresExpandidos)) {
            $this->padresExpandidos = array_diff($this->padresExpandidos, [$id]);
        } else {
            $this->padresExpandidos[] = $id;
        }
    }
    public function obtenerTextoRepresentativoPadre($registro)
    {
        if (!$registro) {
            return 'Registro Padre';
        }
        if (!empty($this->camposPadreSeleccionados)) {
            $valores = [];
            foreach ($this->camposPadreSeleccionados as $campo) {
                if (isset($registro->{$campo}) && !is_null($registro->{$campo}) && $registro->{$campo} !== '') {
                    $valores[] = $registro->{$campo};
                }
            }
            if (!empty($valores)) {
                return implode(' | ', $valores);
            }
        }
        $columnaDeseada = strtolower($this->modeloPadreSeleccionado);
        if (isset($registro->{$columnaDeseada}) && !empty($registro->{$columnaDeseada})) {
            return $registro->{$columnaDeseada};
        }
        $alternativas = ['name', 'nombre', 'descripcion', 'clase', 'casa', 'titulo', 'codigo'];
        foreach ($alternativas as $campo) {
            if (isset($registro->{$campo}) && !empty($registro->{$campo})) {
                return $registro->{$campo};
            }
        }
        $atributos = $registro->getAttributes();
        foreach ($atributos as $columna => $valor) {
            if ($columna !== $registro->getKeyName() && is_string($valor) && !empty($valor) && strlen($valor) < 100) {
                return $valor;
            }
        }
        return 'ID: ' . $registro->getKey();
    }
    public function obtenerTextoRepresentativoHijo($registro)
    {
        if (!$registro) {
            return 'Registro Hijo';
        }
        if (!empty($this->camposHijoSeleccionados)) {
            $valores = [];
            foreach ($this->camposHijoSeleccionados as $campo) {
                if (isset($registro->{$campo}) && !is_null($registro->{$campo}) && $registro->{$campo} !== '') {
                    $valores[] = $registro->{$campo};
                }
            }
            if (!empty($valores)) {
                return implode(' - ', $valores);
            }
        }
        $columnaDeseada = strtolower($this->modeloHijoSeleccionado);
        if (isset($registro->{$columnaDeseada}) && !empty($registro->{$columnaDeseada})) {
            return $registro->{$columnaDeseada};
        }
        $alternativas = ['name', 'nombre', 'descripcion', 'clase', 'casa', 'titulo', 'codigo'];
        foreach ($alternativas as $campo) {
            if (isset($registro->{$campo}) && !empty($registro->{$campo})) {
                return $registro->{$campo};
            }
        }
        $atributos = $registro->getAttributes();
        foreach ($atributos as $columna => $valor) {
            if ($columna !== $registro->getKeyName() && $columna !== $this->campoRelacionSeleccionado && is_string($valor) && !empty($valor) && strlen($valor) < 100) {
                return $valor;
            }
        }
        return 'ID: ' . $registro->getKey();
    }
    public function analizar()
    {
        $this->validate([
            'modeloPadreSeleccionado' => 'required',
            'modeloHijoSeleccionado' => 'required',
            'campoRelacionSeleccionado' => 'required'
        ]);
        $clasePadre = $this->configuracionModelos[$this->modeloPadreSeleccionado];
        $claseHijo = $this->configuracionModelos[$this->modeloHijoSeleccionado];
        $padreInstancia = new $clasePadre();
        $hijoInstancia = new $claseHijo();
        $tablaPadre = $padreInstancia->getTable();
        $tablaHijo = $hijoInstancia->getTable();
        $clavePrimariaPadre = $padreInstancia->getKeyName();
        $fkHijo = $this->campoRelacionSeleccionado;
        $totalPadre = $clasePadre::count();
        $totalReferenciados = $clasePadre::whereIn($clavePrimariaPadre, function ($query) use ($tablaHijo, $fkHijo) {
            $query->select($fkHijo)->from($tablaHijo)->whereNotNull($fkHijo);
        })->count();
        $totalHuerfanos = $clasePadre::whereNotIn($clavePrimariaPadre, function ($query) use ($tablaHijo, $fkHijo) {
            $query->select($fkHijo)->from($tablaHijo)->whereNotNull($fkHijo);
        })->count();
        $cantidadRelacionesExistentes = $claseHijo::whereIn($fkHijo, function ($query) use ($tablaPadre, $clavePrimariaPadre) {
            $query->select($clavePrimariaPadre)->from($tablaPadre);
        })->count();
        $cantidadRelacionesInexistentes = $claseHijo::whereNotIn($fkHijo, function ($query) use ($tablaPadre, $clavePrimariaPadre) {
            $query->select($clavePrimariaPadre)->from($tablaPadre);
        })->whereNotNull($fkHijo)->count();
        $this->estadisticas = [
            'totalPadre' => $totalPadre,
            'totalReferenciados' => $totalReferenciados,
            'totalHuerfanos' => $totalHuerfanos,
            'relacionesExistentes' => $cantidadRelacionesExistentes,
            'relacionesInexistentes' => $cantidadRelacionesInexistentes,
            'filasOmitidas' => isset($this->estadisticas['filasOmitidas']) ? $this->estadisticas['filasOmitidas'] : 0
        ];
        $this->analisisRealizado = true;
    }
    public function abrirCrearPadre()
    {
        $this->modoEdicionPadre = false;
        $this->IdPadreSeleccionado = null;
        $clasePadre = $this->configuracionModelos[$this->modeloPadreSeleccionado];
        $instancia = new $clasePadre();
        $columnas = Schema::getColumnListing($instancia->getTable());
        $this->datosFormularioPadre = [];
        foreach ($columnas as $columna) {
            $this->datosFormularioPadre[$columna] = null;
        }
        $this->mostrarModalPadre = true;
    }
    public function abrirEditarPadre($id)
    {
        $this->modoEdicionPadre = true;
        $this->IdPadreSeleccionado = $id;
        $clasePadre = $this->configuracionModelos[$this->modeloPadreSeleccionado];
        $registro = $clasePadre::findOrFail($id);
        $this->datosFormularioPadre = $registro->toArray();
        $this->mostrarModalPadre = true;
    }
    public function guardarPadre()
    {
        $clasePadre = $this->configuracionModelos[$this->modeloPadreSeleccionado];
        $instancia = new $clasePadre();
        $pk = $instancia->getKeyName();
        $datosAGuardar = collect($this->datosFormularioPadre)->forget($pk)->toArray();
        if ($this->modoEdicionPadre) {
            $registro = $clasePadre::findOrFail($this->IdPadreSeleccionado);
            $registro->forceFill($datosAGuardar)->save();
            session()->flash('mensaje', 'Registro de tabla padre actualizado.');
        } else {
            $nuevoRegistro = new $clasePadre();
            if (isset($this->datosFormularioPadre[$pk]) && !empty($this->datosFormularioPadre[$pk])) {
                $nuevoRegistro->forceFill($this->datosFormularioPadre)->save();
            } else {
                $nuevoRegistro->forceFill($datosAGuardar)->save();
            }
            session()->flash('mensaje', 'Registro de tabla padre creado.');
        }
        $this->mostrarModalPadre = false;
        $this->analizar();
    }
    public function abrirCrearHijo($IdPadre = null)
    {
        $this->modoEdicionHijo = false;
        $this->IdHijoSeleccionado = null;
        $claseHijo = $this->configuracionModelos[$this->modeloHijoSeleccionado];
        $instancia = new $claseHijo();
        $columnas = Schema::getColumnListing($instancia->getTable());
        $this->datosFormularioHijo = [];
        foreach ($columnas as $columna) {
            $this->datosFormularioHijo[$columna] = null;
        }
        if ($IdPadre) {
            $this->datosFormularioHijo[$this->campoRelacionSeleccionado] = $IdPadre;
        }
        $this->mostrarModalHijo = true;
    }
    public function abrirEditarHijo($id)
    {
        $this->modoEdicionHijo = true;
        $this->IdHijoSeleccionado = $id;
        $claseHijo = $this->configuracionModelos[$this->modeloHijoSeleccionado];
        $registro = $claseHijo::findOrFail($id);
        $this->datosFormularioHijo = $registro->toArray();
        $this->mostrarModalHijo = true;
    }
    public function guardarHijo()
    {
        $claseHijo = $this->configuracionModelos[$this->modeloHijoSeleccionado];
        if ($this->modoEdicionHijo) {
            $registro = $claseHijo::findOrFail($this->IdHijoSeleccionado);
            $registro->forceFill($this->datosFormularioHijo)->save();
            session()->flash('mensaje', 'Registro de tabla hija actualizado.');
        } else {
            $nuevoRegistro = new $claseHijo();
            $nuevoRegistro->forceFill($this->datosFormularioHijo)->save();
            session()->flash('mensaje', 'Registro de tabla hija creado.');
        }
        $this->mostrarModalHijo = false;
        $this->analizar();
    }
    public function prepararEliminar($id, $tipo)
    {
        $this->registroAEliminar = $id;
        $this->tipoRegistroAEliminar = $tipo;
        $this->tablasReferenciandoAlerta = [];
        $nombreTabla = '';
        if ($tipo === 'padre') {
            $clase = $this->configuracionModelos[$this->modeloPadreSeleccionado];
            $instancia = new $clase();
            $nombreTabla = $instancia->getTable();
        } else {
            $clase = $this->configuracionModelos[$this->modeloHijoSeleccionado];
            $instancia = new $clase();
            $nombreTabla = $instancia->getTable();
        }
        $baseDatos = DB::connection()->getDatabaseName();
        $referencias = DB::select("
            SELECT TABLE_NAME, COLUMN_NAME, CONSTRAINT_NAME
            FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE
            WHERE REFERENCED_TABLE_SCHEMA = ?
              AND REFERENCED_TABLE_NAME = ?
        ", [$baseDatos, $nombreTabla]);
        foreach ($referencias as $ref) {
            $conteo = DB::table($ref->TABLE_NAME)->where($ref->COLUMN_NAME, $id)->count();
            if ($conteo > 0) {
                $this->tablasReferenciandoAlerta[] = [
                    'tabla' => $ref->TABLE_NAME,
                    'columna' => $ref->COLUMN_NAME,
                    'registros' => $conteo
                ];
            }
        }
        $this->dispatch('abrirModalConfirmacionEliminar');
    }
    public function ejecutarEliminar()
    {
        if (!$this->registroAEliminar) {
            return;
        }
        try {
            if ($this->tipoRegistroAEliminar === 'padre') {
                $clase = $this->configuracionModelos[$this->modeloPadreSeleccionado];
            } else {
                $clase = $this->configuracionModelos[$this->modeloHijoSeleccionado];
            }
            $registro = $clase::findOrFail($this->registroAEliminar);
            $registro->delete();
            session()->flash('mensaje', 'Registro eliminado correctamente de forma física.');
        } catch (\Exception $e) {
            session()->flash('error', 'Error de restricción de base de datos: No se pudo eliminar el registro debido a dependencias activas.');
        }
        $this->registroAEliminar = null;
        $this->tipoRegistroAEliminar = '';
        $this->tablasReferenciandoAlerta = [];
        $this->dispatch('cerrarModalConfirmacionEliminar');
        $this->analizar();
    }
    public function eliminarHuerfanos()
    {
        if (!$this->analisisRealizado) {
            return;
        }
        $clasePadre = $this->configuracionModelos[$this->modeloPadreSeleccionado];
        $claseHijo = $this->configuracionModelos[$this->modeloHijoSeleccionado];
        $padreInstancia = new $clasePadre();
        $hijoInstancia = new $claseHijo();
        $tablaHijo = $hijoInstancia->getTable();
        $clavePrimariaPadre = $padreInstancia->getKeyName();
        $fkHijo = $this->campoRelacionSeleccionado;
        $registrosAEliminar = $clasePadre::whereNotIn($clavePrimariaPadre, function ($query) use ($tablaHijo, $fkHijo) {
            $query->select($fkHijo)->from($tablaHijo)->whereNotNull($fkHijo);
        })->get();
        $eliminados = 0;
        $omitidos = 0;
        foreach ($registrosAEliminar as $registro) {
            try {
                $registro->delete();
                $eliminados++;
            } catch (\Exception $e) {
                $omitidos++;
            }
        }
        $this->analizar();
        $this->estadisticas['filasOmitidas'] = $omitidos;
        if ($omitidos > 0) {
            session()->flash('mensaje', "Proceso completado. Se eliminaron {$eliminados} registros y se omitieron {$omitidos} debido a relaciones activas en otras tablas.");
        } else {
            session()->flash('mensaje', 'Registros huérfanos eliminados correctamente.');
        }
    }
    public function render()
    {
        $arbolPadres = collect();
        if ($this->analisisRealizado && !empty($this->campoRelacionSeleccionado)) {
            $clasePadre = $this->configuracionModelos[$this->modeloPadreSeleccionado];
            $claseHijo = $this->configuracionModelos[$this->modeloHijoSeleccionado];
            $padreInstancia = new $clasePadre();
            $hijoInstancia = new $claseHijo();
            $clavePrimariaPadre = $padreInstancia->getKeyName();
            $fkHijo = $this->campoRelacionSeleccionado;
            $consultaPadre = $clasePadre::query();
            if (!empty($this->keyWord)) {
                $termino = '%' . $this->keyWord . '%';
                $consultaPadre->where(function ($query) use ($clavePrimariaPadre, $termino, $claseHijo, $fkHijo) {
                    $query->where($clavePrimariaPadre, 'LIKE', $termino);
                    $camposPadre = !empty($this->camposPadreSeleccionados) 
                        ? $this->camposPadreSeleccionados 
                        : [strtolower($this->modeloPadreSeleccionado), 'name', 'nombre', 'descripcion', 'clase', 'casa', 'titulo', 'codigo'];
                    foreach ($camposPadre as $campo) {
                        if (Schema::hasColumn($query->getModel()->getTable(), $campo)) {
                            $query->orWhere($campo, 'LIKE', $termino);
                        }
                    }
                    $query->orWhereIn($clavePrimariaPadre, function ($subquery) use ($claseHijo, $fkHijo, $termino) {
                        $hijoInstanciaInterna = new $claseHijo();
                        $subquery->select($fkHijo)
                            ->from($hijoInstanciaInterna->getTable())
                            ->where(function ($q) use ($hijoInstanciaInterna, $termino) {
                                $q->where($hijoInstanciaInterna->getKeyName(), 'LIKE', $termino);
                                $camposHijo = !empty($this->camposHijoSeleccionados) 
                                    ? $this->camposHijoSeleccionados 
                                    : [strtolower($this->modeloHijoSeleccionado), 'name', 'nombre', 'descripcion', 'clase', 'casa', 'titulo', 'codigo'];
                                foreach ($camposHijo as $campoHijo) {
                                    if (Schema::hasColumn($hijoInstanciaInterna->getTable(), $campoHijo)) {
                                        $q->orWhere($campoHijo, 'LIKE', $termino);
                                    }
                                }
                            });
                    });
                });
            }
            foreach ($this->camposPadreSeleccionados as $campoPadre) {
                $consultaPadre->orderBy($campoPadre, 'asc');
            }
            $arbolPadres = $consultaPadre->paginate(30);
            foreach ($arbolPadres as $padre) {
                $coincidePadre = false;
                if (!empty($this->keyWord)) {
                    $termino = '%' . $this->keyWord . '%';
                    if (stripos((string)$padre->getAttribute($clavePrimariaPadre), $this->keyWord) !== false) {
                        $coincidePadre = true;
                    }
                    if (!$coincidePadre) {
                        $camposPadre = !empty($this->camposPadreSeleccionados) 
                            ? $this->camposPadreSeleccionados 
                            : [strtolower($this->modeloPadreSeleccionado), 'name', 'nombre', 'descripcion', 'clase', 'casa', 'titulo', 'codigo'];
                        foreach ($camposPadre as $campo) {
                            if (Schema::hasColumn($padre->getTable(), $campo) && stripos((string)$padre->getAttribute($campo), $this->keyWord) !== false) {
                                $coincidePadre = true;
                                break;
                            }
                        }
                    }
                }
                $consultaHijos = $claseHijo::where($fkHijo, $padre->getAttribute($clavePrimariaPadre));
                if (!empty($this->keyWord) && !$coincidePadre) {
                    $termino = '%' . $this->keyWord . '%';
                    $consultaHijos->where(function ($query) use ($termino) {
                        $query->where($query->getModel()->getKeyName(), 'LIKE', $termino);
                        $camposHijo = !empty($this->camposHijoSeleccionados) 
                            ? $this->camposHijoSeleccionados 
                            : [strtolower($this->modeloHijoSeleccionado), 'name', 'nombre', 'descripcion', 'clase', 'casa', 'titulo', 'codigo'];
                        foreach ($camposHijo as $campoHijo) {
                            if (Schema::hasColumn($query->getModel()->getTable(), $campoHijo)) {
                                $query->orWhere($campoHijo, 'LIKE', $termino);
                            }
                        }
                    });
                }
                foreach ($this->camposHijoSeleccionados as $campoHijo) {
                    $consultaHijos->orderBy($campoHijo, 'asc');
                }
                $padre->hijosDirectosCargados = $consultaHijos->get();
            }
        }
        return view('livewire.homologacion.view', [
            'opcionesModelos' => array_keys($this->configuracionModelos),
            'arbolPadres' => $arbolPadres
        ]);
    }
}