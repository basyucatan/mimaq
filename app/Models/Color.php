<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Color extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'colors';

    protected $fillable = ['IdColorable','color', 'IdColorHerraje', 'colorHex', 'colorRgba', 'clase'];
    public function colorHerraje()
    {
        return $this->belongsTo(Color::class, 'IdColorHerraje');
    }

    public function modelosPerfil()
    {
        return $this->hasMany(Modelo::class, 'IdColorPerfil');
    }

    public function modelosVidrio()
    {
        return $this->hasMany(Modelo::class, 'IdColorVidrio');
    }

    public function colorable()
    {
        return $this->hasOne('App\Models\Colorable', 'id', 'IdColorable');
    }    
}
