<?php

namespace App\Http\Controllers;

use App\Models\Alumno;
use Illuminate\Http\Request;

class AlumnoController extends Controller
{
    public function index()
    {
        $alumnos = Alumno::all();
        return view('alumnos.index', compact('alumnos'));
    }

    public function create()
    {
        return view('alumnos.create');
    }

    public function store(Request $request)
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

    public function show(Alumno $alumno)
    {
        return view('alumnos.show', compact('alumno'));
    }

    public function edit(Alumno $alumno)
    {
        return view('alumnos.edit', compact('alumno'));
    }

    public function update(Request $request, Alumno $alumno)
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

        $alumno->update($request->all());

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

    public function destroy(Alumno $alumno)
    {
        $alumno->delete();
        return redirect()->route('alumnos.index')->with('success', 'Alumno eliminado correctamente');
    }
}