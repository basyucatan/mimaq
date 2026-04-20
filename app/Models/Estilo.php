<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Estilo extends Model
{
	use HasFactory;
	
    public $timestamps = false;

    protected $table = 'estilos';

    protected $fillable = ['estilo','IdClase','foto'];
    
	
    public function clase()
    {
        return $this->hasOne('App\Models\Clase', 'id', 'IdClase');
    }
    
    public function estilosdets()
    {
        return $this->hasMany('App\Models\Estilosdet', 'IdEstilo', 'id');
    }
    
}
