<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Alumnos</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
    <h1>Lista de Alumnos</h1>

    @if(session('success'))
        <div class="mensaje">{{ session('success') }}</div>
    @endif

    <a href="{{ route('alumnos.create') }}" class="btn">Crear Nuevo Alumno</a>

    @if($alumnos->count() > 0)
        <table>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Apellidos</th>
                <th>Correo</th>
                <th>Teléfono</th>
                <th>Nota Media</th>
                <th>Acciones</th>
            </tr>
            @foreach($alumnos as $alumno)
                <tr>
                    <td>{{ $alumno->id }}</td>
                    <td>{{ $alumno->nombre }}</td>
                    <td>{{ $alumno->apellidos }}</td>
                    <td>{{ $alumno->correo }}</td>
                    <td>{{ $alumno->telefono }}</td>
                    <td>{{ $alumno->nota_media }}</td>
                    <td>
                        <a href="{{ route('alumnos.show', $alumno) }}" class="btn">Ver</a>
                        <a href="{{ route('alumnos.edit', $alumno) }}" class="btn">Editar</a>
                        <form action="{{ route('alumnos.destroy', $alumno) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn" onclick="return confirm('¿Estás seguro?')">Eliminar</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </table>
    @else
        <p>No hay alumnos registrados.</p>
    @endif
</body>
</html>