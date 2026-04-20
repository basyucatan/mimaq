<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lote extends Model
{
	use HasFactory;
	
    public $timestamps = false;

    protected $table = 'lotes';

    protected $fillable = ['lote','IdOrden','adicionales'];
    protected $casts = [
        'adicionales' => 'array'
    ];
	
    public function lotesfolios()
    {
        return $this->hasMany('App\Models\Lotesfolio', 'IdLote', 'id');
    }
    
    public function orden()
    {
        return $this->hasOne('App\Models\Orden', 'id', 'IdOrden');
    }
    
}
