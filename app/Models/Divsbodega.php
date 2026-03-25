<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Divsbodega extends Model
{
	use HasFactory;
	
    public $timestamps = false;

    protected $table = 'divsbodegas';

    protected $fillable = ['IdDivision','bodega'];
	
    public function division()
    {
        return $this->hasOne('App\Models\Division', 'id', 'IdDivision');
    }
    
}
