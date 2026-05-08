<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>@yield('title', 'Gestion de Pharmacies')</title>
        <style>
            :root {
                color-scheme: light;
                font-family: Inter, system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
                color: #111827;
                background: #eef2ff;
            }

            * {
                box-sizing: border-box;
            }

            body {
                margin: 0;
                min-height: 100vh;
                background: radial-gradient(circle at top left, rgba(59, 130, 246, 0.12), transparent 28%),
                            radial-gradient(circle at bottom right, rgba(139, 92, 246, 0.14), transparent 30%),
                            #eef2ff;
                color: #111827;
            }

            html {
                scroll-behavior: smooth;
            }

            a {
                color: inherit;
                text-decoration: none;
            }

            .container {
                width: min(1200px, calc(100% - 32px));
                margin: 0 auto;
                padding: 28px 0 48px;
            }

            .page-header {
                display: grid;
                gap: 18px;
                margin-bottom: 28px;
                padding: 28px;
                border-radius: 22px;
                background: rgba(255, 255, 255, 0.86);
                box-shadow: 0 24px 70px rgba(15, 23, 42, 0.08);
                backdrop-filter: blur(16px);
            }

            .page-title {
                margin: 0;
                font-size: clamp(2rem, 2.6vw, 3rem);
                letter-spacing: -0.03em;
                line-height: 1.05;
            }

            .eyebrow {
                margin: 0 0 0.75rem;
                color: #4338ca;
                text-transform: uppercase;
                letter-spacing: 0.18em;
                font-size: 0.78rem;
                font-weight: 700;
            }

            .page-subtitle {
                margin: 0.75rem 0 0;
                font-size: 1rem;
                color: #475569;
                max-width: 780px;
            }

            .page-actions {
                display: flex;
                flex-wrap: wrap;
                gap: 12px;
                align-items: center;
            }

            .card {
                background: #ffffff;
                border-radius: 24px;
                box-shadow: 0 20px 60px rgba(15, 23, 42, 0.08);
                padding: 28px;
                border: 1px solid rgba(148, 163, 184, 0.18);
            }

            .alert {
                display: block;
                background: #dcfce7;
                color: #166534;
                border-radius: 16px;
                padding: 18px 22px;
                margin-bottom: 24px;
                border: 1px solid rgba(34, 197, 94, 0.18);
            }

            .button {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                gap: 8px;
                padding: 12px 18px;
                border-radius: 14px;
                border: none;
                cursor: pointer;
                font-weight: 600;
                color: white;
                background: #2563eb;
                box-shadow: 0 8px 25px rgba(37, 99, 235, 0.18);
                transition: transform 0.2s ease, box-shadow 0.2s ease, opacity 0.2s ease;
            }

            .button:hover {
                transform: translateY(-1px);
                opacity: 0.96;
            }

            .button.secondary {
                background: #6b7280;
                box-shadow: 0 8px 20px rgba(107, 114, 128, 0.16);
            }

            .button.success {
                background: #16a34a;
                box-shadow: 0 8px 20px rgba(22, 163, 74, 0.18);
            }

            .button.danger {
                background: #dc2626;
                box-shadow: 0 8px 20px rgba(220, 38, 38, 0.18);
            }

            .button.info {
                background: #0ea5e9;
                box-shadow: 0 8px 20px rgba(14, 165, 233, 0.18);
            }

            .table {
                width: 100%;
                border-collapse: collapse;
                margin-top: 18px;
                font-size: 0.96rem;
                color: #334155;
            }

            .table th,
            .table td {
                padding: 16px 18px;
                border-bottom: 1px solid #e2e8f0;
                vertical-align: middle;
            }

            .table th {
                text-align: left;
                font-size: 0.95rem;
                letter-spacing: 0.01em;
                text-transform: uppercase;
                color: #475569;
                background: #f8fafc;
            }

            .table tbody tr:hover {
                background: rgba(59, 130, 246, 0.06);
            }

            .table tbody tr:nth-child(even) {
                background: #f8fafc;
            }

            .field {
                margin-bottom: 18px;
            }

            .field label {
                display: block;
                margin-bottom: 10px;
                font-weight: 700;
                color: #0f172a;
            }

            .field input,
            .field textarea,
            .field select {
                width: 100%;
                padding: 14px 16px;
                border-radius: 14px;
                border: 1px solid #cbd5e1;
                background: #f8fafc;
                color: #0f172a;
                transition: border-color 0.2s ease, box-shadow 0.2s ease;
            }

            .error-text {
                color: #b91c1c;
                margin-top: 8px;
                font-size: 0.95rem;
            }

            .actions-row {
                display: flex;
                flex-wrap: wrap;
                gap: 12px;
                margin-top: 16px;
            }

            .inline-form {
                display: inline-block;
                margin: 0;
            }

            .pre-wrap {
                white-space: pre-wrap;
            }

            .field input:focus,
            .field textarea:focus,
            .field select:focus {
                outline: none;
                border-color: #3b82f6;
                box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.08);
            }

            .badge {
                display: inline-flex;
                align-items: center;
                gap: 8px;
                padding: 0.55rem 0.85rem;
                border-radius: 999px;
                font-size: 0.82rem;
                font-weight: 700;
                text-transform: uppercase;
                letter-spacing: 0.03em;
            }

            .badge-warning { background: #fef3c7; color: #92400e; }
            .badge-success { background: #dcfce7; color: #166534; }
            .badge-info { background: #dbeafe; color: #1e3a8a; }

            .grid-2 {
                display: grid;
                gap: 24px;
                grid-template-columns: minmax(300px, 360px) minmax(0, 1fr);
            }

            .section-panel {
                background: #f8fafc;
                border-radius: 20px;
                padding: 24px;
                border: 1px solid #e2e8f0;
            }

            @media (max-width: 900px) {
                .grid-2 {
                    grid-template-columns: 1fr;
                }
            }
        </style>
    </head>
    <body>
        <div class="container">
            <header class="page-header">
                <div>
                    <p class="eyebrow">Gestion Pharmacie</p>
                    <h1 class="page-title">@yield('title', 'Tableau de bord pharmacie')</h1>
                    <p class="page-subtitle">Un espace propre et fluide pour piloter vos patients, commandes, alertes et ordonnances.</p>
                </div>
                <div class="page-actions">
                    @yield('header-actions')
                </div>
            </header>

            @if (session('success'))
                <div class="alert">{{ session('success') }}</div>
            @endif

            <main class="card">
                @yield('content')
            </main>
        </div>
    </body>
</html>
