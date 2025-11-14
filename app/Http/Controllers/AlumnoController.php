<?php

namespace App\Http\Controllers;

use App\Models\Alumno;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AlumnoController extends Controller
{
    /**
     * Muestra el listado de todos los alumnos
     */
    public function index(): View
    {
        $alumnos = Alumno::all();
        return view('alumnos.index', compact('alumnos'));
    }

    /**
     * Muestra el formulario para crear un nuevo alumno
     */
    public function create(): View
    {
        return view('alumnos.create');
    }

    /**
     * Guarda un nuevo alumno en la base de datos
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'nombre' => 'required|max:255',
            'apellidos' => 'required|max:255',
            'telefono' => 'required|max:255',
            'correo' => 'required|email|unique:alumnos',
            'fecha_nacimiento' => 'required|date',
            'nota_media' => 'required|numeric|min:0|max:10',
            'fotografia' => 'nullable|image|max:2048',
            'cv_pdf' => 'nullable|mimes:pdf|max:10240'
        ]);

        $data = $request->except(['fotografia', 'cv_pdf']);
        $alumno = Alumno::create($data);

        if ($request->hasFile('fotografia')) {
            $alumno->fotografia = $request->file('fotografia')->store('fotografias', 'public');
            $alumno->save();
        }

        if ($request->hasFile('cv_pdf')) {
            $nombrePdf = 'alumno_' . $alumno->id . '.pdf';
            $request->file('cv_pdf')->storeAs('cvs', $nombrePdf, 'public');
            $request->file('cv_pdf')->storeAs('cvs_privados', $nombrePdf);
        }

        return redirect()->route('alumnos.index')->with('success', 'Alumno creado correctamente');
    }

    /**
     * Muestra el CV completo de un alumno específico
     */
    public function show(Alumno $alumno): View
    {
        return view('alumnos.show', compact('alumno'));
    }

    /**
     * Muestra el formulario para editar un alumno existente
     */
    public function edit(Alumno $alumno): View
    {
        return view('alumnos.edit', compact('alumno'));
    }

    /**
     * Actualiza los datos de un alumno existente
     */
    public function update(Request $request, Alumno $alumno): RedirectResponse
    {
        $request->validate([
            'nombre' => 'required|max:255',
            'apellidos' => 'required|max:255',
            'telefono' => 'required|max:255',
            'correo' => 'required|email|unique:alumnos,correo,' . $alumno->id,
            'fecha_nacimiento' => 'required|date',
            'nota_media' => 'required|numeric|min:0|max:10',
            'fotografia' => 'nullable|image|max:2048',
            'cv_pdf' => 'nullable|mimes:pdf|max:10240'
        ]);

        $data = $request->except(['fotografia', 'cv_pdf']);
        $alumno->update($data);

        if ($request->hasFile('fotografia')) {
            $alumno->fotografia = $request->file('fotografia')->store('fotografias', 'public');
            $alumno->save();
        }

        if ($request->hasFile('cv_pdf')) {
            $nombrePdf = 'alumno_' . $alumno->id . '.pdf';
            $request->file('cv_pdf')->storeAs('cvs', $nombrePdf, 'public');
            $request->file('cv_pdf')->storeAs('cvs_privados', $nombrePdf);
        }

        return redirect()->route('alumnos.index')->with('success', 'Alumno actualizado correctamente');
    }

    /**
     * Elimina un alumno de la base de datos
     */
    public function destroy(Alumno $alumno): RedirectResponse
    {
        $alumno->delete();
        return redirect()->route('alumnos.index')->with('success', 'Alumno eliminado correctamente');
    }
}