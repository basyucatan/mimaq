<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Foliosmat extends Model
{
	use HasFactory;
	
    public $timestamps = false;

    protected $table = 'foliosmats';

    protected $fillable = ['IdFolio','IdFacImportsDet','IdMaterial',
        'cantidad','pesoG','integrado','adicionales'];
    protected $casts = [
        'adicionales' => 'array'
    ];
	
    public function facimportsdet()
    {
        return $this->hasOne('App\Models\Facimportsdet', 'id', 'IdFacImportsDet');
    }
    
    public function folio()
    {
        return $this->hasOne('App\Models\Folio', 'id', 'IdFolio');
    }
    
    public function material()
    {
        return $this->hasOne('App\Models\Material', 'id', 'IdMaterial');
    }
    
}
