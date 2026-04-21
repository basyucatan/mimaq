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
        $partes = collect([
            $this->Size?->size ? '<strong>'.$this->Size->size.'</strong>' : null,
            $this->Forma?->forma ? '<strong>'.$this->Forma->forma.'</strong>' : null,
            $this->IdEstilo ? '<strong>E| </strong>'.$this->Estilo?->estilo : null,
            $this->estiloY ? '<strong>EE| </strong>'.$this->estiloY : null,
            data_get($this->adicionales,'orden') ? '<strong>O| </strong>'.data_get($this->adicionales,'orden') : null,
            data_get($this->adicionales,'lote') ? '<strong>L| </strong>'.data_get($this->adicionales,'lote') : null,
        ])->filter()->implode(' ');

        return $partes;
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
