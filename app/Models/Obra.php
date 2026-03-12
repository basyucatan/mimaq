<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Obra extends Model
{
	use HasFactory;
	
    public $timestamps = false;

    protected $table = 'obras';

    protected $fillable = ['IdEmpresa','obra','gmaps','adicionales'];
	
    public function Cliente()
    {
        return $this->hasOne('App\Models\Empresa', 'id', 'IdEmpresa');
    }
    
    public function presupuestos()
    {
        return $this->hasMany('App\Models\Presupuesto', 'IdObra', 'id');
    }
    
}
