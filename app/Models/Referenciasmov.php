<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Referenciasmov extends Model
{
	use HasFactory;
	
    public $timestamps = false;

    protected $table = 'referenciasmovs';

    protected $fillable = ['IdFacImportsDet','IdDoc','tipoDoc','estatus',
        'cantidad','pesoG','diferencias','adicionales'];
    protected $casts = [
        'diferencias' => 'array',
        'adicionales' => 'array'
    ];
    public function getDifsFormatAttribute()
    {
        if (!$this->diferencias) return '';
        $salida = [];
        foreach ($this->diferencias as $llave => $valor) {
            if (is_numeric($llave)) {
                $salida[] = $valor;
            } else {
                $prefijo = ($llave == 'pesoG') ? 'g: ' : 'Pz: ';
                $signo = ($valor > 0) ? '+' : '';
                $salida[] = $prefijo . $signo . $valor;
            }
        }
        return implode(' | ', $salida);
    }	
    public function Referencia()
    {
        return $this->hasOne('App\Models\Facimportsdet', 'id', 'IdFacImportsDet');
    }
    
}
