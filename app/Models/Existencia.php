<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Existencia extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = 'existencias';
    protected $fillable = ['IdFacImportsDet', 'IdDepto', 'cantidad', 'pesoG'];
    public function depto()
    {
        return $this->belongsTo(Depto::class, 'IdDepto');
    }
    public function facimportsdet()
    {
        return $this->belongsTo(Facimportsdet::class, 'IdFacImportsDet');
    }
}