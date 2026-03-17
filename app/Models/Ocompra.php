<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Util;

class Ocompra extends Model
{
    use HasFactory;

    public const EST_EDICION   = 'edicion';
    public const EST_APROBADO  = 'aprobado';
    public const EST_ORDENADO  = 'ordenado';
    public const EST_RECIBIDO  = 'recibido';
    public const EST_CANCELADO = 'cancelado';

    public $timestamps = false;

    protected $table = 'ocompras';

    protected $fillable = [
        'IdDivision','IdProveedor','IdCuentaProv','IdUser','IdAprobo','IdRecibio',
        'fechaRec',
        'IdObra','IdCondPago','subtotal','IdCondFlete','fechaHSol','fechaERec',
        'porDescuento','concepto','estatus','adicionales'
    ];
    protected $casts = [
        'subtotal' => 'decimal:4',
        'porDescuento' => 'decimal:2',
        'adicionales' => 'array'
    ];
    public function puedePasarA($nuevo)
    {
        return match ($this->estatus) {
            self::EST_EDICION   => [self::EST_APROBADO, self::EST_CANCELADO],
            self::EST_APROBADO  => [self::EST_ORDENADO, self::EST_CANCELADO],
            self::EST_ORDENADO  => [self::EST_RECIBIDO, self::EST_CANCELADO],
            self::EST_RECIBIDO  => [],
            self::EST_CANCELADO => [],
            default => [],
        };
    }

    public function cambiarEstatus($nuevo)
    {
        $permitidos = $this->puedePasarA($nuevo);

        if (!in_array($nuevo, $permitidos)) {
            throw new \Exception("Transición no permitida");
        }

        $this->estatus = $nuevo;

        if ($nuevo === self::EST_APROBADO) {
            $this->IdAprobo = auth()->id();
        }

        if ($nuevo === self::EST_RECIBIDO && !$this->fechaRec) {
            $this->fechaRec = now();
            $this->IdRecibio = auth()->id();
        }

        $this->save();
    }

// Dentro de App\Models\Ocompra.php

public function getMontoDescuentoAttribute()
{
    return (float)$this->subtotal * ((float)$this->porDescuento / 100);
}

public function getBaseImponibleAttribute()
{
    return (float)$this->subtotal - $this->monto_descuento;
}

public function getMontoIvaAttribute()
{
    $factorIva = Util::getArrayJS('datosFacturacion')[1]['factorIva'];
    return $this->base_imponible * (float)$factorIva;
}

public function getTotalAttribute()
{
    // El total es la base con descuento + el IVA de esa base
    return $this->base_imponible + $this->monto_iva;
}

    public function division()
    {
        return $this->hasOne('App\Models\Division', 'id', 'IdDivision');
    }

    public function Proveedor()
    {
        return $this->hasOne('App\Models\Empresa', 'id', 'IdProveedor');
    }

    public function Obra()
    {
        return $this->hasOne('App\Models\Obra', 'id', 'IdObra');
    }

    public function Solicito()
    {
        return $this->hasOne('App\Models\User', 'id', 'IdUser');
    }

    public function ocomprasdets()
    {
        return $this->hasMany('App\Models\Ocomprasdet', 'IdOCompra', 'id');
    }
}