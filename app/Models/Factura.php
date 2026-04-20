<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Factura extends Model
{
	use HasFactory;
	
    public $timestamps = true;

    protected $table = 'facturas';

    protected $fillable = ['factura','IdPedimento', 'fecha', 'tipoCambio','adicionales'];
    protected $casts = [
        'adicionales' => 'array'
    ];
	
    public function facturasdets()
    {
        return $this->hasMany('App\Models\Facimportsdet', 'IdFacturo', 'id');
    }
    
    public function pedimento()
    {
        return $this->belongsTo('App\Models\Pedimento', 'id', 'IdPedimento');
    }
    
}
