<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AlumnoController;

Route::get('/', function () {
    return view('welcome');
});

Route::resource('alumnos', AlumnoController::class);

// Ruta adicional para descargar PDF
Route::get('alumnos/{alumno}/pdf', [AlumnoController::class, 'descargarPdf'])->name('alumnos.pdf');
Route::get('alumnos/{id}/foto', [AlumnoController::class, 'mostrarFoto'])->name('alumnos.foto');