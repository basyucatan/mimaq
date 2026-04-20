<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pedimento extends Model
{
	use HasFactory;
	
    public $timestamps = true;

    protected $table = 'pedimentos';

    protected $fillable = ['pedimento','regimen','fecha','adicionales'];
    protected $casts = [
        'adicionales' => 'array'
    ];
	
    public function Facturas()
    {
        return $this->hasMany('App\Models\Factura', 'IdPedimento', 'id');
    }
    
}
