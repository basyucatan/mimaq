<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bandeja extends Model
{
	use HasFactory;
	
    public $timestamps = false;

    protected $table = 'bandejas';

    protected $fillable = ['IdFolio','IdFacturaExport','cantidad','pesoMetalInicial','pesoMetalActual','pesoPiedrasConstante','mermaMetalAcumulada','IdProcesoActual','enBoveda','habilitada','estatus'];
    
	
    public function bandejasmovs()
    {
        return $this->hasMany('App\Models\Bandejasmov', 'IdBandeja', 'id');
    }
    
    public function factura()
    {
        return $this->hasOne('App\Models\Factura', 'id', 'IdFacturaExport');
    }
    
    public function folio()
    {
        return $this->hasOne('App\Models\Folio', 'id', 'IdFolio');
    }
    
    public function proceso()
    {
        return $this->hasOne('App\Models\Proceso', 'id', 'IdProcesoActual');
    }
    
}
