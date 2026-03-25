<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class Materialscosto extends Model
{
    use HasFactory;
    public $timestamps = true;
    protected $table = 'materialscostos';
    protected $fillable = ['IdMaterial','referencia','ubicacion','IdColor','IdVidrio',
        'IdBarra','IdPanel','IdUnidad','direccion','costo','IdMoneda'];
    protected $casts = ['ubicacion' => 'array'];

    public function getUnidadAttribute()
    {
        if ($this->barra) return $this->barra->descripcion ?? 'pieza';
        if ($this->panel) return $this->panel->panel ?? 'pieza';
        return $this->material->unidad->unidad ?? 'pieza';
    }
    public function getValoresAttribute()
    {
        $IdUMaterial = $this->material?->IdUnidad ?? 1;
        $valorUReal = $this->costo;
        if ($this->barra) {
            if ($IdUMaterial == 3) { // Se costea por metro
                $longitudBarra = floatval($this->barra->longitud ?? 0);
                $valorUReal = $this->costo * $longitudBarra / 1000;
            }
        }
        $valorUReal =(float) $valorUReal;
        $valorURealMXN = $valorUReal * $this->Moneda->tipoCambio;
        $valores = [
            'valorUReal' => $valorUReal,
            'valorURealMXN' => $valorURealMXN,
        ];
        return $valores;
    } 
    public function moneda(){return $this->belongsTo(Moneda::class, 'IdMoneda');}
    public function barra(){return $this->belongsTo(Barra::class, 'IdBarra');}
    public function panel(){return $this->belongsTo(Panel::class, 'IdPanel');}
    public function color(){return $this->belongsTo(Color::class, 'IdColor');}
    public function material(){return $this->belongsTo(Material::class, 'IdMaterial');}
    public function unidadRel(){return $this->belongsTo(Unidad::class, 'IdUnidad');}

}