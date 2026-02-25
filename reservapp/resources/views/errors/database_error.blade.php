<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sin conexión — ReservApp</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">
    <style>
        *,
        *::before,
        *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        :root {
            --bg: #f8f7f4;
            --surface: #ffffff;
            --black: #16161a;
            --accent: #7c3aed;
            --accent-light: #f5f3ff;
            --muted: #71717a;
            --border: #e4e4e7;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: var(--bg);
            color: var(--black);
            min-height: 100vh;
            display: grid;
            place-items: center;
            padding: 2rem;
        }

        body::before {
            content: '';
            position: fixed;
            inset: 0;
            background-image: radial-gradient(circle, rgba(0, 0, 0, 0.07) 1px, transparent 1px);
            background-size: 28px 28px;
            z-index: 0;
            pointer-events: none;
        }

        .card {
            position: relative;
            z-index: 1;
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 24px;
            padding: 3rem 3.5rem;
            text-align: center;
            max-width: 480px;
            width: 100%;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 20px 40px -10px rgba(0, 0, 0, 0.08);
        }

        .logo {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            font-weight: 800;
            font-size: 1.1rem;
            color: var(--black);
            text-decoration: none;
            margin-bottom: 2.5rem;
        }

        .logo-dot {
            width: 8px;
            height: 8px;
            background: #2563eb;
            border-radius: 50%;
        }

        /* Icono base de datos con animación de "sin señal" */
        .icon-wrap {
            width: 72px;
            height: 72px;
            background: var(--accent-light);
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.75rem;
            position: relative;
        }

        .icon-wrap svg.db-icon {
            width: 34px;
            height: 34px;
            color: var(--accent);
        }

        /* Cruz de error sobre el icono */
        .icon-wrap .badge-error {
            position: absolute;
            top: -6px;
            right: -6px;
            width: 22px;
            height: 22px;
            background: #dc2626;
            border-radius: 50%;
            border: 2px solid var(--surface);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .badge-error svg {
            width: 10px;
            height: 10px;
            color: white;
        }

        /* Título y texto */
        .error-label {
            display: inline-block;
            font-size: 0.7rem;
            font-weight: 700;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            color: var(--accent);
            background: var(--accent-light);
            padding: 0.25rem 0.75rem;
            border-radius: 99px;
            margin-bottom: 1rem;
        }

        h1 {
            font-size: 1.5rem;
            font-weight: 800;
            margin-bottom: 0.625rem;
        }

        p {
            font-size: 0.9375rem;
            color: var(--muted);
            line-height: 1.65;
            margin-bottom: 1.75rem;
        }

        /* Indicadores de estado */
        .status-list {
            background: var(--bg);
            border: 1px solid var(--border);
            border-radius: 14px;
            padding: 1rem 1.25rem;
            margin-bottom: 2rem;
            text-align: left;
            display: flex;
            flex-direction: column;
            gap: 0.625rem;
        }

        .status-item {
            display: flex;
            align-items: center;
            gap: 0.625rem;
            font-size: 0.85rem;
            font-weight: 500;
        }

        .status-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            flex-shrink: 0;
        }

        .status-dot.ok {
            background: #16a34a;
        }

        .status-dot.error {
            background: #dc2626;
            animation: pulse-red 1s ease-in-out infinite;
        }

        .status-dot.warning {
            background: #d97706;
            animation: pulse-yellow 1.5s ease-in-out infinite;
        }

        @keyframes pulse-red {

            0%,
            100% {
                box-shadow: 0 0 0 0 rgba(220, 38, 38, 0.4);
            }

            50% {
                box-shadow: 0 0 0 4px rgba(220, 38, 38, 0);
            }
        }

        @keyframes pulse-yellow {

            0%,
            100% {
                box-shadow: 0 0 0 0 rgba(217, 119, 6, 0.4);
            }

            50% {
                box-shadow: 0 0 0 4px rgba(217, 119, 6, 0);
            }
        }

        .status-label {
            color: var(--black);
        }

        .status-value {
            margin-left: auto;
            font-size: 0.78rem;
        }

        .status-value.ok-text {
            color: #16a34a;
        }

        .status-value.err-text {
            color: #dc2626;
        }

        .status-value.warn-text {
            color: #d97706;
        }

        .btn-primary {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            width: 100%;
            padding: 0.8125rem 1.5rem;
            background: var(--black);
            color: #fff;
            font-family: inherit;
            font-weight: 600;
            font-size: 0.9375rem;
            text-decoration: none;
            border-radius: 12px;
            border: none;
            cursor: pointer;
            transition: background 0.15s, transform 0.15s;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.15), 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .btn-primary:hover {
            background: #27272a;
            transform: translateY(-1px);
        }

        .btn-primary svg {
            width: 16px;
            height: 16px;
            transition: transform 0.3s;
        }

        .btn-primary:hover svg {
            transform: rotate(180deg);
        }

        /* Contador de reintento automático */
        .auto-retry {
            margin-top: 1.25rem;
            font-size: 0.8rem;
            color: var(--muted);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.375rem;
        }

        .countdown {
            font-weight: 700;
            color: var(--black);
            font-variant-numeric: tabular-nums;
            min-width: 1.5ch;
            display: inline-block;
        }

        .error-badge {
            margin-top: 1.75rem;
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            padding: 0.3rem 0.75rem;
            background: var(--bg);
            border: 1px solid var(--border);
            border-radius: 99px;
            font-size: 0.7rem;
            font-weight: 500;
            color: var(--muted);
            letter-spacing: 0.5px;
        }

        .badge-dot {
            width: 5px;
            height: 5px;
            background: #dc2626;
            border-radius: 50%;
            animation: pulse-dot 0.8s ease-in-out infinite;
        }

        @keyframes pulse-dot {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: 0.2;
            }
        }
    </style>
