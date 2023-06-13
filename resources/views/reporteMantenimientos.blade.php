<!DOCTYPE html>
<html>
<head>
	<title>Reporte de mantenimientos</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!-- Estilos de Bootstrap -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
	<style>
		.container {
			width: 100%;
			margin: 0 auto;
		}
		.header {
			background-color: #007bff;
			color: #fff;
			padding: 10px;
            margin: 0 auto;
			margin-bottom: 20px;
			border-radius: 5px;
		}
		.header h1 {
			margin-bottom: 5px;
			font-weight: bold;
			text-transform: uppercase;
		}
		.header p {
			margin-bottom: 0;
		}
		.table td, .table th {
			border: 1px solid #dee2e6;
			padding: 8px;
		}
		.table th {
			background-color: #007bff;
			color: #fff;
			text-align: center;
			vertical-align: middle;
			font-weight: bold;
			text-transform: uppercase;
            font-size: 14px;
		}
		.table td {
			vertical-align: middle;
			text-align: left;
            font-size: 12px;
		}

        @page
        {
            margin: 1cm 0cm;
            font-size: 1em;
        }
	</style>
</head>
<body>
	<div class="container">
		<div class="header">
			<h1 class="text-center">Reporte de mantenimientos</h1>
			<p class="text-center">Encargado: {{ $user->name }}</p>
		</div>
        @if (count($mantenimientos) == 0)

            <div class="alert alert-danger">
                <strong>No hay mantenimientos registrados en el rango de fechas seleccionado.</strong>
            </div>

        @else

		<table class="table">
			<thead>
				<tr>
					<th>Fecha</th>
					<th>Equipo</th>
					<th>Tipo de mantenimiento</th>
					<th>Descripción</th>
				</tr>
			</thead>
			<tbody>
                    @foreach($mantenimientos as $mantenimiento)
                    <tr>
                        <td>{{ ($mantenimiento->created_at)->format('d/m/Y') }}</td>
                        <td>{{ $mantenimiento->cpu_id ? 'CPU' : 'Impresora' }}</td>
                        <td>{{ $mantenimiento->maintenance_type->type }}</td>
                        <td>{{ $mantenimiento->solution }}</td>
                    </tr>
                    @endforeach
			</tbody>
		</table>

        @endif
	</div>
</body>
</html>
