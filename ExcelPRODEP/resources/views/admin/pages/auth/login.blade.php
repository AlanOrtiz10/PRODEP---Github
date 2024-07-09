<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar sesión</title>
</head>
<body>
    @if($errors->any())
        <div class="alert alert-danger">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <form action="{{ route('login') }}" method="POST">
        @csrf 
        <label for="correo_curp">Correo o CURP:</label><br>
        <input type="text" name="correo_curp" required><br><br>
    
        <label for="password">Contraseña:</label><br>
        <input type="password" name="password" required><br><br>
    
        <input type="submit" value="Iniciar sesión">
    </form>
    
</body>
</html>
