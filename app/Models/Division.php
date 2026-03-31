<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Division extends Model
{
	use HasFactory;
	
    public $timestamps = false;

    protected $table = 'divisions';

    protected $fillable = ['IdNegocio','division', 'adicionales'];
	protected $casts = ['adicionales' => 'array'];
    
    public function divsbodegas()
    {
        return $this->hasMany('App\Models\Divsbodega', 'IdDivision', 'id');
    }
    
    public function divscajas()
    {
        return $this->hasMany('App\Models\Divscaja', 'IdDivision', 'id');
    }
    
    public function lineas()
    {
        return $this->hasMany('App\Models\Linea', 'IdDivision', 'id');
    }
    
    public function modelospres()
    {
        return $this->hasMany('App\Models\Modelospre', 'IdDivision', 'id');
    }
    
    public function negocio()
    {
        return $this->hasOne('App\Models\Negocio', 'id', 'IdNegocio');
    }
    
}
