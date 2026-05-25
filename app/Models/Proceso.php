<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proceso extends Model
{
	use HasFactory;
	
    public $timestamps = false;

    protected $table = 'procesos';

    protected $fillable = ['proceso','procesoI','IdDepto','PMaxMerma'];
public function depto()
{
    return $this->belongsTo(Depto::class, 'IdDepto');
}
    
}
