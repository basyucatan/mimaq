<?php
namespace App\Services;
use App\Models\Movinventario;
class MovInvService
{
    public static function registrar($datos)
    {
        return Movinventario::create([
            'IdUserOri' => $datos['IdUserOri'] ?? auth()->id(),
            'IdUserDes' => $datos['IdUserDes'] ?? null,
            'tipo' => $datos['tipo'],
            'IdBodega' => $datos['IdBodega'],
            'IdDepto' => $datos['IdDepto'] ?? 1,
            'IdBodegaOri' => $datos['IdBodegaOri'] ?? null,
            'IdDeptoOri' => $datos['IdDeptoOri'] ?? null,
            'IdMatCosto' => $datos['IdMatCosto'],
            'fechaH' => now()->tz('America/Mexico_City'),
            'cantidad' => $datos['cantidad'],
            'valorU' => $datos['valorU'] ?? 0,
            'dimensiones' => $datos['dimensiones'] ?? null,
            'adicionales' => $datos['adicionales'] ?? null,
        ]);
    }
    public static function Etiquetas(Movinventario $mov)
    {
        $esSalida = $mov->cantidad < 0;
        $nombreBodega = $mov->bodega->bodega ?? '-';
        $nombreDepto = $mov->depto->depto ?? '-';
        $ubicacionActual = $nombreBodega . ' (' . $nombreDepto . ')';
        if ($mov->IdDeptoOri) {
            $nombreBodegaOri = $mov->bodegaOri->bodega ?? '-';
            $nombreDeptoOri = $mov->deptoOri->depto ?? '-';
            $ubicacionRemota = $nombreBodegaOri . ' (' . $nombreDeptoOri . ')';
            $mov->txtOri = $esSalida ? $ubicacionActual : $ubicacionRemota;
            $mov->txtDes = $esSalida ? $ubicacionRemota : $ubicacionActual;
        } else {
            if ($esSalida) {
                $mov->txtOri = $ubicacionActual;
                $mov->txtDes = $mov->tipo == 'Entrega' ? 'CLIENTE' : 'EXTERNO';
            } else {
                $mov->txtOri = $mov->tipo == 'Compra' ? 'PROVEEDOR' : 'EXTERNO';
                $mov->txtDes = $ubicacionActual;
            }
        }
        return $mov;
    }
}