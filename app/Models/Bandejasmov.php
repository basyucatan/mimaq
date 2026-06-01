<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bandejasmov extends Model
{
	use HasFactory;
	
    public $timestamps = false;

    protected $table = 'bandejasmovs';

    protected $fillable = ['IdBandeja','IdProceso','IdUser','IdEmpleado','IdRegistrador','pesoEntrada',
        'pesoSalida','fechaHEntrada','fechaHSalida'];
    
	
    public function bandeja()
    {
        return $this->hasOne('App\Models\Bandeja', 'id', 'IdBandeja');
    }
    
    public function empleado()
    {
        return $this->hasOne('App\Models\Empleado', 'id', 'IdEmpleado');
    }
    public function registrador()
    {
        return $this->hasOne('App\Models\Empleado', 'id', 'IdRegistrador');
    }
public function proceso()
{
    return $this->belongsTo(Proceso::class, 'IdProceso');
}
public function user()
{
    return $this->belongsTo(User::class, 'IdUser');
}
}
