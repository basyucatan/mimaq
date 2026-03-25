<?php 

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Util;
class Movinventario extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = 'movinventarios';
    protected $appends = ['sentido', 'cantidadAbsoluta', 'fechaHora'];
    protected $fillable = [
        'IdUserOri', 'IdUserDes', 'tipo', 'IdMatCosto', 'IdBodega', 'IdDepto', 
        'IdBodegaOri', 'IdDeptoOri', 'fechaH', 'cantidad', 'valorU', 'dimensiones', 'adicionales'
    ];
    protected $casts = ['adicionales' => 'array'];
    public function userOri(){return $this->belongsTo('App\Models\User', 'IdUserOri');}
    public function userDes(){return $this->belongsTo('App\Models\User', 'IdUserDes');}
    public function materialscosto(){return $this->belongsTo('App\Models\Materialscosto', 'IdMatCosto');}
    public function depto(){return $this->belongsTo('App\Models\Depto', 'IdDepto');}
    public function deptoOri(){return $this->belongsTo('App\Models\Depto', 'IdDeptoOri');}
    public function bodega(){return $this->belongsTo('App\Models\Divsbodega', 'IdBodega');}
    public function bodegaOri(){return $this->belongsTo('App\Models\Divsbodega', 'IdBodegaOri');}

    public function getSentidoAttribute(){return $this->cantidad >= 0 ? 'Entrada' : 'Salida';}
    public function getCantidadAbsolutaAttribute(){return abs($this->cantidad);}
    public function getFechaHoraAttribute(){return (Util::formatFecha($this->fechaH, 'CortaDhm'));}

    public function getValoresAttribute()
    {
        $matCosto = $this->materialscosto ?? null;
        $IdUMaterial = $matCosto?->material?->IdUnidad ?? 1;
        $valorUReal = $this->valorU ?? 0;
        if ($matCosto && $matCosto->barra) {
            if ($IdUMaterial == 3) {
                $longitudBarra = floatval($matCosto->barra->longitud ?? 0);
                $valorUReal = $this->valorU * $longitudBarra / 1000;
            }
        }
        $valorURealMXN = $valorUReal * ($matCosto?->Moneda?->tipoCambio ?? 1);
        return [
            'unidad' => $matCosto?->Unidad,
            'valorUReal' => $valorUReal,
            'valorURealMXN' => $valorURealMXN,
        ];
    }

public function scopePorUbicacion($query, $IdMatCosto, $IdBodega, $IdDepto)
{
    return $query->where('IdMatCosto', $IdMatCosto)
        ->where('IdBodega', $IdBodega)
        ->where('IdDepto', $IdDepto);
}
public function scopePorRangoFecha($query, $fechaIni, $fechaFin)
{
    if ($fechaIni && $fechaFin) {
        return $query->whereBetween('fechaH', [$fechaIni . ' 00:00:00', $fechaFin . ' 23:59:59']);
    }
    return $query;
}
public static function obtenerKardex($IdMatCosto, $IdBodega, $IdDepto, $fechaIni = null, $fechaFin = null)
{
    $queryBase = self::porUbicacion($IdMatCosto, $IdBodega, $IdDepto);
    $paginados = $queryBase->clone()
        ->porRangoFecha($fechaIni, $fechaFin)
        ->with(['depto', 'bodega', 'deptoOri', 'bodegaOri', 'userOri', 'userDes'])
        ->orderBy('fechaH', 'desc')
        ->paginate(8);
    if ($paginados->isEmpty()) return $paginados;
    $ultimoDeLaPagina = $paginados->last();
    $saldoAnterior = (float)$queryBase->clone()
        ->where('fechaH', '<', $ultimoDeLaPagina->fechaH)
        ->orWhere(function($q) use ($ultimoDeLaPagina, $IdMatCosto, $IdBodega, $IdDepto) {
            $q->where('fechaH', $ultimoDeLaPagina->fechaH)
                ->where('id', '<', $ultimoDeLaPagina->id)
                ->porUbicacion($IdMatCosto, $IdBodega, $IdDepto);
        })
        ->sum('cantidad');
    $items = $paginados->getCollection()->reverse();
    foreach ($items as $mov) {
        $saldoAnterior += (float)$mov->cantidad;
        $mov->saldoCalculado = $saldoAnterior;
        \App\Services\MovInvService::Etiquetas($mov);
    }
    $paginados->setCollection($items->reverse());
    return $paginados;
}
}