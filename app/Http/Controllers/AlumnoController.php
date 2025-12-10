<?php

namespace App\Http\Controllers;

use App\Models\Alumno;
use Illuminate\Database\QueryException;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AlumnoController extends Controller
{
    /**
     * Muestra el listado de todos los alumnos
     */
    function index(): View
    {
        $alumnos = Alumno::all();
        return view('alumnos.index', ['alumnos' => $alumnos]);
    }

    /**
     * Muestra el formulario para crear un nuevo alumno
     */
    function create(): View
    {
        return view('alumnos.create');
    }

    /**
     * Guarda un nuevo alumno en la base de datos
     */
    function store(Request $request): RedirectResponse
    {
        // Validación similar a PeinadoCreateRequest
        $validatedData = $request->validate([
            'nombre' => 'required|string|max:255',
            'apellidos' => 'required|string|max:255',
            'telefono' => 'required|string|max:255',
            'correo' => 'required|email|unique:alumnos,correo|max:255',
            'fecha_nacimiento' => 'required|date',
            'nota_media' => 'required|numeric|min:0|max:10|decimal:0,2',
            'experiencia' => 'nullable|string',
            'formacion' => 'nullable|string',
            'habilidades' => 'nullable|string',
            'fotografia' => 'nullable|image|max:2048',
            'cv_pdf' => 'nullable|mimes:pdf|max:10240'
        ]);

        // Crear alumno con los datos validados (excepto archivos)
        $alumno = new Alumno($request->except(['fotografia', 'cv_pdf']));
        $result = false;

        try {
            $result = $alumno->save();
            $txtMessage = 'El alumno ha sido agregado correctamente.';

            // Subir fotografía si existe
            if ($request->hasFile('fotografia')) {
                $ruta = $this->uploadFotografia($request, $alumno);
                $alumno->fotografia = $ruta;
                $alumno->save();
            }

            // Subir PDF si existe
            if ($request->hasFile('cv_pdf')) {
                $this->uploadPdf($request, $alumno);
            }
        } catch (UniqueConstraintViolationException $e) {
            $txtMessage = 'El correo ya está registrado.';
        } catch (QueryException $e) {
            $txtMessage = 'Error en la base de datos.';
        } catch (\Exception $e) {
            $txtMessage = 'Error al crear el alumno.';
        }

        $message = [
            'mensajeTexto' => $txtMessage,
        ];

        if ($result) {
            return redirect()->route('alumnos.index')->with($message);
        } else {
            return back()->withInput()->withErrors($message);
        }
    }

    /**
     * Muestra el CV completo de un alumno específico
     */
    function show(Alumno $alumno): View
    {
        return view('alumnos.show', ['alumno' => $alumno]);
    }

    /**
     * Muestra el formulario para editar un alumno existente
     */
    function edit(Alumno $alumno): View
    {
        return view('alumnos.edit', ['alumno' => $alumno]);
    }

    /**
     * Actualiza los datos de un alumno existente
     */
    function update(Request $request, Alumno $alumno): RedirectResponse
    {
        // Validación con excepción del correo actual
        $validatedData = $request->validate([
            'nombre' => 'required|string|max:255',
            'apellidos' => 'required|string|max:255',
            'telefono' => 'required|string|max:255',
            'correo' => 'required|email|max:255|unique:alumnos,correo,' . $alumno->id,
            'fecha_nacimiento' => 'required|date',
            'nota_media' => 'required|numeric|min:0|max:10|decimal:0,2',
            'experiencia' => 'nullable|string',
            'formacion' => 'nullable|string',
            'habilidades' => 'nullable|string',
            'fotografia' => 'nullable|image|max:2048',
            'cv_pdf' => 'nullable|mimes:pdf|max:10240'
        ]);

        $result = false;

        // Manejar eliminación de fotografía si se solicita
        if ($request->deleteFotografia == 'true') {
            $alumno->fotografia = null;
        }

        // Actualizar datos del alumno
        $alumno->fill($request->except(['fotografia', 'cv_pdf', 'deleteFotografia']));

        try {
            // Subir nueva fotografía si existe
            if ($request->hasFile('fotografia')) {
                $ruta = $this->uploadFotografia($request, $alumno);
                $alumno->fotografia = $ruta;
            }

            $result = $alumno->save();

            // Subir nuevo PDF si existe
            if ($request->hasFile('cv_pdf')) {
                $this->uploadPdf($request, $alumno);
            }

            $txtMessage = 'El alumno ha sido actualizado correctamente.';
        } catch (UniqueConstraintViolationException $e) {
            $txtMessage = 'El correo ya está registrado.';
        } catch (QueryException $e) {
            $txtMessage = 'Error en la base de datos.';
        } catch (\Exception $e) {
            $txtMessage = 'Error al actualizar el alumno.';
        }

        $message = [
            'mensajeTexto' => $txtMessage,
        ];

        if ($result) {
            return redirect()->route('alumnos.index')->with($message);
        } else {
            return back()->withInput()->withErrors($message);
        }
    }

    /**
     * Elimina un alumno de la base de datos
     */
    function destroy(Alumno $alumno): RedirectResponse
    {
        try {
            $result = $alumno->delete();
            $txtMessage = 'El alumno se ha eliminado correctamente.';
        } catch (\Exception $e) {
            $txtMessage = 'El alumno no se ha podido eliminar.';
            $result = false;
        }

        $message = [
            'mensajeTexto' => $txtMessage,
        ];

        if ($result) {
            return redirect()->route('alumnos.index')->with($message);
        } else {
            return back()->withInput()->withErrors($message);
        }
    }

    /**
     * Método privado para subir la fotografía
     */
    private function uploadFotografia(Request $request, Alumno $alumno): string
    {
        $fotografia = $request->file('fotografia');
        $name = 'alumno_' . $alumno->id . '.' . $fotografia->getClientOriginalExtension();
        $ruta = $fotografia->storeAs('fotografias', $name, 'public');
        $fotografia->storeAs('fotografias', $name, 'local');
        return $ruta;
    }

    /**
     * Método privado para subir el PDF del CV
     * Similar a uploadPdf() en PeinadoController
     */
    private function uploadPdf(Request $request, Alumno $alumno): string
    {
        $pdf = $request->file('cv_pdf');
        $name = 'alumno_' . $alumno->id . '.pdf';
        $ruta = $pdf->storeAs('cvs', $name, 'public');
        $pdf->storeAs('cvs_privados', $name, 'local');
        return $ruta;
    }
}
