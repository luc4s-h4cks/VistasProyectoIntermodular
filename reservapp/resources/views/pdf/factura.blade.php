<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <title>Factura Cita #{{ $cita->id_cita }}</title>
    <style>
        /* Tipografía y cuerpo */
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #333;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 100%;
            padding: 20px;
        }

        header,
        footer {
            width: 100%;
            text-align: center;
            position: fixed;
        }

        header {
            top: 0;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
        }

        footer {
            bottom: 0;
            border-top: 2px solid #000;
            font-size: 10px;
            color: #666;
            padding-top: 5px;
        }

        h1 {
            font-size: 24px;
            margin: 0;
        }

        h2,
        h3 {
            margin: 5px 0;
        }

        .info,
        .totals {
            width: 100%;
            margin-top: 20px;
            display: flex;
            justify-content: space-between;
        }

        .info div {
            width: 48%;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            border: 1px solid #ccc;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        .totals {
            margin-top: 20px;
            flex-direction: column;
            align-items: flex-end;
        }

        .totals div {
            display: flex;
            justify-content: space-between;
            width: 250px;
            padding: 2px 0;
        }

        .totals .grand-total {
            font-weight: bold;
            font-size: 16px;
            border-top: 2px solid #000;
            padding-top: 5px;
        }
    </style>
</head>

<body>
    <header>
        <h1>{{ config('app.name', 'Mi Taller') }}</h1>
        <p>{{ auth()->user()->taller->direccion ?? 'Dirección del taller' }} | Tel:
            {{ auth()->user()->taller->telefono ?? '000000000' }} | Email:
            {{ auth()->user()->taller->email ?? 'taller@email.com' }}</p>
    </header>

    <footer>
        <p>Factura generada automáticamente - Gracias por confiar en nosotros</p>
    </footer>

    <div class="container">
        <h2>Factura</h2>
        <p><strong>Factura N°:</strong> {{ $cita->id_cita }}</p>
        <p><strong>Fecha emisión:</strong> {{ now()->format('d/m/Y') }}</p>

        <div class="info">
            <div>
                <h3>Cliente</h3>
                <p><strong>Nombre:</strong> {{ $cita->coche->usuario->nombre }} {{ $cita->coche->usuario->apellidos }}
                </p>
                <p><strong>Email:</strong> {{ $cita->coche->usuario->email }}</p>
            </div>
            <div>
                <h3>Coche y Cita</h3>
                <p><strong>Marca y Modelo:</strong> {{ $cita->coche->marca }} {{ $cita->coche->modelo }}</p>
                <p><strong>Matrícula:</strong> {{ $cita->coche->matricula ?? 'N/A' }}</p>
                <p><strong>Fecha cita:</strong> {{ $cita->fecha }}</p>
                <p><strong>Motivo:</strong> {{ $cita->motivo }}</p>
            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Descripción</th>
                    <th>Precio (€)</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($items as $item)
                    <tr>
                        <td>{{ $item['nombre'] }}</td>
                        <td>{{ number_format($item['precio'], 2) }} €</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="totals">
            <div><span>Subtotal:</span> <span>{{ number_format($subtotal, 2) }} €</span></div>
            <div><span>IVA (21%):</span> <span>{{ number_format($iva, 2) }} €</span></div>
            <div class="grand-total"><span>Total:</span> <span>{{ number_format($total, 2) }} €</span></div>
        </div>
    </div>
</body>

</html>
