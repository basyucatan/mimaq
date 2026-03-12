<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Empresa extends Model
{
	use HasFactory;
	
    public $timestamps = false;

    protected $table = 'empresas';

    protected $fillable = ['IdNegocio','tipo','empresa','direccion',
        'razonSocial', 'rfc',
        'gmaps','telefono','email','adicionales'];
	
    public function empresascontactos()
    {
        return $this->hasMany('App\Models\Empresascontacto', 'IdEmpresa', 'id');
    }
    
    public function empresassucs()
    {
        return $this->hasMany('App\Models\Empresassuc', 'IdEmpresa', 'id');
    }
    
    public function negocio()
    {
        return $this->hasOne('App\Models\Negocio', 'id', 'IdNegocio');
    }
    
    public function obras()
    {
        return $this->hasMany('App\Models\Obra', 'IdEmpresa', 'id');
    }
    
    public function presupuestos()
    {
        return $this->hasMany('App\Models\Presupuesto', 'IdCliente', 'id');
    }
    
}
