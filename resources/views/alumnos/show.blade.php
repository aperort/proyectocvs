<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    {{-- Título dinámico con el nombre del alumno --}}
    <title>CV - {{ $alumno->nombre }} {{ $alumno->apellidos }}</title>
    <style>
        /* Estilos CSS para el CV - Diseño profesional con degradado */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f4;
            padding: 20px;
        }
        .cv-container {
            max-width: 900px;
            margin: 0 auto;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        .cv-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 40px;
            text-align: center;
        }
        .cv-photo {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            border: 5px solid white;
            object-fit: cover;
            margin-bottom: 20px;
        }
        .cv-name {
            font-size: 32px;
            margin-bottom: 10px;
        }
        .cv-contact {
            font-size: 14px;
            opacity: 0.9;
        }
        .cv-body {
            padding: 40px;
        }
        .cv-section {
            margin-bottom: 30px;
        }
        .cv-section-title {
            font-size: 22px;
            color: #667eea;
            border-bottom: 2px solid #667eea;
            padding-bottom: 10px;
            margin-bottom: 15px;
        }
        .cv-info {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
            margin-bottom: 20px;
        }
        .cv-info-item {
            display: flex;
            flex-direction: column;
        }
        .cv-info-label {
            font-weight: bold;
            color: #555;
            font-size: 14px;
            margin-bottom: 5px;
        }
        .cv-info-value {
            color: #333;
            font-size: 16px;
        }
        .cv-text {
            line-height: 1.6;
            color: #555;
            white-space: pre-line;
        }
        .btn-container {
            display: flex;
            gap: 10px;
            margin-top: 30px;
            padding: 0 40px 40px;
        }
        .btn {
            padding: 10px 20px;
            border-radius: 4px;
            text-decoration: none;
            display: inline-block;
            cursor: pointer;
            border: none;
        }
        .btn-primary {
            background-color: #667eea;
            color: white;
        }
        .btn-secondary {
            background-color: #6c757d;
            color: white;
        }
        .btn:hover {
            opacity: 0.9;
        }
        .nota-badge {
            display: inline-block;
            background-color: #28a745;
            color: white;
            padding: 5px 15px;
            border-radius: 20px;
            font-weight: bold;
            font-size: 18px;
        }
    </style>
</head>
<body>
    <div class="cv-container">
        {{-- CABECERA DEL CV: Foto, nombre y contacto con fondo degradado --}}
        <div class="cv-header">
            {{-- MOSTRAR FOTOGRAFÍA: Si existe usa la foto guardada, si no usa placeholder --}}
            @if($alumno->fotografia)
                {{-- asset('storage/...') genera URL pública para archivos en storage/app/public --}}
                <img src="{{ asset('storage/' . $alumno->fotografia) }}" alt="Foto de {{ $alumno->nombre }}" class="cv-photo">
            @else
                {{-- Imagen de marcador de posición si no hay foto --}}
                <img src="https://via.placeholder.com/150" alt="Sin foto" class="cv-photo">
            @endif
            
            {{-- Nombre completo del alumno --}}
            <h1 class="cv-name">{{ $alumno->nombre }} {{ $alumno->apellidos }}</h1>
            
            {{-- Información de contacto --}}
            <div class="cv-contact">
                Correo: {{ $alumno->correo }} | Teléfono: {{ $alumno->telefono }}
            </div>
        </div>

        {{-- CUERPO DEL CV: Secciones con información detallada --}}
        <div class="cv-body">
            
            {{-- SECCIÓN: Información Personal (siempre se muestra) --}}
            <div class="cv-section">
                <h2 class="cv-section-title">Información Personal</h2>
                <div class="cv-info">
                    <div class="cv-info-item">
                        <span class="cv-info-label">Fecha de Nacimiento</span>
                        {{-- Carbon formatea la fecha de YYYY-MM-DD a DD/MM/YYYY --}}
                        <span class="cv-info-value">{{ \Carbon\Carbon::parse($alumno->fecha_nacimiento)->format('d/m/Y') }}</span>
                    </div>
                    <div class="cv-info-item">
                        <span class="cv-info-label">Nota Media</span>
                        {{-- Badge verde destacando la nota media --}}
                        <span class="nota-badge">{{ $alumno->nota_media }}</span>
                    </div>
                </div>
            </div>

            {{-- SECCIÓN: Experiencia (solo si tiene contenido) --}}
            @if($alumno->experiencia)
                <div class="cv-section">
                    <h2 class="cv-section-title">Experiencia</h2>
                    {{-- white-space: pre-line mantiene los saltos de línea del textarea --}}
                    <p class="cv-text">{{ $alumno->experiencia }}</p>
                </div>
            @endif

            {{-- SECCIÓN: Formación (solo si tiene contenido) --}}
            @if($alumno->formacion)
                <div class="cv-section">
                    <h2 class="cv-section-title">Formación</h2>
                    <p class="cv-text">{{ $alumno->formacion }}</p>
                </div>
            @endif

            {{-- SECCIÓN: Habilidades (solo si tiene contenido) --}}
            @if($alumno->habilidades)
                <div class="cv-section">
                    <h2 class="cv-section-title">Habilidades</h2>
                    <p class="cv-text">{{ $alumno->habilidades }}</p>
                </div>
            @endif

            {{-- 
                SECCIÓN: PDF del CV (solo si existe el archivo)
                
                VERIFICACIÓN: file_exists() comprueba físicamente si el PDF existe en el servidor
                public_path('storage/cvs/...') apunta a: public/storage/cvs/alumno_X.pdf
            --}}
            @if(file_exists(public_path('storage/cvs/alumno_' . $alumno->id . '.pdf')))
                <div class="cv-section">
                    <h2 class="cv-section-title">Curriculum Vitae (PDF)</h2>
                    {{-- 
                        Botón de descarga:
                        - asset() genera la URL pública del PDF
                        - download fuerza la descarga en lugar de abrir en el navegador
                    --}}
                    <a href="{{ asset('storage/cvs/alumno_' . $alumno->id . '.pdf') }}" download class="btn btn-primary" style="display: inline-block; text-decoration: none;">
                        Descargar CV en PDF
                    </a>
                </div>
            @endif          
        </div>

        {{-- BOTONES DE ACCIÓN: Editar o volver al listado --}}
        <div class="btn-container">
            {{-- Editar: GET /alumnos/{id}/edit --}}
            <a href="{{ route('alumnos.edit', $alumno) }}" class="btn btn-primary">Editar CV</a>
            
            {{-- Volver al listado: GET /alumnos --}}
            <a href="{{ route('alumnos.index') }}" class="btn btn-secondary">Volver al listado</a>
        </div>
    </div>
</body>
</html>