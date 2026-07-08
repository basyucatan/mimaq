<?php 
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class Clase extends Model
{
	use HasFactory;
    public $timestamps = false;
    protected $table = 'clases';
    protected $fillable = ['IdTipo','IdArancel','clase','claseI','adicionales'];
    protected $casts = ['adicionales' => 'array'];
    public function tipo(){return $this->hasOne('App\Models\Tipo', 'id', 'IdTipo');}
    public function arancel(){return $this->hasOne('App\Models\Arancel', 'id', 'IdArancel');}
    public function estilos(){return $this->hasMany('App\Models\Estilo', 'IdClase', 'id');}
    public function materials(){return $this->hasMany('App\Models\Material', 'IdClase', 'id');}
    
}
