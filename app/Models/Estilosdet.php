<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Estilosdet extends Model
{
	use HasFactory;
	
    public $timestamps = false;

    protected $table = 'estilosdets';

    protected $fillable = ['IdEstilo','cantidad','claseI','adicionales'];
    protected $casts = [
        'adicionales' => 'array'
    ];
	
    public function estilo()
    {
        return $this->hasOne('App\Models\Estilo', 'id', 'IdEstilo');
    }
    
}
