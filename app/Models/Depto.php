<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Depto extends Model
{
	use HasFactory;
	
    public $timestamps = false;

    protected $table = 'deptos';

    protected $fillable = ['depto','deptoI','orden'];
    
	
    public function users()
    {
        return $this->hasMany('App\Models\User', 'IdDepto', 'id');
    }
    
}
