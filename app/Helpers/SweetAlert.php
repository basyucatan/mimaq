<?php
namespace App\Helpers;

class SweetAlert
{
    public static function mensaje(
        string $mensaje,
        int $duracion = 1000,
        string $tipo = 'success'
    ): array {
        return [
            'text' => $mensaje,
            'timer' => $duracion,
            'icon' => $tipo,
        ];
    }
}
