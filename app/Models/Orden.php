<?php 

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class Orden extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = 'ordens';
    protected $fillable = ['IdCliente','orden','estatus','fechaVen','alertas','adicionales'];
    protected $casts = ['adicionales' => 'array'];
    public function cliente()
    {
        return $this->hasOne('App\Models\Cliente', 'id', 'IdCliente');
    }
    public function lotes()
    {
        return $this->hasMany('App\Models\Lote', 'IdOrden', 'id');
    }
}