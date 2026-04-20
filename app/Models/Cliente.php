<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
	use HasFactory;
	
    public $timestamps = false;

    protected $table = 'clientes';

    protected $fillable = ['cliente','adicionales'];
    protected $casts = [
        'adicionales' => 'array'
    ];
	
    public function ordens()
    {
        return $this->hasMany('App\Models\Orden', 'IdCliente', 'id');
    }
    
}
