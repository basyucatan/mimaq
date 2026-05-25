<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Facexportsdet extends Model
{
	use HasFactory;
	
    public $timestamps = false;

    protected $table = 'facexportsdets';

    protected $fillable = ['IdFactura','IdBandeja','productoFinal','arancel','cantidad',
        'precioU','pesoG','castingIni','castingG','piedrasG','diamantesG','miscG','adicionales'];
    protected $casts = [
        'adicionales' => 'array'
    ];
	
    public function bandeja()
    {
        return $this->hasOne('App\Models\Bandeja', 'id', 'IdBandeja');
    }
    
    public function facexportsmats()
    {
        return $this->hasMany('App\Models\Facexportsmat', 'IdFacExportsDet', 'id');
    }
    
    public function factura()
    {
        return $this->hasOne('App\Models\Factura', 'id', 'IdFactura');
    }
    
}
