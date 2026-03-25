<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
	use HasFactory;
	
    public $timestamps = false;

    protected $table = 'materials';

    protected $fillable = ['IdClase','IdLinea','IdUnidad','IdTipo',
        'referencia','material','foto','KgxMetro','rendimiento','IdUnidadRend','adicionales'];
    protected $casts = ['adicionales' => 'array'];        
public function getFotoUrlAttribute()
{
    if (!$this->foto) return null;
    $marcaNombre = $this->linea->marca->marca ?? 'generico';
    $marcaLimpia = \Illuminate\Support\Str::slug($marcaNombre);
    return asset("storage/materiales/{$marcaLimpia}/{$this->foto}");
}
    public function clase()
    {
        return $this->hasOne('App\Models\Clase', 'id', 'IdClase');
    }
    
    public function linea()
    {
        return $this->hasOne('App\Models\Linea', 'id', 'IdLinea');
    }
    
    public function Materialscostos()
    {
        return $this->hasMany('App\Models\Materialscosto', 'IdMaterial', 'id');
    }
    
    public function modelopremats()
    {
        return $this->hasMany('App\Models\Modelopremat', 'IdMaterial', 'id');
    }
    
    public function modelosmats()
    {
        return $this->hasMany('App\Models\Modelosmat', 'IdMaterial', 'id');
    }
    
    public function reglas()
    {
        return $this->hasMany('App\Models\Regla', 'IdMaterial', 'id');
    }
    
    public function tablaherrajesdets()
    {
        return $this->hasMany('App\Models\Tablaherrajesdet', 'IdMaterial', 'id');
    }
    
    public function unidad()
    {
        return $this->hasOne('App\Models\Unidad', 'id', 'IdUnidadRend');
    }
    
}
