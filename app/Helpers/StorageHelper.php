<?php
//app/Helpers/Envato/User.php

namespace App\Helpers;

use Illuminate\Support\Facades\Storage;

class StorageHelper
{
    public static function existe($path, $disk = 'uploads')
    {
        return \Storage::disk($disk)->exists($path);
    }

    public static function crearDirectorio($path, $disk = 'uploads')
    {
        return \Storage::disk($disk)->makeDirectory($path);
    }

    public static function mover($desde, $hasta, $disk = 'uploads')
    {
        return \Storage::disk($disk)->move($desde, $hasta);
    }

    public static function copiar($desde, $hasta, $disk = 'uploads')
    {
        return \Storage::disk($disk)->copy($desde, $hasta);
    }

    public static function eliminar($path, $disk = 'uploads')
    {
        return \Storage::disk($disk)->delete($path);
    }

    public static function url($path, $disk = 'uploads')
    {
        return \Storage::disk($disk)->url($path);
    }

    public static function path($path, $disk = 'uploads')
    {
        return \Storage::disk($disk)->path($path);
    }

    public static function put($path, $contenido, $options = [], $disk = 'uploads')
    {
        return \Storage::disk($disk)->put($path, $contenido, $options);
    }

    public static function contenido($path, $disk = 'uploads')
    {
        return \Storage::disk($disk)->get($path);
    }

    public static function descargar($nombreArchivo, $path = '', $disk = 'uploads')
    {
        $headers = [
            'Content-Disposition' => 'attachment; filename="' . $nombreArchivo . '"',
        ];

        if ($path) {
            $path = $path . '/' . $nombreArchivo;
        }

        return \Response::make(\Storage::disk($disk)->get($path), 200, $headers);
    }

    public static function directorios($path, $disk = 'uploads')
    {
        return \Storage::disk($disk)->directories($path);
    } 
    
    public static function archivos($path, $disk = 'uploads')
    {
        return \Storage::disk($disk)->files($path);
    }     
}
