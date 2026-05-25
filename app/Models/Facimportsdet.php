<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Facimportsdet extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = 'facimportsdets';
    protected $fillable = [
        'IdFactura', 'IdEntradaMex', 'IdOrigen', 'IdMaterial', 'arancel',
        'cantidad', 'precioU', 'pesoEnUMat', 'pesoG', 'IdSize', 'IdForma',
        'IdFolio', 'IdEstilo', 'estiloY',  'diferencias', 'adicionales'
    ];
    protected $casts = [
        'diferencias' => 'array',
        'adicionales' => 'array'
    ];
    public function getPropiedadesAttribute()
    {
        return collect([
            data_get($this->adicionales, 'kt'),
            data_get($this->adicionales, 'color'),
            $this->size?->size,
            $this->forma?->forma,
        ])->filter()->implode(' ');
    }
    public function getPropsTotAttribute()
    {
        return collect([
            $this->propiedades,
            $this->IdEstilo ? $this->estilo?->estilo : null,
            $this->estiloY ? $this->estiloY : null,
        ])->filter()->implode(' ');
    }
    public function getOrdenInfoAttribute()
    {
        $folio = $this->folio;
        $lote = $folio?->lote;
        $orden = $lote?->orden;
        $cliente = $orden?->cliente;
        return collect([
            $orden ? $orden->orden : null,
            $lote ? '-' . $lote->lote : null,
            $folio ? ' | ' . $folio->id : null,
            $cliente ? ' (' . $cliente->cliente.')' : null
        ])->filter()->implode(' ');
    }
    public function getDifsFormatAttribute()
    {
        if (!$this->diferencias) return '';
        return collect($this->diferencias)->map(function ($valor, $llave) {
            if (is_numeric($llave)) return $valor;
            if ($llave === 'material') return $valor;
            
            $prefijo = ($llave === 'pesoG') ? 'g: ' : 'Pz: ';
            $signo = (is_numeric($valor) && $valor > 0) ? '+' : '';
            return $prefijo . $signo . $valor;
        })->implode(' | ');
    }
    public function factura() { return $this->belongsTo(Factura::class, 'IdFactura'); }
    public function foliosmats() { return $this->hasMany(Foliosmat::class, 'IdFacImportsDet'); }
    public function forma() { return $this->belongsTo(Forma::class, 'IdForma'); }
    public function folio() { return $this->belongsTo(Folio::class, 'IdFolio'); }
    public function material() { return $this->belongsTo(Material::class, 'IdMaterial'); }
    public function origen() { return $this->belongsTo(Origen::class, 'IdOrigen'); }
    public function estilo() { return $this->belongsTo(Estilo::class, 'IdEstilo'); }
    public function size() { return $this->belongsTo(Size::class, 'IdSize'); }
    public function referenciasmovs() { return $this->hasMany(Referenciasmov::class, 'IdFacImportsDet'); }
    public function existencias() { return $this->hasMany(Existencia::class, 'IdFacImportsDet', 'id'); }
}