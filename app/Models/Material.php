<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
	use HasFactory;
	
    public $timestamps = false;

    protected $table = 'materials';

    protected $fillable = ['IdClase','IdUnidad','IdUnidadP','material','materialI','materialFiscal','abreviatura','adicionales'];
    protected $casts = [
        'adicionales' => 'array'
    ];
    public function getPesoG($valor)
    {
        $factor = $this->unidadP ? $this->unidadP->factorC : 1;
        return $valor * $factor;
    }	
    public function clase()
    {
        return $this->hasOne('App\Models\Clase', 'id', 'IdClase');
    }
    public function estilosdets()
    {
        return $this->hasMany('App\Models\Estilosdet', 'IdMaterial', 'id');
    }
    
    public function facimportsdets()
    {
        return $this->hasMany('App\Models\Facimportsdet', 'IdMaterial', 'id');
    }
    
    public function lotesfolios()
    {
        return $this->hasMany('App\Models\Lotesfolio', 'IdMaterial', 'id');
    }
    
    public function lotesmats()
    {
        return $this->hasMany('App\Models\Lotesmat', 'IdMaterial', 'id');
    }
    
    public function unidad()
    {
        return $this->hasOne('App\Models\Unidad', 'id', 'IdUnidad');
    }
    
    public function unidadP()
    {
        return $this->hasOne('App\Models\Unidad', 'id', 'IdUnidadP');
    }
    
}
