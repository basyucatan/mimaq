<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Facimportsdet extends Model
{
	use HasFactory;
	
    public $timestamps = false;

    protected $table = 'facimportsdets';

    protected $fillable = ['IdFactura','IdEntradaMex','IdOrigen','IdMaterial','cantidad','precioU','pesoEnUMat','pesoG','IdSize','IdForma','adicionales'];
    protected $casts = [
        'adicionales' => 'array'
    ];
	
    public function factura()
    {
        return $this->hasOne('App\Models\Factura', 'id', 'IdFactura');
    }
    
    public function foliosmats()
    {
        return $this->hasMany('App\Models\Foliosmat', 'IdFacImportsDet', 'id');
    }
    
    public function forma()
    {
        return $this->hasOne('App\Models\Forma', 'id', 'IdForma');
    }
    
    public function material()
    {
        return $this->hasOne('App\Models\Material', 'id', 'IdMaterial');
    }
    
    public function origen()
    {
        return $this->hasOne('App\Models\Origen', 'id', 'IdOrigen');
    }
    
    public function size()
    {
        return $this->hasOne('App\Models\Size', 'id', 'IdSize');
    }
    
}
