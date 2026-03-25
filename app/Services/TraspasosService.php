<?php
namespace App\Services;
use App\Models\{Traspaso, Traspasosdet, Movinventario, Moneda};
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
class TraspasosService
{
    public static function crearTraspaso($datos)
    {
        return Traspaso::create([
            'tipo' => $datos['tipo'] ?? 'Traspaso',
            'IdUserOri' => $datos['IdUserOri'] ?? Auth::id(),
            'IdUserDes' => $datos['IdUserDes'] ?? null,
            'IdBodegaOri' => $datos['IdBodegaOri'] ?? null,
            'IdBodegaDes' => $datos['IdBodegaDes'] ?? null,
            'fechaH' => now()->tz('America/Mexico_City'),
            'estatus' => 'Abierto',
            'adicionales' => array_merge([
                'tipoCEuro' => Moneda::find(2)?->tipoCambio,
                'tipoCDolar' => Moneda::find(3)?->tipoCambio
            ], $datos['adicionales'] ?? [])
        ]);
    }
    public static function agregarDetalle($IdTraspaso, $item)
    {
        return Traspasosdet::create([
            'IdTraspaso' => $IdTraspaso,
            'IdMatCosto' => $item['IdMatCosto'],
            'cantidad' => $item['cantidad'],
            'valorU' => $item['valorU'] ?? 0,
            'dimensiones' => $item['dimensiones'] ?? null,
            'adicionales' => $item['adicionales'] ?? null
        ]);
    }
    
    public static function cerrarTraspaso($IdTraspaso)
    {
        return DB::transaction(function () use ($IdTraspaso) {
            $traspaso = Traspaso::with('traspasosdets')->findOrFail($IdTraspaso);
            if (!$traspaso->traspasosdets->count()) {
                return ['status' => 'warning', 'mensaje' => 'No hay detalles en el movimiento'];
            }
            $traspaso->estatus = 'Cerrado';
            $traspaso->IdUserDes = Auth::id();
            $traspaso->save();
            foreach ($traspaso->traspasosdets as $det) {
                $adicionalesMov = [];
                if ($traspaso->tipo == 'Compra') {
                    $adicionalesMov['IdPresupuesto'] = $traspaso->adicionales['IdPresupuesto'] ?? 0;
                }
                if ($traspaso->IdBodegaOri) {
                    Movinventario::create([
                        'IdUserOri' => $traspaso->IdUserOri,
                        'IdUserDes' => $traspaso->IdUserDes,
                        'tipo' => $traspaso->tipo,
                        'IdBodega' => $traspaso->IdBodegaOri,
                        'IdDepto' => $traspaso->adicionales['IdDeptoOri'] ?? 1,
                        'IdMatCosto' => $det->IdMatCosto,
                        'fechaH' => now()->tz('America/Mexico_City'),
                        'cantidad' => $det->cantidad * -1,
                        'valorU' => $det->valorU ?? 0,
                        'dimensiones' => $det->dimensiones ?? null,
                        'adicionales' => $adicionalesMov,
                    ]);
                }
                if ($traspaso->IdBodegaDes) {
                    Movinventario::create([
                        'IdUserOri' => $traspaso->IdUserOri,
                        'IdUserDes' => $traspaso->IdUserDes,
                        'tipo' => $traspaso->tipo,
                        'IdBodega' => $traspaso->IdBodegaDes,
                        'IdDepto' => $traspaso->adicionales['IdDeptoDes'] ?? 1,
                        'IdMatCosto' => $det->IdMatCosto,
                        'fechaH' => now()->tz('America/Mexico_City'),
                        'cantidad' => $det->cantidad,
                        'valorU' => $det->valorU ?? 0,
                        'dimensiones' => $det->dimensiones ?? null,
                        'adicionales' => $adicionalesMov,
                    ]);
                }
            }
            return ['status' => 'success', 'mensaje' => 'Traspaso procesado correctamente', 'objeto' => $traspaso];
        });
    }
}