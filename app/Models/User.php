<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
	use HasApiTokens, HasFactory, Notifiable, HasRoles;
	
    public $timestamps = false;

    protected $table = 'users';

    protected $fillable = ['name','telefono','IdDepto','email','password'];   
     
    public function Depto()
    {
        return $this->hasOne('App\Models\Depto', 'id', 'IdDepto');
    }
    public function Division()
    {
        return $this->hasOne('App\Models\Division', 'id', 'IdDivision');
    }      
}