</head>

<body>
    <div class="card">
        <a href="{{ url('/') }}" class="logo">
            <span class="logo-dot"></span>
            ReservApp
        </a>

        <div class="icon-wrap">
            <!-- Base de datos -->
            <svg class="db-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75"
                stroke-linecap="round" stroke-linejoin="round">
                <ellipse cx="12" cy="5" rx="9" ry="3" />
                <path d="M21 12c0 1.66-4 3-9 3s-9-1.34-9-3" />
                <path d="M3 5v14c0 1.66 4 3 9 3s9-1.34 9-3V5" />
            </svg>
            <div class="badge-error">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round">
                    <path d="M18 6L6 18M6 6l12 12" />
                </svg>
            </div>
        </div>

        <span class="error-label">Sin conexión</span>
        <h1>Base de datos no disponible</h1>
        <p>No se puede conectar con el servidor de base de datos.<br>
            Estamos trabajando para restaurar el servicio.</p>

        <!-- Panel de estado -->
        <div class="status-list">
            <div class="status-item">
                <span class="status-dot ok"></span>
                <span class="status-label">Servidor web</span>
                <span class="status-value ok-text">Operativo</span>
            </div>
            <div class="status-item">
                <span class="status-dot error"></span>
                <span class="status-label">Base de datos</span>
                <span class="status-value err-text">Sin conexión</span>
            </div>
            <div class="status-item">
                <span class="status-dot warning"></span>
                <span class="status-label">Servicio general</span>
                <span class="status-value warn-text">Degradado</span>
            </div>
        </div>

        <button onclick="location.reload()" class="btn-primary">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"
                stroke-linejoin="round">
                <path d="M1 4v6h6M23 20v-6h-6" />
                <path d="M20.49 9A9 9 0 0 0 5.64 5.64L1 10m22 4l-4.64 4.36A9 9 0 0 1 3.51 15" />
            </svg>
            Reintentar ahora
        </button>

        <p class="auto-retry">
            Reintentando automáticamente en <span class="countdown" id="countdown">30</span>s
        </p>

        <div class="error-badge">
            <span class="badge-dot"></span>
            PDOException · Connection refused
        </div>
    </div>

    <script>
        let seconds = 30;
        const el = document.getElementById('countdown');

        const timer = setInterval(() => {
            seconds--;
            el.textContent = seconds;
            if (seconds <= 0) {
                clearInterval(timer);
                location.reload();
            }
        }, 1000);
    </script>
</body>

</html>
