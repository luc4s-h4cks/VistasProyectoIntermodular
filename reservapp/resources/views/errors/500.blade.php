<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>500 — ReservApp</title>
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
            --accent: #dc2626;
            --accent-light: #fef2f2;
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
            animation: flicker 6s ease-in-out infinite;
        }

        @keyframes flicker {

            0%,
            93%,
            100% {
                opacity: 1;
            }

            94% {
                opacity: 0.5;
            }

            95% {
                opacity: 1;
            }

            96% {
                opacity: 0.3;
            }

            97% {
                opacity: 1;
            }
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
        }

        .icon-wrap svg {
            width: 28px;
            height: 28px;
            color: var(--accent);
            animation: spin-slow 8s linear infinite;
        }

        @keyframes spin-slow {
            from {
                transform: rotate(0deg);
            }

            to {
                transform: rotate(360deg);
            }
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
            background: var(--black);
            color: #fff;
            font-family: inherit;
            font-weight: 600;
            font-size: 0.9375rem;
            text-decoration: none;
            border-radius: 12px;
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
            transition: transform 0.2s;
        }

        .btn-primary:hover svg {
            transform: rotate(180deg);
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
            cursor: pointer;
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
            background: var(--accent);
            border-radius: 50%;
            animation: pulse-dot 0.8s ease-in-out infinite;
        }

        @keyframes pulse-dot {

            0%,
            100% {
                opacity: 1;
                transform: scale(1);
            }

            50% {
                opacity: 0.3;
                transform: scale(0.7);
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
            <!-- Engranaje girando -->
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round"
                stroke-linejoin="round">
                <path d="M12 15a3 3 0 1 0 0-6 3 3 0 0 0 0 6z" />
                <path
                    d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83-2.83l.06-.06A1.65 1.65 0 0 0 4.68 15a1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 2.83-2.83l.06.06A1.65 1.65 0 0 0 9 4.68a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 2.83l-.06.06A1.65 1.65 0 0 0 19.4 9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z" />
            </svg>
        </div>

        <div class="error-code">500</div>
        <h1>Algo ha fallado</h1>
        <p>Ha ocurrido un error interno en el servidor.<br>
            Nuestro equipo ya está trabajando en solucionarlo.</p>

        <a href="{{ url('/') }}" class="btn-primary">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"
                stroke-linejoin="round">
                <path d="M1 4v6h6M23 20v-6h-6" />
                <path d="M20.49 9A9 9 0 0 0 5.64 5.64L1 10m22 4l-4.64 4.36A9 9 0 0 1 3.51 15" />
            </svg>
            Reintentar
        </a>

        <div class="divider">o</div>

        <a href="javascript:history.back()" class="btn-secondary">Volver atrás</a>

        <div class="error-badge">
            <span class="badge-dot"></span>
            Error 500 · Error interno del servidor
        </div>
    </div>
</body>

</html>
