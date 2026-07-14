<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Usuario</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f6f9; padding: 20px; }
        .container { max-width: 600px; margin: 0 auto; background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; margin-bottom: 5px; font-weight: bold; }
        .form-group input, .form-group select { width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
        .btn-submit { background-color: #0275d8; color: white; padding: 10px 15px; border: none; border-radius: 4px; cursor: pointer; width: 100%; font-size: 16px; }
        .alert-error { background-color: #f2dede; color: #a94442; padding: 15px; border-radius: 4px; margin-bottom: 20px; }
    </style>
</head>
<body>
@include('layouts.headerprof')
<div class="container">
    @if(session('rol') === 'admin')
        
        <h2>Registrar Nuevo Usuario (CoDentaL)</h2>

        @if ($errors->any())
            <div class="alert-error">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="/admin/registro_usuario" method="POST">
            @csrf
            <div class="form-group">
                <label>Rol de Usuario (Ajustado a máximo 5 letras):</label>
                <select name="rol" id="rol-select" required>
                    <option value="dent">Dentista (dent)</option>
                    <option value="recep">Recepcionista (recep)</option>
                    <option value="admin">Administrador (admin)</option>
                </select>
            </div>

            <div class="form-group">
                <label>Cédula (Máx 8 caracteres):</label>
                <input type="text" name="ced" id="ced-input" maxlength="8" value="{{ old('ced') }}" required>
            </div>

            <div class="form-group">
                <label>Nombre:</label>
                <input type="text" name="nom" maxlength="20" value="{{ old('nom') }}" required>
            </div>

            <div class="form-group">
                <label>Apellido Paterno:</label>
                <input type="text" name="app" maxlength="20" value="{{ old('app') }}" required>
            </div>

            <div class="form-group">
                <label>Apellido Materno:</label>
                <input type="text" name="apm" maxlength="20" value="{{ old('apm') }}" required>
            </div>

            <div class="form-group">
                <label>Sexo:</label>
                <select name="sex" required>
                    <option value="M">Masculino</option>
                    <option value="F">Femenino</option>
                </select>
            </div>

            <div class="form-group">
                <label>Especialidad:</label>
                <input type="text" name="esp" maxlength="15" placeholder="Ej: General, Ortodoncia" value="{{ old('esp') }}" required>
            </div>

            <div class="form-group">
                <label>Fecha de Nacimiento:</label>
                <input type="date" name="nac" value="{{ old('nac') }}" required>
            </div>

            <div class="form-group">
                <label>Estado Civil:</label>
                <input type="text" name="civ" maxlength="10" placeholder="Soltero, Casado..." value="{{ old('civ') }}" required>
            </div>

            <div class="form-group">
                <label>Dirección:</label>
                <input type="text" name="dic" maxlength="50" value="{{ old('dic') }}" required>
            </div>

            <div class="form-group">
                <label>Estado:</label>
                <input type="text" name="est" maxlength="15" value="{{ old('est') }}" required>
            </div>

            <div class="form-group">
                <label>Municipio:</label>
                <input type="text" name="mun" maxlength="20" value="{{ old('mun') }}" required>
            </div>

            <div class="form-group">
                <label>Teléfono (10 dígitos):</label>
                <input type="text" name="tel" maxlength="10" value="{{ old('tel') }}" required>
            </div>

            <div class="form-group">
                <label>Correo Electrónico:</label>
                <input type="email" name="cor" maxlength="35" value="{{ old('cor') }}" required>
            </div>

            

            <div class="form-group" style="background: #e9ecef; padding: 15px; border-radius: 4px; margin-top: 20px;">
                <h3>Credenciales de Acceso</h3>
                
                <div class="form-group">
                    <label>Nombre de Usuario (Login Username):</label>
                    <input type="text" name="usuario_login" maxlength="30" value="{{ old('usuario_login') }}" required>
                </div>

                <div class="form-group">
                    <label>Contraseña:</label>
                    <input type="password" name="contrasena_login" required>
                </div>
            </div>

            <button type="submit" class="btn-submit">Guardar Todo en la Base de Datos</button>
        </form>

    @else
        <div class="alert-error" style="text-align: center;">
            <h3>🛑 Acceso Denegado</h3>
            <p>Solo los usuarios con rol de administrador pueden registrar personal.</p>
            <a href="/inicio">Volver al Inicio</a>
        </div>
    @endif
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var rolSelect = document.getElementById('rol-select');
        var cedInput = document.getElementById('ced-input');

        function evaluarRol() {
            if (rolSelect.value === 'recep') {
                // Bloquea el input, limpia lo que esté escrito y quita la obligatoriedad
                cedInput.disabled = true;
                cedInput.value = '';
                cedInput.placeholder = 'No requerida para Recepcionista';
                cedInput.removeAttribute('required');
            } else {
                // Lo vuelve a activar si eligen "Dentista"
                cedInput.disabled = false;
                cedInput.placeholder = 'Ingresa la cédula';
                cedInput.setAttribute('required', 'required');
            }
        }

        // Escucha cuando el usuario cambia la opción del select
        rolSelect.addEventListener('change', evaluarRol);

        // Ejecuta la función al cargar la página por si hay valores precargados
        evaluarRol();
    });
</script>
</body>
</html>