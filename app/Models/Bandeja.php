<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bandeja extends Model
{
	use HasFactory;
	
    public $timestamps = false;

    protected $table = 'bandejas';

    protected $fillable = ['IdFolio','IdFacturaExport','cantidad','castingIni','castingFin',
        'piedrasG','diamantesG','miscG','IdProcesoActual','enBoveda','habilitada','estatus','adicionales'];
    protected $casts = [
        'adicionales' => 'array'
    ];
    public function ultimoMovimiento()
    {
        return $this->hasOne(Bandejasmov::class, 'IdBandeja', 'id')->latestOfMany();
    }
    protected static function booted()
    {
        static::creating(function ($bandeja) {
            $ultimaBandeja = static::where('IdFolio', $bandeja->IdFolio)->max('numeroBandeja');
            $bandeja->numeroBandeja = $ultimaBandeja ? $ultimaBandeja + 1 : 1;
        });
    }
    public function folio()
    {
        return $this->belongsTo(Folio::class, 'IdFolio');
    }
    public function getCodigoBandejaAttribute()
    {
        return $this->folio->codigoFolio . '-' . $this->numeroBandeja;
    }
    public function bandejasmovs()
    {
        return $this->hasMany('App\Models\Bandejasmov', 'IdBandeja', 'id');
    }
    
    public function factura()
    {
        return $this->hasOne('App\Models\Factura', 'id', 'IdFacturaExport');
    }
    public function proceso()
    {
        return $this->hasOne('App\Models\Proceso', 'id', 'IdProcesoActual');
    }
    
}
