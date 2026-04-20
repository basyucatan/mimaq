<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Unidad extends Model
{
	use HasFactory;
	
    public $timestamps = false;

    protected $table = 'unidads';

    protected $fillable = ['unidad','unidadI','factorC','grupo','adicionales'];
    protected $casts = [
        'adicionales' => 'array'
    ];
	
    public function materials()
    {
        return $this->hasMany('App\Models\Material', 'IdUnidad', 'id');
    }
    
    
}
