<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Factura extends Model
{
	use HasFactory;
	
    public $timestamps = true;

    protected $table = 'facturas';

    protected $fillable = ['serie','factura','IdPedimento', 'fecha', 'tipoCambio','estatus','guias','adicionales'];
    protected $casts = [
        'guias' => 'array',
        'adicionales' => 'array'
    ];
    public function getFacturaStrAttribute()
    {
        return $this->serie . '-' . $this->pedimento->regimen . '-' . str_pad($this->factura, 6, '0', STR_PAD_LEFT);
    }
    public static function getConsecutivo($serie = 'A', $regimen = 'IN')
    {
        return static::query()
            ->join('pedimentos', 'facturas.IdPedimento', '=', 'pedimentos.id')
            ->where('facturas.serie', $serie)
            ->where('pedimentos.regimen', $regimen)
            ->max('facturas.factura') + 1;
    }
    public function getNextIdEntradaMex($cantidad)
    {
        $prefijo = $this->factura . '-';
        $registros = $this->facimportsdets()
            ->where('IdEntradaMex', 'LIKE', $prefijo . '%')
            ->pluck('IdEntradaMex');
        $usados = $registros->map(function ($valor) use ($prefijo) {
            return (int) substr($valor, strlen($prefijo));
        })->sort()->values();
        $ids = [];
        $consecutivo = 1;
        while (count($ids) < $cantidad) {
            if (!$usados->contains($consecutivo)) {
                $ids[] = $prefijo . $consecutivo;
            }
            $consecutivo++;
        }
        return $ids;
    }	
public function getTotalAttribute()
{
    return $this->facimportsdets->sum(fn($detalle) => $detalle->cantidad * $detalle->precioU);
}

public function getLimiteFacturaAttribute()
{
    $limite = Util::getParametro('limiteFactura', 100000);
    $valor = $this->adicionales['limiteFactura'] ?? '';
    if ($valor === '' || $valor < $limite) {
        return $limite;
    }
    return $valor;
}

public function getExcedeLimiteAttribute()
{
    return $this->total > $this->limiteFactura;
}    
    public function facimportsdets()
    {
        return $this->hasMany('App\Models\Facimportsdet', 'IdFactura', 'id');
    }
    public function facexportsdets()
    {
        return $this->hasMany('App\Models\Facexportsdet', 'IdFactura', 'id');
    }
public function pedimento()
{
    return $this->belongsTo('App\Models\Pedimento', 'IdPedimento', 'id');
}
}
