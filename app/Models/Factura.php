<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Factura extends Model
{
	use HasFactory;
	
    public $timestamps = true;

    protected $table = 'facturas';

    protected $fillable = ['factura','IdPedimento', 'fecha', 'tipoCambio','adicionales'];
    protected $casts = [
        'adicionales' => 'array'
    ];
    public function getNextIdEntradaMex()
    {
        $prefijo = $this->factura . '-';
        $ultimoRegistro = $this->facimportsdets()
            ->where('IdEntradaMex', 'LIKE', $prefijo . '%')
            ->orderByRaw('CAST(SUBSTRING_INDEX(IdEntradaMex, "-", -1) AS UNSIGNED) DESC')
            ->first();
        if ($ultimoRegistro && strpos($ultimoRegistro->IdEntradaMex, '-') !== false) {
            $partes = explode('-', $ultimoRegistro->IdEntradaMex);
            $consecutivo = (int) end($partes) + 1;
        } else {
            $consecutivo = 1;
        }
        return $prefijo . $consecutivo;
    }	
    public function facimportsdets()
    {
        return $this->hasMany('App\Models\Facimportsdet', 'IdFactura', 'id');
    }
    
    public function pedimento()
    {
        return $this->belongsTo('App\Models\Pedimento', 'id', 'IdPedimento');
    }
    
}
