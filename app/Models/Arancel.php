<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Arancel extends Model
{
	use HasFactory;
	
    public $timestamps = false;

    protected $table = 'arancels';

    protected $fillable = ['arancel','arancelUSA','descripcion','IdPermiso'];
    
	
    public function clases()
    {
        return $this->hasMany('App\Models\Clase', 'IdArancel', 'id');
    }
    
    public function permiso()
    {
        return $this->hasOne('App\Models\Permiso', 'id', 'IdPermiso');
    }
    
}
