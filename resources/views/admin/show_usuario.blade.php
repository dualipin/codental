<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Listado de Pacientes</title>
	<style>
		* {
			box-sizing: border-box;
		}

		body {
			margin: 0;
			font-family: Arial, Helvetica, sans-serif;
			background: #f5f7fb;
			color: #1f2937;
		}

		.container {
			max-width: 1000px;
			margin: 0 auto;
			padding: 32px 20px;
		}

		.header {
			margin-bottom: 24px;
		}

		.header h1 {
			margin: 0 0 8px;
			font-size: 28px;
		}

		.header p {
			margin: 0;
			color: #6b7280;
		}

		.grid {
			display: flex;
			flex-direction: column;
			gap: 14px;
		}

		.card {
			background: #fff;
			border-radius: 16px;
			padding: 18px 20px;
			display: flex;
			align-items: center;
			gap: 16px;
			box-shadow: 0 8px 24px rgba(15, 23, 42, 0.08);
			border: 1px solid #e5e7eb;
			width: 100%;
		}

		.card img {
			width: 72px;
			height: 72px;
			border-radius: 50%;
			object-fit: cover;
			flex-shrink: 0;
			border: 3px solid #e0f2fe;
		}

		.info {
			min-width: 0;
		}

		.info .nombre {
			margin: 0 0 6px;
			font-size: 18px;
			font-weight: 700;
		}

		.info .dentista {
			margin: 0;
			color: #4b5563;
			font-size: 14px;
		}

		.meta {
			display: grid;
			grid-template-columns: repeat(2, minmax(0, 1fr));
			gap: 8px 16px;
			margin-top: 10px;
			font-size: 13px;
			color: #374151;
		}

		.meta strong {
			color: #0f172a;
		}

		.label {
			display: inline-block;
			margin-bottom: 8px;
			font-size: 12px;
			font-weight: 700;
			text-transform: uppercase;
			letter-spacing: 0.04em;
			color: #0284c7;
		}
	</style>
</head>
<body>
    @include('layouts.headerprof')
	<div class="container">
		<div class="header">
			<h1>Listado de Usuarios</h1>
			<p>Usuarios registrados en la base de datos.</p>
			
        </div>
		<div class="grid">
			@foreach ($usuarios as $usuario)
            
				<div class="card">
					<img src="{{ $usuario->foto }}" alt="Foto de {{ $usuario->nombre }}">
					<div class="info">
						
						<p class="nombre">{{ $usuario->nom }}</p>
						<div class="meta">
							<div><strong>Sexo:</strong> {{ $usuario->sex }}</div>
							<div><strong>Teléfono:</strong> {{ $usuario->tel }}</div>
							<div><strong>Correo:</strong> {{ $usuario->cor }}</div>
							<div><strong>Ubicación:</strong> {{ $usuario->mun }}</div>
							
						</div>
					</div>
				</div>
			@endforeach
			@if (!$usuarios)
				<div class="card">
					<div class="info">
						<p class="nombre">No hay usuarios registrados</p>
						<p class="dentista">Aún no existen registros en la tabla usuarios.</p>
					</div>
				</div>
			@endif
		</div>
	</div>
</body>
</html>
