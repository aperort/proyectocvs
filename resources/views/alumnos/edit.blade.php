<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Alumno</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            padding: 20px;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        h1 {
            color: #333;
            margin-bottom: 20px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            color: #555;
            font-weight: bold;
        }
        input[type="text"],
        input[type="email"],
        input[type="tel"],
        input[type="date"],
        input[type="number"],
        input[type="file"],
        textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
        }
        textarea {
            min-height: 100px;
            resize: vertical;
        }
        .error {
            color: #dc3545;
            font-size: 12px;
            margin-top: 5px;
        }
        .current-photo {
            max-width: 150px;
            margin-top: 10px;
            border-radius: 8px;
        }
        .pdf-preview {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 4px;
            margin-bottom: 10px;
            border-left: 4px solid #28a745;
        }
        .pdf-info {
            margin-bottom: 10px;
            font-size: 14px;
            color: #333;
        }
        .pdf-buttons {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }
        .btn-small {
            background-color: #007bff;
            color: white;
            padding: 8px 15px;
            text-decoration: none;
            border-radius: 4px;
            display: inline-block;
            font-size: 14px;
            transition: all 0.3s ease;
        }
        .btn-small:hover {
            background-color: #0056b3;
        }
        .btn-download {
            background-color: #28a745;
        }
        .btn-download:hover {
            background-color: #218838;
        }
        .buttons {
            display: flex;
            gap: 10px;
            margin-top: 20px;
        }
        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
        }
        .btn-primary {
            background-color: #007bff;
            color: white;
        }
        .btn-primary:hover {
            background-color: #0056b3;
        }
        .btn-secondary {
            background-color: #6c757d;
            color: white;
        }
        .btn-secondary:hover {
            background-color: #545b62;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Editar Alumno: {{ $alumno->nombre }} {{ $alumno->apellidos }}</h1>

        <form action="{{ route('alumnos.update', $alumno) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="nombre">Nombre *</label>
                <input type="text" id="nombre" name="nombre" value="{{ old('nombre', $alumno->nombre) }}" required>
                @error('nombre')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="apellidos">Apellidos *</label>
                <input type="text" id="apellidos" name="apellidos" value="{{ old('apellidos', $alumno->apellidos) }}" required>
                @error('apellidos')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="telefono">Teléfono *</label>
                <input type="tel" id="telefono" name="telefono" value="{{ old('telefono', $alumno->telefono) }}" required>
                @error('telefono')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="correo">Correo Electrónico *</label>
                <input type="email" id="correo" name="correo" value="{{ old('correo', $alumno->correo) }}" required>
                @error('correo')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="fecha_nacimiento">Fecha de Nacimiento *</label>
                <input type="date" id="fecha_nacimiento" name="fecha_nacimiento" value="{{ old('fecha_nacimiento', $alumno->fecha_nacimiento) }}" required>
                @error('fecha_nacimiento')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="nota_media">Nota Media (0-10) *</label>
                <input type="number" id="nota_media" name="nota_media" step="0.01" min="0" max="10" value="{{ old('nota_media', $alumno->nota_media) }}" required>
                @error('nota_media')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="experiencia">Experiencia</label>
                <textarea id="experiencia" name="experiencia">{{ old('experiencia', $alumno->experiencia) }}</textarea>
                @error('experiencia')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="formacion">Formación</label>
                <textarea id="formacion" name="formacion">{{ old('formacion', $alumno->formacion) }}</textarea>
                @error('formacion')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="habilidades">Habilidades</label>
                <textarea id="habilidades" name="habilidades">{{ old('habilidades', $alumno->habilidades) }}</textarea>
                @error('habilidades')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="fotografia">Fotografía</label>
                @if($alumno->fotografia)
                    <img src="{{ asset('storage/' . $alumno->fotografia) }}" alt="Foto actual" class="current-photo">
                    <p style="margin-top: 10px; font-size: 14px; color: #666;">Subir nueva imagen para reemplazar</p>
                @endif
                <input type="file" id="fotografia" name="fotografia" accept="image/*">
                @error('fotografia')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="cv_pdf">CV en PDF (opcional)</label>
                @if(file_exists(storage_path('app/public/cvs/alumno_' . $alumno->id . '.pdf')))
                    <div class="pdf-preview">
                        <p class="pdf-info">
                            <strong>PDF actual:</strong> alumno_{{ $alumno->id }}.pdf
                        </p>
                        <div class="pdf-buttons">
                            <a href="{{ asset('storage/cvs/alumno_' . $alumno->id . '.pdf') }}" target="_blank" class="btn-small">
                                Ver PDF actual
                            </a>
                            <a href="{{ route('alumnos.pdf', $alumno) }}" class="btn-small btn-download">
                                Descargar PDF
                            </a>
                        </div>
                        <p style="margin-top: 10px; font-size: 14px; color: #666;">Subir nuevo PDF para reemplazar el actual</p>
                    </div>
                @else
                    <p style="font-size: 14px; color: #666; margin-bottom: 10px;">No hay PDF cargado actualmente</p>
                @endif
                <input type="file" id="cv_pdf" name="cv_pdf" accept=".pdf">
                @error('cv_pdf')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>          

            <div class="buttons">
                <button type="submit" class="btn btn-primary">Actualizar Alumno</button>
                <a href="{{ route('alumnos.show', $alumno) }}" class="btn btn-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</body>
</html>