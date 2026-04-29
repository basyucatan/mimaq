<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Clase extends Model
{
	use HasFactory;
	
    public $timestamps = false;

    protected $table = 'clases';

    protected $fillable = ['IdAccess','IdTipo','IdArancel','clase','claseI','adicionales'];
    protected $casts = [
        'adicionales' => 'array'
    ];
	
    public function arancel()
    {
        return $this->hasOne('App\Models\Arancel', 'id', 'IdArancel');
    }
   
    public function estiloys()
    {
        return $this->hasMany('App\Models\Estiloy', 'IdClase', 'id');
    }
    
    public function materials()
    {
        return $this->hasMany('App\Models\Material', 'IdClase', 'id');
    }
    
    public function tipo()
    {
        return $this->hasOne('App\Models\Tipo', 'id', 'IdTipo');
    }
    
}
