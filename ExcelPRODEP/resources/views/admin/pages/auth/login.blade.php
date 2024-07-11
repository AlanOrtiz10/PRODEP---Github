<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar sesión</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f8f9fa;
        }
        .login-container {
            background: white;
            padding: 50px;
            border-radius: 0;
            box-shadow: none;
            width: 100%;
            max-width: 600px;
        }
        .login-container .btn-primary {
            width: 100%;
        }
        .login-container h2 {
            margin-bottom: 30px;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2 class="text-center">Inicio de sesión</h2>

        @if($errors->any())
            <div class="alert alert-danger">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form action="{{ route('login') }}" method="POST">
            @csrf 
            <div class="form-group">
                <label for="correo_curp">Correo o CURP:</label>
                <input type="text" class="form-control" id="correo_curp" name="correo_curp" required>
            </div>
        
            <div class="form-group">
                <label for="password">Contraseña:</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
        
            <button type="submit" class="btn btn-primary">Iniciar sesión</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
