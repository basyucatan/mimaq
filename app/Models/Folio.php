<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
class Folio extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = 'folios';
    protected $fillable = ['IdLote','IdEstilo','jobStyle','cantidad','totalBandejas',
        'abreviatura','productoFinal','precioU','fechaVen','estatus','alertas','adicionales'];
    protected $casts = ['alertas' => 'array','adicionales' => 'array'];
    protected static function booted()
    {
        static::creating(function ($folio) {
            $folio->periodo = now()->tz('America/Mexico_City')->format('ym');
            $ultimoConsecutivo = static::where('periodo', $folio->periodo)->max('consecutivoMensual');
            $folio->consecutivoMensual = $ultimoConsecutivo ? $ultimoConsecutivo + 1 : 1;
        });
    }
    public function precioU()
    {
        if ($this->cantidad <= 0) { return 1; }
        $this->loadMissing('foliosmats.facImportsDet');
        $costoTotal = $this->foliosmats->sum(function ($foliosmat) {
            $precioImport = $foliosmat->facImportsDet->precioU ?? 0;
            return ($foliosmat->cantidad * $precioImport) / $this->cantidad;
        });
        return $costoTotal + 1;
    }
    public function getAscendenciaAttribute()
    {
        $this->loadMissing('lote.orden.cliente', 'estilo');
        $lote = $this->lote;
        $orden = $lote ? $lote->orden : null;
        $cliente = $orden ? $orden->cliente : null;
        $datos = array_filter([
            $cliente->cliente ?? null,
            $lote->lote ?? null,
            $this->estilo->estilo ?? null,
            $this->abreviatura ?? null,
            $this->ktCol ?? null
        ]);
        return implode('📌', $datos);
    }
    public function getKtColAttribute()
    {
        $adicionales = $this->adicionales ?? [];
        $kt = $adicionales['kt'] ?? '';
        $color = $adicionales['color'] ?? '';
        $vKt = is_array($kt) ? ($kt['valor'] ?? '') : $kt;
        $vCol = is_array($color) ? ($color['valor'] ?? '') : $color;
        return trim("$vKt $vCol");
    }
    public function getCodigoFolioAttribute()
    {
        return $this->periodo . '-' . $this->consecutivoMensual;
    }
    public function definirProducto($IdEstilo, $cantidad, $kt = null, $color = null)
    {
        $this->IdEstilo = $IdEstilo;
        $this->cantidad = $cantidad;
        $vKt = is_array($kt) ? ($kt['valor'] ?? '') : ($kt ?? '');
        $vCol = is_array($color) ? ($color['valor'] ?? '') : ($color ?? '');
        if (!$this->estilo) return;
        $this->jobStyle = (string)$this->estilo->estilo;
        $this->adicionales = array_merge($this->adicionales ?? [], ['composicion' => $this->composicionBase()]);
        $comp = $this->adicionales['composicion'];
        $this->productoFinal = (string)$this->producto($comp, $vKt, $vCol);
        $this->abreviatura = (string)$this->abreviatura($comp);
        $this->totalBandejas = $this->calcularBandejas($cantidad);
    }
    private function abreviatura($comp)
    {
        if (empty($comp)) return '';
        $prioridad = [1 => 1, 2 => 2, 7 => 3, 6 => 4];
        $idsMateriales = array_keys($comp);
        $materiales = Material::with('clase.tipo')
            ->whereIn('id', $idsMateriales)
            ->get()
            ->keyBy('id');
        return collect($comp)->map(function($c, $IdMat) use ($materiales) {
            $mat = $materiales->get($IdMat);
            return [
                'cant' => $c['cantidad'], 
                'abv' => $mat->abreviatura ?? 'S/A', 
                'tipo' => $mat->clase->tipo->id ?? null
            ];
        })->sortBy(fn($i) => $prioridad[$i['tipo']] ?? 99)->reduce(function ($carry, $i) {
            $formato = (floor($i['cant']) == $i['cant']) ? number_format($i['cant'], 0) : number_format($i['cant'], 2);
            return $carry . ($carry ? '|' : '') . "{$formato}{$i['abv']}";
        }, '');
    }
    private function producto($comp, $kt = '', $color = '')
    {
        $nombreBase = '';
        $idsCasting = [];
        foreach ($comp as $IdMat => $d) {
            if (($d['idTipo'] ?? null) == 1 || ($d['tipo'] ?? '') == 'CASTING') {
                $idsCasting[] = $IdMat;
            }
        }
        if (!empty($idsCasting)) {
            $material = Material::whereIn('id', $idsCasting)->first();
            if ($material) {
                $nombreBase = $material->material ?? '';
            }
        }
        if (!$nombreBase) {
            $nombreBase = $this->estilo->descripcion ?? 'PRODUCTO TERMINADO';
        }
        return strtoupper(trim("$nombreBase $kt $color"));
    }
    public function composicionBase()
    {
        return Estilosdet::with('material.clase.tipo')
            ->where('IdEstilo', $this->IdEstilo)
            ->get()
            ->mapWithKeys(fn($d) => [
                (string)$d->IdMaterial => [
                    'cantidad' => $d->cantidad, 
                    'tipo' => $d->material->clase->tipo->tipo ?? 'n/a', 
                    'idTipo' => $d->material->clase->tipo->id ?? null
                ]
            ])->toArray();
    }
    public function calcularBandejas($cant)
    {
        $max = Cache::remember('setting_cant_bandeja', 3600, function () {
            $path = base_path('settings.json');
            if (file_exists($path)) {
                $config = json_decode(file_get_contents($path), true);
                return $config['Parametros'][0]['cantBandeja'] ?? 10;
            }
            return 10;
        });
        return ceil($cant / $max);
    }
    public function renumerarBandejas()
    {
        $bandejas = $this->bandejas()->orderBy('id')->get();
        foreach ($bandejas as $index => $bandeja) {
            $bandeja->update(['numeroBandeja' => $index + 1]);
        }
    }
    public function actualizarEstadoIntegridad()
    {
        $this->renumerarBandejas();
        $totalBandejas = $this->bandejas()->count();
        $sumaBandejas = $this->bandejas()->sum('cantidad');
        $esIntegro = ($sumaBandejas == $this->cantidad);
        $adicionales = $this->adicionales ?? [];
        $adicionales['integro'] = $esIntegro;
        $this->update([
            'totalBandejas' => $totalBandejas,
            'adicionales' => $adicionales
        ]);
        return $esIntegro;
    }
    public function agregarBandeja($cantidad)
    {
        if ($this->estatus !== 'abierto') return null;
        return DB::transaction(function () use ($cantidad) {
            $siguienteNumero = $this->bandejas()->count() + 1;
            $idProcesoDistribucion = DB::table('procesos')->where('proceso', '00 DISTRIBUCION')->value('id');
            $bandeja = $this->bandejas()->create([
                'numeroBandeja' => $siguienteNumero,
                'cantidad' => $cantidad,
                'castingIni' => 0,
                'castingFin' => 0,
                'piedrasG' => 0,
                'diamantesG' => 0,
                'miscG' => 0,
                'IdProcesoActual' => $idProcesoDistribucion,
                'enBoveda' => false,
                'habilitada' => true,
                'estatus' => 'proceso'
            ]);
            $this->actualizarEstadoIntegridad();
            $this->reprocesarEstructuraMovimientos();
            return $bandeja;
        });
    }
    public function crearBandejasIniciales($cantMaxBandeja)
    {
        $piezasTotales = $this->cantidad;
        $nBandejas = ($this->totalBandejas > 0) ? (int)$this->totalBandejas : (int)ceil($piezasTotales / $cantMaxBandeja);
        if ($nBandejas <= 0) return;
        DB::transaction(function () use ($piezasTotales, $nBandejas) {
            $idProcesoDistribucion = DB::table('procesos')->where('proceso', '00 DISTRIBUCION')->value('id');
            $piezasRestantes = $piezasTotales;
            $piezasBase = (int)intdiv($piezasTotales, $nBandejas);
            for ($i = 1; $i <= $nBandejas; $i++) {
                $piezasBandeja = ($i === $nBandejas) ? $piezasRestantes : $piezasBase;
                $this->bandejas()->create([
                    'numeroBandeja' => $i,
                    'cantidad' => $piezasBandeja,
                    'castingIni' => 0,
                    'castingFin' => 0,
                    'piedrasG' => 0,
                    'diamantesG' => 0,
                    'miscG' => 0,
                    'IdProcesoActual' => $idProcesoDistribucion,
                    'enBoveda' => false,
                    'habilitada' => true,
                    'estatus' => 'proceso'
                ]);
                $piezasRestantes -= $piezasBandeja;
            }
            $this->actualizarEstadoIntegridad();
        });
    }
    public function reprocesarEstructuraMovimientos()
    {
        DB::transaction(function () {
            $idDeptoBoveda = DB::table('deptos')->where('depto', '0 BOVEDA')->value('id');
            $controlEmpleadoId = DB::table('empleados')->where('numero', 999)->value('id');
            $referenciasPrevias = Referenciasmov::where('tipoDoc', 'folio')
                ->where('IdDoc', $this->id)
                ->where('tipo', 'salida')
                ->get();
            foreach ($referenciasPrevias as $ref) {
                $existencia = Existencia::where('IdFacImportsDet', $ref->IdFacImportsDet)
                    ->where('IdDepto', $idDeptoBoveda)
                    ->first();
                if ($existencia) {
                    $existencia->increment('cantidad', $ref->cantidad);
                    $existencia->increment('pesoG', $ref->pesoG);
                }
                $ref->delete();
            }
            $this->foliosmats()->update(['integrado' => false]);
            $idProcesoDistribucion = DB::table('procesos')->where('proceso', '00 DISTRIBUCION')->value('id');
            $idProcesoValidacion = DB::table('procesos')->where('proceso', '05 VALIDACION')->value('id');
            $materialesSurtir = $this->foliosmats()
                ->whereNotNull('IdFacImportsDet')
                ->where('integrado', false)
                ->get();
            foreach ($materialesSurtir as $item) {
                $existencia = Existencia::where('IdFacImportsDet', $item->IdFacImportsDet)
                    ->where('IdDepto', $idDeptoBoveda)
                    ->first();
                if ($existencia && $existencia->cantidad >= $item->cantidad) {
                    $existencia->decrement('cantidad', $item->cantidad);
                    $existencia->decrement('pesoG', $item->pesoG);
                    Referenciasmov::create([
                        'IdFacImportsDet' => $item->IdFacImportsDet,
                        'IdMaterial' => $item->IdMaterial,
                        'IdDeptoOri' => $idDeptoBoveda,
                        'IdDeptoDes' => 2,
                        'tipo' => 'salida',
                        'cantidad' => $item->cantidad,
                        'pesoG' => $item->pesoG,
                        'tipoDoc' => 'folio',
                        'IdDoc' => $this->id,
                        'glosa' => "Salida a Folio #{$this->id}",
                        'estatus' => 'cerrado'
                    ]);
                    $item->update(['integrado' => true]);
                }
            }
            $salidasEfectivas = Referenciasmov::with('Material.Clase.Tipo')
                ->where('tipoDoc', 'folio')
                ->where('IdDoc', $this->id)
                ->where('tipo', 'salida')
                ->get();
            $pesoMetal = 0; $pesoPiedras = 0; $pesoDiamantes = 0; $pesoMisc = 0;
            foreach ($salidasEfectivas as $mov) {
                $tipo = $mov->Material->Clase->IdTipo ?? null;
                if ($tipo == 1) {
                    $pesoMetal += $mov->pesoG;
                } elseif ($tipo == 2) {
                    $pesoDiamantes += $mov->pesoG;
                } elseif ($tipo == 7) {
                    $pesoPiedras += $mov->pesoG;
                } elseif ($tipo == 6) {
                    $pesoMisc += $mov->pesoG;
                }
            }
            $pesoTotalMateriales = $pesoMetal + $pesoPiedras + $pesoDiamantes + $pesoMisc;
            $bandejasActuales = $this->bandejas;
            $piezasTotales = $this->cantidad;
            $ahora = now()->tz('America/Mexico_City');
            $userId = auth()->id() ?? 1;
            $idsBandejas = $bandejasActuales->pluck('id');
            if ($idsBandejas->isNotEmpty()) {
                Bandejasmov::whereIn('IdBandeja', $idsBandejas)->delete();
            }
            foreach ($bandejasActuales as $bandeja) {
                $factor = ($piezasTotales > 0) ? ($bandeja->cantidad / $piezasTotales) : 0;
                $pesoCalculado = round($pesoTotalMateriales * $factor, 4);
                $bandeja->update([
                    'castingIni' => round($pesoMetal * $factor, 4),
                    'castingFin' => round($pesoMetal * $factor, 4),
                    'piedrasG' => round($pesoPiedras * $factor, 4),
                    'diamantesG' => round($pesoDiamantes * $factor, 4),
                    'miscG' => round($pesoMisc * $factor, 4),
                    'IdProcesoActual' => $idProcesoDistribucion,
                    'enBoveda' => false,
                    'habilitada' => true,
                    'estatus' => 'proceso'
                ]);
                Bandejasmov::create([
                    'IdBandeja' => $bandeja->id,
                    'IdProceso' => $idProcesoDistribucion,
                    'IdProcesoSig' => $idProcesoValidacion,
                    'IdUser' => $userId,
                    'IdEmpleado' => $controlEmpleadoId,
                    'IdRegistrador' => $controlEmpleadoId,
                    'pesoEntrada' => $pesoCalculado,
                    'pesoSalida' => $pesoCalculado,
                    'fechaHEntrada' => $ahora,
                    'fechaHSalida' => $ahora
                ]);
            }
        });
    }
    public function corregirIntegridad()
    {
        if ($this->estatus !== 'abierto') return false;
        return DB::transaction(function () {
            $bandejas = $this->bandejas;
            $totalBandejas = $bandejas->count();
            if ($totalBandejas === 0) return false;
            $sumaActual = $bandejas->sum('cantidad');
            $piezasTotales = $this->cantidad;
            if ($sumaActual !== $piezasTotales) {
                $diferencia = $piezasTotales - $sumaActual;
                $ultimaBandeja = $bandejas->last();
                $nuevaCantidadUltima = $ultimaBandeja->cantidad + $diferencia;
                if ($nuevaCantidadUltima > 0) {
                    $ultimaBandeja->update(['cantidad' => $nuevaCantidadUltima]);
                } else {
                    $piezasBase = (int)intdiv($piezasTotales, $totalBandejas);
                    $piezasRestantes = $piezasTotales;
                    foreach ($bandejas as $i => $b) {
                        $cant = ($i === ($totalBandejas - 1)) ? $piezasRestantes : $piezasBase;
                        $b->update(['cantidad' => $cant]);
                        $piezasRestantes -= $cant;
                    }
                }
            }
            $this->actualizarEstadoIntegridad();
            $this->reprocesarEstructuraMovimientos();
            return true;
        });
    }
    public function bandejas() { return $this->hasMany(Bandeja::class, 'IdFolio'); }
    public function estilo() { return $this->belongsTo('App\Models\Estilo', 'IdEstilo'); }
    public function foliosmats() { return $this->hasMany('App\Models\Foliosmat', 'IdFolio', 'id'); }
    public function lote() { return $this->belongsTo('App\Models\Lote', 'IdLote'); }
}