<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ocomprasdet extends Model
{
	use HasFactory;
	
    public $timestamps = false;

    protected $table = 'ocomprasdets';

    protected $fillable = ['IdOCompra','IdMatCosto','cantidad','costoU'];
	
    public function materialscosto()
    {
        return $this->hasOne('App\Models\Materialscosto', 'id', 'IdMatCosto');
    }
    
    public function ocompra()
    {
        return $this->hasOne('App\Models\Ocompra', 'id', 'IdOCompra');
    }
    
}
