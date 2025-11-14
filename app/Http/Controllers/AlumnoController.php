<?php

namespace App\Http\Controllers;

use App\Models\Alumno;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

/**
 * Controlador para gestionar todas las operaciones CRUD de Alumnos
 * CRUD = Create (Crear), Read (Leer), Update (Actualizar), Delete (Eliminar)
 */
class AlumnoController extends Controller
{
    /**
     * Método index() - Muestra el listado de todos los alumnos
     * 
     * Pasos:
     * 1. Obtiene todos los registros de la tabla 'alumnos'
     * 2. Los pasa a la vista 'alumnos.index' para mostrarlos en una tabla
     * 
     * Ruta: GET /alumnos
     */
    public function index()
    {
        // Alumno::all() obtiene TODOS los registros de la base de datos
        $alumnos = Alumno::all();
        
        // compact('alumnos') crea un array ['alumnos' => $alumnos]
        // La vista podrá acceder a la variable $alumnos
        return view('alumnos.index', compact('alumnos'));
    }

    /**
     * Método create() - Muestra el formulario para crear un nuevo alumno
     * 
     * Solo retorna la vista con el formulario vacío
     * 
     * Ruta: GET /alumnos/create
     */
    public function create()
    {
        return view('alumnos.create');
    }

    /**
     * Método store() - Guarda un nuevo alumno en la base de datos
     * 
     * Pasos:
     * 1. Valida todos los datos recibidos del formulario
     * 2. Si hay una fotografía, la guarda en storage/app/public/fotografias
     * 3. Crea el registro del alumno en la base de datos
     * 4. Si hay un PDF, lo guarda con el nombre alumno_{id}.pdf
     * 5. Redirecciona al listado con un mensaje de éxito
     * 
     * Ruta: POST /alumnos
     * 
     * @param Request $request - Contiene todos los datos del formulario
     */
    public function store(Request $request)
    {
        // VALIDACIÓN: Verifica que los datos sean correctos antes de guardar
        $request->validate([
            'nombre' => 'required|string|max:255',  // Obligatorio, texto, máximo 255 caracteres
            'apellidos' => 'required|string|max:255',
            'telefono' => 'required|string|max:255',
            'correo' => 'required|email|unique:alumnos',  // Debe ser email válido y único
            'fecha_nacimiento' => 'required|date',  // Debe ser una fecha válida
            'nota_media' => 'required|numeric|min:0|max:10',  // Número entre 0 y 10
            'fotografia' => 'nullable|image|max:2048',  // Opcional, debe ser imagen, máx 2MB
            'cv_pdf' => 'nullable|mimes:pdf|max:10240'  // Opcional, solo PDF, máx 10MB
        ]);

        // $request->all() obtiene TODOS los datos del formulario
        $data = $request->all();

        // GUARDAR FOTOGRAFÍA (si existe)
        if ($request->hasFile('fotografia')) {
            // store() guarda el archivo y retorna la ruta: "fotografias/abc123.jpg"
            // 'public' indica que se guarda en storage/app/public
            $data['fotografia'] = $request->file('fotografia')->store('fotografias', 'public');
        }

        // CREAR ALUMNO en la base de datos
        // Retorna el objeto creado con su ID asignado automáticamente
        $alumno = Alumno::create($data);

        // GUARDAR PDF DEL CV (si existe)
        if ($request->hasFile('cv_pdf')) {
            // Crear nombre único: alumno_1.pdf, alumno_2.pdf, etc.
            $nombrePdf = 'alumno_' . $alumno->id . '.pdf';
            
            // storeAs() permite especificar el nombre del archivo
            // Se guarda en: storage/app/public/cvs/alumno_1.pdf
            $request->file('cv_pdf')->storeAs('cvs', $nombrePdf, 'public');
            
            // TAMBIÉN se guarda una copia en cvs_privados (storage/app/cvs_privados)
            // Esta carpeta NO es pública (sin 'public')
            $request->file('cv_pdf')->storeAs('cvs_privados', $nombrePdf);
        }

        // Redireccionar al listado con mensaje de éxito
        // session('success') estará disponible en la siguiente petición
        return redirect()->route('alumnos.index')->with('success', 'Alumno creado correctamente');
    }

    /**
     * Método show() - Muestra el CV completo de un alumno específico
     * 
     * Laravel automáticamente busca el alumno por su ID gracias a "Route Model Binding"
     * Si escribes /alumnos/5, Laravel busca el alumno con id=5
     * 
     * Ruta: GET /alumnos/{id}
     * 
     * @param Alumno $alumno - Objeto del alumno a mostrar (inyectado automáticamente)
     */
    public function show(Alumno $alumno)
    {
        // Pasa el objeto $alumno a la vista para mostrar sus datos
        return view('alumnos.show', compact('alumno'));
    }

    /**
     * Método edit() - Muestra el formulario para editar un alumno existente
     * 
     * Similar a show(), pero carga la vista del formulario de edición
     * El formulario viene pre-rellenado con los datos actuales del alumno
     * 
     * Ruta: GET /alumnos/{id}/edit
     * 
     * @param Alumno $alumno - Alumno a editar
     */
    public function edit(Alumno $alumno)
    {
        return view('alumnos.edit', compact('alumno'));
    }

