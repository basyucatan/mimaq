<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Facimportsdet extends Model
{
	use HasFactory;
	
    public $timestamps = false;

    protected $table = 'facimportsdets';

    protected $fillable = ['IdFactura','IdEntradaMex','IdOrigen','IdMaterial',
        'cantidad','precioU','pesoEnUMat','pesoG','IdSize','IdForma',
        'IdEstilo','estiloY','adicionales'];
    protected $casts = [
        'adicionales' => 'array'
    ];

    public function getPropiedadesAttribute()
    {
        return collect([
            data_get($this->adicionales, 'kt'),
            data_get($this->adicionales, 'color'),
            $this->Size?->size,
            $this->Forma?->forma,
        ])->filter()->implode(' ');
    }
    public function getPropsExtAttribute()
    {
        return collect([
            $this->propiedades,
            $this->IdEstilo ? 'E| '.$this->Estilo?->estilo : null,
            $this->estiloY ? 'EE| '.$this->estiloY : null,
        ])->filter()->implode(' ');
    }
    public function getPropsTotAttribute()
    {
        return collect([
            $this->propsExt,
            data_get($this->adicionales, 'orden') ? 'O| '.strtoupper(data_get($this->adicionales, 'orden')) : null,
            data_get($this->adicionales, 'lote') ? 'L| '.data_get($this->adicionales, 'lote') : null,
        ])->filter()->implode(' ');
    }   
    public function factura()
    {
        return $this->hasOne('App\Models\Factura', 'id', 'IdFactura');
    }
    
    public function foliosmats()
    {
        return $this->hasMany('App\Models\Foliosmat', 'IdFacImportsDet', 'id');
    }
    
    public function forma()
    {
        return $this->hasOne('App\Models\Forma', 'id', 'IdForma');
    }
    
    public function material()
    {
        return $this->hasOne('App\Models\Material', 'id', 'IdMaterial');
    }
    
    public function origen()
    {
        return $this->hasOne('App\Models\Origen', 'id', 'IdOrigen');
    }
    public function Estilo()
    {
        return $this->hasOne('App\Models\Estilo', 'id', 'IdEstilo');
    }    
    public function size()
    {
        return $this->hasOne('App\Models\Size', 'id', 'IdSize');
    }
    
}
