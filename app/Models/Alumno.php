<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Alumno extends Model
{
    // Especificar el nombre de la tabla si no sigue la convención
    protected $table = 'alumnos';

    // Campos que se pueden asignar masivamente
    protected $fillable = [
        'nombre',
        'apellidos',
        'telefono',
        'correo',
        'fecha_nacimiento',
        'nota_media',
        'experiencia',
        'formacion',
        'habilidades',
        'fotografia'
    ];

    /**
     * Obtiene la ruta completa de la fotografía del alumno
     * Similar a getPath() en Peinado
     */
    function getPath(): string
    {
        $path = url('assets/img/default-avatar.jpg');
        if ($this->fotografia != null &&
            file_exists(storage_path('app/public') . '/' . $this->fotografia)) {
            $path = url('storage/' . $this->fotografia);
        }
        return $path;
    }

    /**
     * Obtiene la ruta del archivo PDF del CV
     * Similar a getPdf() en Peinado
     */
    function getPdf(): string
    {
        return url('storage/cvs') . '/alumno_' . $this->id . '.pdf';
    }

    /**
     * Comprueba si existe el archivo PDF del CV
     * Similar a isPdf() en Peinado
     */
    function isPdf(): bool
    {
        return file_exists(storage_path('app/public/cvs') . '/alumno_' . $this->id . '.pdf');
    }

}