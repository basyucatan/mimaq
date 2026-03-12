<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Empresascuenta extends Model
{
	use HasFactory;
	
    public $timestamps = false;

    protected $table = 'empresascuentas';

    protected $fillable = ['IdEmpresa','banco','cuenta','cuentaClabe','adicionales'];
	
    public function empresa()
    {
        return $this->hasOne('App\Models\Empresa', 'id', 'IdEmpresa');
    }
    
}
