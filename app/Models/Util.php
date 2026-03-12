<?php

namespace App\Models;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class Util
{
    //Formato moneda
    public static function Dinero($Numero, $centavos = 2)
    {
        $num = '$ ' . number_format(abs($Numero), $centavos);
        if ($Numero < 0) {
            $num = '- ' . $num;
        }
        return $num;
    }
    public static function Miles($Numero, $centavos = 0)
    {
        $num = number_format(abs($Numero), $centavos);
        if ($Numero < 0) {
            $num = '- ' . $num;
        }
        return $num;
    }
    public static function Divide($numerador, $denominador)
    {
        $division = $denominador != 0 ? $numerador / $denominador : 0;
        return $division;
    }

    public static function formatFecha($date, $formato = 'Larga')
    {
        $carbonDate = Carbon::parse($date);
        $diasSemana = ['Dom', 'Lun', 'Mar', 'Mie', 'Jue', 'Vie', 'Sab'];
        $meses = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'];
        switch ($formato) {
            case 'Corta': //Dom 9/Feb
                $fecha = sprintf(
                    "%s %d/%s",
                    $diasSemana[$carbonDate->dayOfWeek],
                    $carbonDate->day,
                    $meses[$carbonDate->month - 1]
                );
                break;
            case 'hm': //23:31	
                $fecha = sprintf(
                    "%02d:%02d",
                    $carbonDate->hour,
                    $carbonDate->minute
                );
                break;
            case 'Dhm': //8 | 23:31	
                $fecha = sprintf(
                    "%d | %02d:%02d",
                    $carbonDate->day,
                    $carbonDate->hour,
                    $carbonDate->minute
                );
                break;
            case 'CortaDhm': //Dom 9/Feb 12:51	
                $fecha = sprintf(
                    "%s %d/%s %02d:%02d",
                    $diasSemana[$carbonDate->dayOfWeek],
                    $carbonDate->day,
                    $meses[$carbonDate->month - 1],
                    $carbonDate->hour,
                    $carbonDate->minute
                );
                break;
            case 'MMM/AA': //Feb/25	
                $fecha = sprintf(
                    "%s/%s",
                    $meses[$carbonDate->month - 1],
                    $carbonDate->format('y')
                );
                break;
            case 'D/MMM': //8/Feb
                $fecha = sprintf(
                    "%d/%s",
                    $carbonDate->day,
                    $meses[$carbonDate->month - 1]
                );
                break;
            case 'prefijo': // <-- Nuevo formato
                $fecha = sprintf(
                    "%02d%02d%02d",
                    $carbonDate->year % 100,
                    $carbonDate->month,
                    $carbonDate->day
                );
                break;                
            case 'D/MMM/AA': //8/Feb/25	
                $fecha = sprintf(
                    "%d/%s/%02d",
                    $carbonDate->day,
                    $meses[$carbonDate->month - 1],
                    $carbonDate->year % 100 // Obtiene los últimos dos dígitos del año
                );
                break;
            case 'abreviada': // S8Feb25|2331
                $fecha = sprintf(
                    "%s%d%s%02dH%02d%02d",
                    mb_substr($diasSemana[$carbonDate->dayOfWeek], 0, 1),
                    $carbonDate->day,
                    $meses[$carbonDate->month - 1],
                    $carbonDate->year % 100,
                    $carbonDate->hour,
                    $carbonDate->minute
                );
                break;

            case 'Larga': //Sab 8/Feb/25 23:31
            default:
                $fecha = sprintf(
                    "%s %d/%s/%s %02d:%02d",
                    $diasSemana[$carbonDate->dayOfWeek],
                    $carbonDate->day,
                    $meses[$carbonDate->month - 1],
                    $carbonDate->format('y'),
                    $carbonDate->hour,
                    $carbonDate->minute
                );
                break;
        }
        return $fecha;
    }