    /**
     * Método update() - Actualiza los datos de un alumno existente
     * 
     * Pasos:
     * 1. Valida los nuevos datos (similar a store)
     * 2. Si hay nueva fotografía, la guarda
     * 3. Si hay nuevo PDF, lo guarda (reemplaza el anterior)
     * 4. Actualiza el registro en la base de datos
     * 5. Redirecciona con mensaje de éxito
     * 
     * Ruta: PUT/PATCH /alumnos/{id}
     * 
     * @param Request $request - Datos del formulario
     * @param Alumno $alumno - Alumno a actualizar
     */
    public function update(Request $request, Alumno $alumno)
    {
        // VALIDACIÓN (igual que store, pero el correo puede ser el mismo del alumno actual)
        $request->validate([
            'nombre' => 'required|string|max:255',
            'apellidos' => 'required|string|max:255',
            'telefono' => 'required|string|max:255',
            // unique:alumnos,correo,{id} permite que el correo sea el mismo si es del alumno actual
            'correo' => 'required|email|unique:alumnos,correo,' . $alumno->id,
            'fecha_nacimiento' => 'required|date',
            'nota_media' => 'required|numeric|min:0|max:10',
            'fotografia' => 'nullable|image|max:2048',
            'cv_pdf' => 'nullable|mimes:pdf|max:10240'
        ]);

        $data = $request->all();

        // ACTUALIZAR FOTOGRAFÍA (si se sube una nueva)
        if ($request->hasFile('fotografia')) {
            // MEJORA RECOMENDADA: Aquí deberías eliminar la foto anterior
            // Storage::disk('public')->delete($alumno->fotografia);
            
            $data['fotografia'] = $request->file('fotografia')->store('fotografias', 'public');
        }

        // ACTUALIZAR PDF (si se sube uno nuevo)
        if ($request->hasFile('cv_pdf')) {
            $nombrePdf = 'alumno_' . $alumno->id . '.pdf';
            
            // MEJORA RECOMENDADA: Eliminar el PDF anterior antes de subir el nuevo
            // if (Storage::disk('public')->exists('cvs/' . $nombrePdf)) {
            //     Storage::disk('public')->delete('cvs/' . $nombrePdf);
            // }
            
            // Guardar nuevo PDF (reemplaza automáticamente si existe)
            $request->file('cv_pdf')->storeAs('cvs', $nombrePdf, 'public');
            $request->file('cv_pdf')->storeAs('cvs_privados', $nombrePdf);
        }

        // ACTUALIZAR registro en la base de datos
        // update() modifica solo los campos que cambiaron
        $alumno->update($data);

        return redirect()->route('alumnos.index')->with('success', 'Alumno actualizado correctamente');
    }

    /**
     * Método destroy() - Elimina un alumno de la base de datos
     * 
     * PROBLEMA ACTUAL: No elimina los archivos (foto y PDF) del servidor
     * Esto causa que se acumulen archivos huérfanos
     * 
     * MEJORA RECOMENDADA:
     * - Eliminar la fotografía si existe
     * - Eliminar el PDF si existe
     * - Luego eliminar el registro
     * 
     * Ruta: DELETE /alumnos/{id}
     * 
     * @param Alumno $alumno - Alumno a eliminar
     */
    public function destroy(Alumno $alumno)
    {
        // MEJORA RECOMENDADA: Eliminar archivos antes de borrar el registro
        // if ($alumno->fotografia) {
        //     Storage::disk('public')->delete($alumno->fotografia);
        // }
        // $nombrePdf = 'alumno_' . $alumno->id . '.pdf';
        // if (Storage::disk('public')->exists('cvs/' . $nombrePdf)) {
        //     Storage::disk('public')->delete('cvs/' . $nombrePdf);
        // }
        
        // delete() elimina el registro de la base de datos
        $alumno->delete();
        
        return redirect()->route('alumnos.index')->with('success', 'Alumno eliminado correctamente');
    }

    /**
     * Método descargarPdf() - NUEVO MÉTODO PARA DESCARGAR PDFs
     * 
     * Este método permite descargar el PDF del CV con un nombre descriptivo
     * 
     * Pasos:
     * 1. Construye el nombre del archivo en el servidor (alumno_1.pdf)
     * 2. Verifica que el archivo existe
     * 3. Si existe, lo descarga con un nombre amigable (CV_Juan_Pérez.pdf)
     * 4. Si no existe, retorna un error
     * 
     * Ruta: GET /alumnos/{id}/pdf
     * 
     * @param Alumno $alumno - Alumno del cual descargar el PDF
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse|RedirectResponse
     */
    public function descargarPdf(Alumno $alumno)
    {
        // Construir nombre del archivo: alumno_1.pdf
        $nombrePdf = 'alumno_' . $alumno->id . '.pdf';
        
        // Ruta relativa dentro de storage/app/public/
        $rutaPdf = 'cvs/' . $nombrePdf;
        
        // Verificar si el archivo existe en el disco 'public'
        if (Storage::disk('public')->exists($rutaPdf)) {
            // download() descarga el archivo y permite cambiar el nombre
            // El usuario descargará: "CV_Juan_Pérez.pdf" en lugar de "alumno_1.pdf"
            return Storage::disk('public')->download(
                $rutaPdf, 
                'CV_' . $alumno->nombre . '_' . $alumno->apellidos . '.pdf'
            );
        }
        
        // Si el archivo no existe, redirigir atrás con mensaje de error
        return redirect()->back()->with('error', 'El archivo PDF no existe');
    }
}