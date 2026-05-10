<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Referenciasmov extends Model
{
    use HasFactory;

    public $timestamps = true;
    protected $table = 'referenciasmovs';
    protected $fillable = ['IdFacImportsDet','IdMaterial','IdDeptoOri','IdDeptoDes','tipo','estatus',
        'cantidad','pesoG','tipoDoc','IdDoc','glosa','diferencias','adicionales'];
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
            } elseif ($llave === 'material') {
                $salida[] = $valor;
            } else {
                $prefijo = ($llave == 'pesoG') ? 'g: ' : 'Pz: ';
                $signo = (is_numeric($valor) && $valor > 0) ? '+' : '';
                $salida[] = $prefijo . $signo . $valor;
            }
        }
        return implode(' | ', $salida);
    }

    public function Referencia()
    {
        return $this->belongsTo(Facimportsdet::class, 'IdFacImportsDet');
    }

    public function Material()
    {
        return $this->belongsTo(Material::class, 'IdMaterial');
    }

    public function DeptoOri()
    {
        return $this->belongsTo(Depto::class, 'IdDeptoOri');
    }

    public function DeptoDes()
    {
        return $this->belongsTo(Depto::class, 'IdDeptoDes');
    }
}