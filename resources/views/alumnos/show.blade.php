<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CV - {{ $alumno->nombre }} {{ $alumno->apellidos }}</title>
    <style>
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
        <div class="cv-header">
            @if($alumno->fotografia)
                <img src="{{ asset('storage/' . $alumno->fotografia) }}" alt="Foto de {{ $alumno->nombre }}" class="cv-photo">
            @else
                <img src="https://via.placeholder.com/150" alt="Sin foto" class="cv-photo">
            @endif
            <h1 class="cv-name">{{ $alumno->nombre }} {{ $alumno->apellidos }}</h1>
            <div class="cv-contact">
                Correo: {{ $alumno->correo }} | Teléfono: {{ $alumno->telefono }}
            </div>
        </div>

        <div class="cv-body">
            <div class="cv-section">
                <h2 class="cv-section-title">Información Personal</h2>
                <div class="cv-info">
                    <div class="cv-info-item">
                        <span class="cv-info-label">Fecha de Nacimiento</span>
                        <span class="cv-info-value">{{ \Carbon\Carbon::parse($alumno->fecha_nacimiento)->format('d/m/Y') }}</span>
                    </div>
                    <div class="cv-info-item">
                        <span class="cv-info-label">Nota Media</span>
                        <span class="nota-badge">{{ $alumno->nota_media }}</span>
                    </div>
                </div>
            </div>

            @if($alumno->experiencia)
                <div class="cv-section">
                    <h2 class="cv-section-title">Experiencia</h2>
                    <p class="cv-text">{{ $alumno->experiencia }}</p>
                </div>
            @endif

            @if($alumno->formacion)
                <div class="cv-section">
                    <h2 class="cv-section-title">Formación</h2>
                    <p class="cv-text">{{ $alumno->formacion }}</p>
                </div>
            @endif

            @if($alumno->habilidades)
                <div class="cv-section">
                    <h2 class="cv-section-title">Habilidades</h2>
                    <p class="cv-text">{{ $alumno->habilidades }}</p>
                </div>
            @endif

            @if(file_exists(public_path('storage/cvs/alumno_' . $alumno->id . '.pdf')))
                <div class="cv-section">
                    <h2 class="cv-section-title">Curriculum Vitae (PDF)</h2>
                    <a href="{{ asset('storage/cvs/alumno_' . $alumno->id . '.pdf') }}" download class="btn btn-primary" style="display: inline-block; text-decoration: none;">
                        Descargar CV en PDF
                    </a>
                </div>
            @endif        
        </div>

        <div class="btn-container">
            <a href="{{ route('alumnos.edit', $alumno) }}" class="btn btn-primary">Editar CV</a>
            <a href="{{ route('alumnos.index') }}" class="btn btn-secondary">Volver al listado</a>
        </div>
    </div>
</body>
</html>