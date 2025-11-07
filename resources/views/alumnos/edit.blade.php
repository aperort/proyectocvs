<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Alumno</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
    <h1>Editar Alumno: {{ $alumno->nombre }} {{ $alumno->apellidos }}</h1>

    <form action="{{ route('alumnos.update', $alumno) }}" method="POST" enctype="multipart/form-data" class="formulario">
        @csrf
        @method('PUT')

        <div class="campo">
            <label>Nombre *</label>
            <input type="text" name="nombre" value="{{ old('nombre', $alumno->nombre) }}" required>
            @error('nombre')<div class="error">{{ $message }}</div>@enderror
        </div>

        <div class="campo">
            <label>Apellidos *</label>
            <input type="text" name="apellidos" value="{{ old('apellidos', $alumno->apellidos) }}" required>
            @error('apellidos')<div class="error">{{ $message }}</div>@enderror
        </div>

        <div class="campo">
            <label>Teléfono *</label>
            <input type="text" name="telefono" value="{{ old('telefono', $alumno->telefono) }}" required>
            @error('telefono')<div class="error">{{ $message }}</div>@enderror
        </div>

        <div class="campo">
            <label>Correo *</label>
            <input type="email" name="correo" value="{{ old('correo', $alumno->correo) }}" required>
            @error('correo')<div class="error">{{ $message }}</div>@enderror
        </div>

        <div class="campo">
            <label>Fecha de Nacimiento *</label>
            <input type="date" name="fecha_nacimiento" value="{{ old('fecha_nacimiento', $alumno->fecha_nacimiento) }}" required>
            @error('fecha_nacimiento')<div class="error">{{ $message }}</div>@enderror
        </div>

        <div class="campo">
            <label>Nota Media (0-10) *</label>
            <input type="number" name="nota_media" step="0.01" min="0" max="10" value="{{ old('nota_media', $alumno->nota_media) }}" required>
            @error('nota_media')<div class="error">{{ $message }}</div>@enderror
        </div>

        <div class="campo">
            <label>Experiencia</label>
            <textarea name="experiencia">{{ old('experiencia', $alumno->experiencia) }}</textarea>
            @error('experiencia')<div class="error">{{ $message }}</div>@enderror
        </div>

        <div class="campo">
            <label>Formación</label>
            <textarea name="formacion">{{ old('formacion', $alumno->formacion) }}</textarea>
            @error('formacion')<div class="error">{{ $message }}</div>@enderror
        </div>

        <div class="campo">
            <label>Habilidades</label>
            <textarea name="habilidades">{{ old('habilidades', $alumno->habilidades) }}</textarea>
            @error('habilidades')<div class="error">{{ $message }}</div>@enderror
        </div>

        <div class="campo">
            <label>Fotografía</label>
            @if($alumno->fotografia)
                <img src="{{ asset('storage/' . $alumno->fotografia) }}" class="foto-actual">
            @endif
            <input type="file" name="fotografia" accept="image/*">
            @error('fotografia')<div class="error">{{ $message }}</div>@enderror
        </div>

        <div class="campo">
            <label>CV en PDF</label>
            <input type="file" name="cv_pdf" accept=".pdf">
            @error('cv_pdf')<div class="error">{{ $message }}</div>@enderror
        </div>

        <button type="submit" class="btn">Actualizar</button>
        <a href="{{ route('alumnos.show', $alumno) }}" class="btn">Cancelar</a>
    </form>
</body>
</html>