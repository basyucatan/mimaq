<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Estilosdet extends Model
{
	use HasFactory;
	
    public $timestamps = false;

    protected $table = 'estilosdets';

    protected $fillable = ['IdEstilo','cantidad','IdMaterial','IdSize','IdForma','estiloY','adicionales'];
    protected $casts = [
        'adicionales' => 'array'
    ];
	
    public function estilo()
    {
        return $this->hasOne('App\Models\Estilo', 'id', 'IdEstilo');
    }
    
    public function foliosmats()
    {
        return $this->hasMany('App\Models\Foliosmat', 'IdEstilosDet', 'id');
    }
    
    public function forma()
    {
        return $this->hasOne('App\Models\Forma', 'id', 'IdForma');
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
