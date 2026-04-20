<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tipo extends Model
{
	use HasFactory;
	
    public $timestamps = false;

    protected $table = 'tipos';

    protected $fillable = ['tipo','adicionales'];
    protected $casts = [
        'adicionales' => 'array'
    ];
	
    public function clases()
    {
        return $this->hasMany('App\Models\Clase', 'IdTipo', 'id');
    }
    
}
