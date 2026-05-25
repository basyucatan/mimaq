<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Facexportsmat extends Model
{
	use HasFactory;
	
    public $timestamps = false;

    protected $table = 'facexportsmats';

    protected $fillable = ['IdFacExportsDet','IdFacImportsDet',
        'cantidad','pesoG'];
    public function facexportsdet()
    {
        return $this->hasOne('App\Models\Facexportsdet', 'id', 'IdFacExportsDet');
    }
    
    public function facimportsdet()
    {
        return $this->hasOne('App\Models\Facimportsdet', 'id', 'IdFacImportsDet');
    }
    
    public function material()
    {
        return $this->hasOne('App\Models\Material', 'id', 'IdMaterial');
    }
    
}
