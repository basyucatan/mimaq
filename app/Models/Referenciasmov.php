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
        'cantidad','pesoG','tipoDoc','IdDoc','glosa','adicionales'];
    protected $casts = [
        'adicionales' => 'array'
    ];
    public function getDifsFormatAttribute()
    {
        return $this->Referencia?->difsFormat ?? '';
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