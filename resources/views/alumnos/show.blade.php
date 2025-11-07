<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CV - {{ $alumno->nombre }} {{ $alumno->apellidos }}</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
    <div class="cv">
        @if($alumno->fotografia)
            <img src="{{ asset('storage/' . $alumno->fotografia) }}" class="foto">
        @endif

        <h1>{{ $alumno->nombre }} {{ $alumno->apellidos }}</h1>
        
        <div class="seccion">
            <p><strong>Correo:</strong> {{ $alumno->correo }}</p>
            <p><strong>Teléfono:</strong> {{ $alumno->telefono }}</p>
            <p><strong>Fecha de Nacimiento:</strong> {{ date('d/m/Y', strtotime($alumno->fecha_nacimiento)) }}</p>
            <p><strong>Nota Media:</strong> {{ $alumno->nota_media }}</p>
        </div>

        @if($alumno->experiencia)
        <div class="seccion">
            <h2>Experiencia</h2>
            <p>{{ $alumno->experiencia }}</p>
        </div>
        @endif

        @if($alumno->formacion)
        <div class="seccion">
            <h2>Formación</h2>
            <p>{{ $alumno->formacion }}</p>
        </div>
        @endif

        @if($alumno->habilidades)
        <div class="seccion">
            <h2>Habilidades</h2>
            <p>{{ $alumno->habilidades }}</p>
        </div>
        @endif

        @if(file_exists(public_path('storage/cvs/alumno_' . $alumno->id . '.pdf')))
        <div class="seccion">
            <h2>CV en PDF</h2>
            <a href="{{ asset('storage/cvs/alumno_' . $alumno->id . '.pdf') }}" download class="btn">Descargar PDF</a>
        </div>
        @endif
    </div>

    <a href="{{ route('alumnos.edit', $alumno) }}" class="btn">Editar</a>
    <a href="{{ route('alumnos.index') }}" class="btn">Volver al listado</a>
</body>
</html>