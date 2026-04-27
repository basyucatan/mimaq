<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Folio extends Model
{
	use HasFactory;
	
    public $timestamps = false;

    protected $table = 'folios';

    protected $fillable = ['IdLote','IdEstilo','jobStyle','cantidad','totalBandejas',
        'precioU','fechaVen','estatus','adicionales'];
    protected $casts = [
        'adicionales' => 'array'
    ];
	
    public function bandejas()
    {
        return $this->hasMany('App\Models\Bandeja', 'IdFolio', 'id');
    }
    
    public function estilo()
    {
        return $this->hasOne('App\Models\Estilo', 'id', 'IdEstilo');
    }
    
    public function foliosmats()
    {
        return $this->hasMany('App\Models\Foliosmat', 'IdFolio', 'id');
    }
    public function getTieneMatsAttribute()
    {
        return $this->foliosmats()->exists();
    }
    
    public function lote()
    {
        return $this->hasOne('App\Models\Lote', 'id', 'IdLote');
    }
    
}
