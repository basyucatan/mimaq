<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lotesfoliosmat extends Model
{
	use HasFactory;
	
    public $timestamps = false;

    protected $table = 'lotesfoliosmats';

    protected $fillable = ['IdLotesFolio','IdFacImportsDet','cantidad','IdMaterial','pesoEnUMat','IdSize','IdForma','adicionales'];
    protected $casts = [
        'adicionales' => 'array'
    ];
	
    public function facimportsdet()
    {
        return $this->hasOne('App\Models\Facimportsdet', 'id', 'IdFacImportsDet');
    }
    
    public function forma()
    {
        return $this->hasOne('App\Models\Forma', 'id', 'IdForma');
    }
    
    public function lotesfolio()
    {
        return $this->hasOne('App\Models\Lotesfolio', 'id', 'IdLotesFolio');
    }
    
    public function material()
    {
        return $this->hasOne('App\Models\Material', 'id', 'IdMaterial');
    }
    
    public function size()
    {
        return $this->hasOne('App\Models\Size', 'id', 'IdSize');
    }
    
}
