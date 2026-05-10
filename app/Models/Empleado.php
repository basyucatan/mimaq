<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Empleado extends Model
{
	use HasFactory;
	
    public $timestamps = false;

    protected $table = 'empleados';

    protected $fillable = ['empleado','IdDepto','vigente','adicionales'];
    protected $casts = [
        'adicionales' => 'array'
    ];
	
    public function bandejasmovs()
    {
        return $this->hasMany('App\Models\Bandejasmov', 'IdEmpleado', 'id');
    }
    
    public function depto()
    {
        return $this->hasOne('App\Models\Depto', 'id', 'IdDepto');
    }
    
}
