<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lotesfolio extends Model
{
	use HasFactory;
	
    public $timestamps = false;

    protected $table = 'lotesfolios';

    protected $fillable = ['IdLote','IdEstilo','cantidad','precioU','peso','jobStyle','fechaVen','adicionales'];
    protected $casts = [
        'adicionales' => 'array'
    ];
	
    public function estilo()
    {
        return $this->hasOne('App\Models\Estilo', 'id', 'IdEstilo');
    }
    
    public function lote()
    {
        return $this->hasOne('App\Models\Lote', 'id', 'IdLote');
    }
    
    public function lotesfoliosmats()
    {
        return $this->hasMany('App\Models\Lotesfoliosmat', 'IdLotesFolio', 'id');
    }
    
}
