<?php
namespace App\Helpers;
class SweetAlert
{
    public static function mensaje(string $mensaje, string $tipo = 'success', int $duracion = 1500): array
    {
        return [
            'text' => $mensaje,
            'timer' => $duracion,
            'icon' => $tipo,
        ];
    }
}