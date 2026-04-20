<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Forma extends Model
{
	use HasFactory;
	
    public $timestamps = false;

    protected $table = 'formas';

    protected $fillable = ['forma'];
    
	
    public function estilosdets()
    {
        return $this->hasMany('App\Models\Estilosdet', 'IdForma', 'id');
    }
    
}
