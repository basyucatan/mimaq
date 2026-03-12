<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ocompra extends Model
{
	use HasFactory;
	
    public $timestamps = false;

    protected $table = 'ocompras';

    protected $fillable = ['IdDivision','IdProveedor','IdCuentaProv','IdUser','IdAprobo',
        'IdObra','IdCondPago', 'subtotal','IdCondFlete','fechaHSol','fechaERec',
        'porDescuento','concepto','estatus','adicionales'];
	
    public function getTotalAttribute()
    {
        $descuento = $this->subtotal * ($this->porDescuento / 100);
        return round($this->subtotal - $descuento, 2);
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
