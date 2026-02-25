<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 — ReservApp</title>
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
            --accent: #2563eb;
            --accent-light: #eff6ff;
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
            background: var(--accent);
            border-radius: 50%;
        }

        .error-code {
            font-size: clamp(5rem, 15vw, 7.5rem);
            font-weight: 800;
            line-height: 1;
            letter-spacing: -4px;
            background: linear-gradient(135deg, var(--black) 0%, #52525b 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 0.25rem;
            display: inline-block;
        }

        .icon-wrap {
            width: 56px;
            height: 56px;
            background: var(--accent-light);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            animation: float 3s ease-in-out infinite;
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0px);
            }

            50% {
                transform: translateY(-6px);
            }
        }

        .icon-wrap svg {
            width: 28px;
            height: 28px;
            color: var(--accent);
        }

        h1 {
            font-size: 1.375rem;
            font-weight: 700;
            margin-bottom: 0.625rem;
        }

        p {
            font-size: 0.9375rem;
            color: var(--muted);
            line-height: 1.65;
            margin-bottom: 2rem;
        }

        .btn-primary {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            width: 100%;
            padding: 0.8125rem 1.5rem;
            background: var(--accent);
            color: #fff;
            font-family: inherit;
            font-weight: 600;
            font-size: 0.9375rem;
            text-decoration: none;
            border-radius: 12px;
            transition: background 0.15s, transform 0.15s, box-shadow 0.15s;
            box-shadow: 0 1px 3px rgba(37, 99, 235, 0.3), 0 4px 12px rgba(37, 99, 235, 0.2);
        }

        .btn-primary:hover {
            background: #1d4ed8;
            transform: translateY(-1px);
        }

        .btn-primary svg {
            width: 16px;
            height: 16px;
            transition: transform 0.15s;
        }

        .btn-primary:hover svg {
            transform: translateX(-2px);
        }

        .divider {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin: 1rem 0;
            font-size: 0.8rem;
            color: var(--muted);
        }

        .divider::before,
        .divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: var(--border);
        }

        .btn-secondary {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            padding: 0.8125rem 1.5rem;
            background: transparent;
            color: var(--black);
            font-family: inherit;
            font-weight: 600;
            font-size: 0.9375rem;
            text-decoration: none;
            border-radius: 12px;
            border: 1px solid var(--border);
            transition: background 0.15s;
        }

        .btn-secondary:hover {
            background: var(--bg);
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
            background: #ef4444;
            border-radius: 50%;
            animation: pulse-dot 1.5s ease-in-out infinite;
        }

        @keyframes pulse-dot {

            0%,
            100% {
                opacity: 1;
                transform: scale(1);
            }

            50% {
                opacity: 0.4;
                transform: scale(0.8);
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
            <!-- Calendario con interrogación -->
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round"
                stroke-linejoin="round">
                <rect x="3" y="4" width="18" height="18" rx="2" />
                <line x1="16" y1="2" x2="16" y2="6" />
                <line x1="8" y1="2" x2="8" y2="6" />
                <line x1="3" y1="10" x2="21" y2="10" />
                <path d="M9.5 16a2.5 2.5 0 0 1 5 0c0 1.5-2.5 2-2.5 2" />
                <line x1="12" y1="20" x2="12.01" y2="20" />
            </svg>
        </div>

        <div class="error-code">404</div>
        <h1>Página no encontrada</h1>
        <p>La dirección que buscas no existe o ha sido<br>movida a otro lugar.</p>

        <a href="{{ url('/') }}" class="btn-primary">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"
                stroke-linejoin="round">
                <path d="M19 12H5M12 19l-7-7 7-7" />
            </svg>
            Volver al inicio
        </a>

        <div class="divider">o</div>

        <a href="javascript:history.back()" class="btn-secondary">Página anterior</a>

        <div class="error-badge">
            <span class="badge-dot"></span>
            Error 404 · Ruta no encontrada
        </div>
    </div>
</body>

</html>
