<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lote extends Model
{
	use HasFactory;
	
    public $timestamps = false;

    protected $table = 'lotes';

    protected $fillable = ['lote','IdOrden','alertas','adicionales'];
    protected $casts = [
        'adicionales' => 'array'
    ];
	
    public function folios()
    {
        return $this->hasMany('App\Models\Folio', 'IdLote', 'id');
    }
    
    public function orden()
    {
        return $this->hasOne('App\Models\Orden', 'id', 'IdOrden');
    }
    
}
