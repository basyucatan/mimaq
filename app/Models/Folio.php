<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class Folio extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = 'folios';
    protected $fillable = ['IdLote','IdEstilo','jobStyle','cantidad','totalBandejas','abreviatura','productoFinal','precioU','fechaVen','estatus','alertas','adicionales'];
    protected $casts = ['alertas' => 'array','adicionales' => 'array'];
    public function precioU()
    {
        if ($this->cantidad <= 0) {return 1;}
        $costoTotal = $this->foliosmats->sum(function ($foliosmat) {
            $precioImport = $foliosmat->facImportsDet->precioU ?? 0;
            return ($foliosmat->cantidad * $precioImport) / $this->cantidad;
        });
        return $costoTotal + 1;
    }
    protected static function booted()
    {
        static::creating(function ($folio) {
            $folio->periodo = now()->format('ym');
            $ultimoConsecutivo = static::where('periodo', $folio->periodo)->max('consecutivoMensual');
            $folio->consecutivoMensual = $ultimoConsecutivo ? $ultimoConsecutivo + 1 : 1;
        });
    }
    public function getAscendenciaAttribute()
    {
        $lote = $this->lote;
        $orden = $lote ? $lote->orden : null;
        $cliente = $orden ? $orden->cliente : null;
        $datos = array_filter([
            $cliente->cliente ?? null,
            $orden->orden ?? null,
            $lote->lote ?? null
        ]);
        return implode(' | ', $datos);
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
        $prioridad = [1 => 1, 2 => 2, 7 => 3, 6 => 4];
        return collect($comp)->map(function($c, $IdMat) {
            $mat = Material::with('clase.tipo')->find($IdMat);
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
        foreach ($comp as $IdMat => $d) {
            if (($d['idTipo'] ?? null) == 1 || ($d['tipo'] ?? '') == 'CASTING') {
                $nombreBase = Material::find($IdMat)->material ?? '';
                break;
            }
        }
        if (!$nombreBase) $nombreBase = $this->estilo->descripcion ?? 'PRODUCTO TERMINADO';
        return strtoupper(trim("$nombreBase $kt $color"));
    }
    public function composicionBase()
    {
        return Estilosdet::with('material.clase.tipo')->where('IdEstilo', $this->IdEstilo)->get()->mapWithKeys(fn($d) => [
            (string)$d->IdMaterial => [
                'cantidad' => $d->cantidad, 
                'tipo' => $d->material->clase->tipo->tipo ?? 'n/a', 
                'idTipo' => $d->material->clase->tipo->id ?? null
            ]
        ])->toArray();
    }
    public function calcularBandejas($cant)
    {
        $config = json_decode(file_get_contents(base_path('settings.json')), true);
        $max = $config['Parametros'][0]['cantBandeja'] ?? 10;
        return ceil($cant / $max);
    }
    public function bandejas(){return $this->hasMany(Bandeja::class, 'IdFolio');}
    public function estilo(){return $this->belongsTo('App\Models\Estilo', 'IdEstilo');}
    public function foliosmats(){return $this->hasMany('App\Models\Foliosmat', 'IdFolio', 'id');}
    public function lote(){return $this->belongsTo('App\Models\Lote', 'IdLote');}
}