    public static function getArray(string $tabla, ?string $campo = null): array
    {
        //Si no se especifica $campo, es con base a la tabla, ejemplo: $this->vidrios = Util::getArray('vidrios');
        if (empty($campo)) {
            $campo = Str::singular($tabla);
        }
        $columna = DB::select("SHOW COLUMNS FROM {$tabla} LIKE '{$campo}'")[0] ?? null;

        if (!$columna) {
            throw new \InvalidArgumentException("El campo '{$campo}' no existe en la tabla '{$tabla}'");
        }
        if (Str::startsWith($columna->Type, 'enum(')) { 
            // Extraer los valores del enum y ordenarlos alfabéticamente
            // $this->cancelerias = Util::getArray('presupuestos', 'canceleria'); canceleria es tipo enum de presupuestos
            $values = substr($columna->Type, 5, -1);
            $values = str_getcsv($values, ',', "'");
            natcasesort($values); //esto aplica orden alfabético
            return array_combine($values, $values);
        }
        return DB::table($tabla)->orderBy($campo, 'asc')->pluck($campo, 'id')->toArray();
    }

    public static function getArrayJS(string $catalogo, ?string $campo = null): array
    {
        $data = config("settings.catalogos.$catalogo", []);
        if (empty($data)) {
            return [];
        }
        $coleccion = collect($data);
        if ($campo) {
            if (!isset($data[0][$campo])) {
                throw new \InvalidArgumentException("El campo '{$campo}' no existe en el catálogo '{$catalogo}'");
            }
            return $coleccion->sortBy($campo)->pluck($campo, 'id')->toArray();
        }
        return $coleccion->keyBy('id')->toArray();
    }

    public static function guardarArchivo($file, $nombreBase, $carpeta)
    {
        if (!$file || !$carpeta) return null;
        $extImagen = ['jpg','jpeg','png','webp'];
        $ext = strtolower($file->extension());
        return in_array($ext, $extImagen)
            ? self::guardarFoto($file, $nombreBase, $carpeta)
            : self::guardarDocumento($file, $nombreBase, $carpeta);
    }

    public static function guardarDocumento($file, $nombreBase, $carpeta)
    {
        $base = Str::slug(pathinfo($nombreBase, PATHINFO_FILENAME));
        $ext  = $file->extension();
        if (strlen($base) > 96) {
            $base = substr($base, 0, 96) . '-' . Str::random(4);
        }
        $nombreArchivo = $base . '.' . $ext;
        Storage::putFileAs("public/{$carpeta}", $file, $nombreArchivo);
        return $nombreArchivo;
    }
    
    public static function borrarArchivo($carpeta, $nombreArchivo)
    {
        if (!$nombreArchivo || !$carpeta) return false;
        $ruta = "public/{$carpeta}/{$nombreArchivo}";
        if (Storage::exists($ruta)) {
            Storage::delete($ruta);
        }
        return true;
    }
    public static function guardarFoto($file, $nombreBase, $carpeta)
    {
        if (!$file || !$carpeta) return null;
        $base = Str::slug(pathinfo($nombreBase, PATHINFO_FILENAME));
        $ext  = $file->extension();                                   
        if (strlen($base) > 96) {
            $base = substr($base, 0, 96) . '-' . Str::random(4);  //maximo 100 caracteres
        }
        $nombreFoto = $base . '.' . $ext;
        $dirTmp = storage_path('app/tmp');
        if (!is_dir($dirTmp) && !@mkdir($dirTmp, 0755, true)) {
            return null; // no se puede crear tmp
        }
        $rutaTemp = $dirTmp . DIRECTORY_SEPARATOR . $nombreFoto;
        $manager = new ImageManager(new Driver());
        $image = $manager->read($file->getRealPath());
        $origW = $image->width(); 
        $origH = $image->height();
        $maxSide = 1000;
        if ($origW <= $maxSide && $origH <= $maxSide) {
            $targetW = $origW; $targetH = $origH;
        } elseif ($origW >= $origH) {
            $targetW = $maxSide;
            $targetH = (int) round($origH * ($targetW / $origW));
        } else {
            $targetH = $maxSide;
            $targetW = (int) round($origW * ($targetH / $origH));
        }
        $image->resize($targetW, $targetH, function ($c) { $c->upsize(); });
        $maxBytes = 500 * 1024;
        $qualities = [90,80,70,60,50,40,30];
        foreach ($qualities as $q) {
            @unlink($rutaTemp);              // eliminar temp previo si existe
            $image->save($rutaTemp, $q);     // guardar intento
            clearstatcache(true, $rutaTemp);

            if (file_exists($rutaTemp) && filesize($rutaTemp) <= $maxBytes) break;
        }
        try {
            Storage::putFileAs("public/{$carpeta}", new \Illuminate\Http\File($rutaTemp), $nombreFoto);
        } finally {
            @unlink($rutaTemp);
        }
        return $nombreFoto;
    }


}
