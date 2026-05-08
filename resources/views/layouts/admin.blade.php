<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>@yield('title', 'Gestion de Pharmacie')</title>
        <style>
            * { margin: 0; padding: 0; box-sizing: border-box; }
            html { scroll-behavior: smooth; }
            body { font-family: 'Inter', system-ui, sans-serif; background: radial-gradient(circle at top right, rgba(56, 189, 248, 0.18), transparent 28%), linear-gradient(180deg, #f8fbff 0%, #e8eef7 100%); color: #0f172a; }
            .navbar { position: sticky; top: 0; z-index: 20; background: rgba(15, 23, 42, 0.96); backdrop-filter: blur(16px); padding: 0 28px; height: 70px; display: flex; justify-content: space-between; align-items: center; color: #ffffff; box-shadow: 0 24px 80px rgba(15, 23, 42, 0.1); }
            .navbar .brand { display: inline-flex; align-items: center; gap: 12px; font-size: 1.05rem; font-weight: 700; letter-spacing: 0.02em; }
            .navbar .brand .brand-dot { width: 12px; height: 12px; border-radius: 9999px; background: #38bdf8; box-shadow: 0 0 18px rgba(56, 189, 248, 0.5); }
            .navbar .nav-actions { display: inline-flex; align-items: center; gap: 18px; font-size: 0.95rem; }
            .navbar .nav-actions button { border: none; background: transparent; color: #e2e8f0; cursor: pointer; font-size: 0.95rem; }
            .navbar .nav-actions button:hover { color: #ffffff; }
            .sidebar { background: #0f172a; width: 260px; min-height: calc(100vh - 70px); position: fixed; left: 0; top: 70px; padding: 26px 0 32px; color: #cbd5e1; border-right: 1px solid rgba(226, 232, 240, 0.08); }
            .sidebar a { display: block; padding: 14px 24px; color: #cbd5e1; text-decoration: none; border-left: 4px solid transparent; border-radius: 0 12px 12px 0; margin-bottom: 4px; transition: all 0.2s ease; }
            .sidebar a:hover, .sidebar a.active { background: rgba(255,255,255,0.04); color: #ffffff; border-left-color: #38bdf8; }
            .main { margin-left: 260px; padding: 34px 34px 48px; }
            .container { max-width: 1320px; margin: 0 auto; }
            .card { background: #ffffff; border-radius: 28px; box-shadow: 0 24px 60px rgba(15, 23, 42, 0.08); padding: 32px; margin-bottom: 28px; }
            .card-soft { background: #f8fbff; }
            .btn { display: inline-flex; align-items: center; justify-content: center; gap: 10px; padding: 12px 20px; border-radius: 9999px; border: none; cursor: pointer; font-size: 0.95rem; font-weight: 700; text-decoration: none; }
            .btn-primary { background: #2563eb; color: #ffffff; }
            .btn-secondary { background: #0f172a; color: #ffffff; }
            .btn-success { background: #059669; color: #ffffff; }
            .btn-danger { background: #dc2626; color: #ffffff; }
            .btn:hover { opacity: 0.95; }
            .table { width: 100%; border-collapse: collapse; margin-top: 18px; }
            .table th, .table td { border: 1px solid #e2e8f0; padding: 14px; text-align: left; }
            .table th { background: #f8fafc; font-weight: 700; }
            .form-group { margin-bottom: 20px; }
            .form-group label { display: block; margin-bottom: 10px; font-weight: 700; color: #1e293b; }
            .form-group input, .form-group textarea, .form-group select { width: 100%; padding: 14px 16px; border: 1px solid #cbd5e1; border-radius: 14px; font-size: 0.95rem; transition: border-color 0.2s ease; }
            .form-group input:focus, .form-group textarea:focus, .form-group select:focus { outline: none; border-color: #2563eb; box-shadow: 0 0 0 4px rgba(56, 189, 248, 0.12); }
            .alert { padding: 16px 20px; border-radius: 16px; margin-bottom: 24px; font-size: 0.95rem; }
            .alert-success { background: #d1fae5; color: #065f46; border: 1px solid #a7f3d0; }
            .alert-error { background: #fee2e2; color: #991b1b; border: 1px solid #fecaca; }
            .error-text { color: #dc2626; margin-top: 8px; font-size: 0.95rem; }
            .required-star { color: #dc2626; font-size: 0.95rem; margin-left: 4px; }
            .actions-row { display: flex; flex-wrap: wrap; gap: 12px; margin-top: 24px; }
            h1, h2, h3 { color: #0f172a; }
            h1 { margin-bottom: 16px; font-size: clamp(2rem, 2.6vw, 2.8rem); }
            h2 { margin-bottom: 18px; font-size: 1.5rem; }
            .dashboard-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 20px; margin-bottom: 24px; }
            .stat-card { background: #f8fbff; border-radius: 24px; padding: 28px 24px; text-align: left; box-shadow: 0 18px 40px rgba(15, 23, 42, 0.07); }
            .stat-value { font-size: 2.35rem; font-weight: 800; color: #2563eb; line-height: 1; }
            .stat-label { color: #475569; font-size: 0.95rem; margin-top: 10px; }
            .hero-card { display: grid; grid-template-columns: 1.2fr 0.8fr; gap: 24px; align-items: center; margin-bottom: 28px; }
            .hero-copy { padding: 28px 28px 16px; background: linear-gradient(140deg, #ffffff, #f8fbff); border-radius: 32px; box-shadow: 0 28px 60px rgba(15, 23, 42, 0.08); }
            .hero-copy .eyebrow { display: inline-flex; align-items: center; gap: 10px; margin-bottom: 18px; font-size: 0.95rem; font-weight: 700; color: #2563eb; }
            .hero-copy .eyebrow span { width: 10px; height: 10px; border-radius: 9999px; background: #38bdf8; }
            .hero-copy p { color: #475569; font-size: 1rem; line-height: 1.8; margin-top: 16px; margin-bottom: 24px; }
            .hero-actions { display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, max-content)); gap: 12px; }
            .hero-illustration { display: flex; justify-content: center; align-items: center; min-height: 320px; background: linear-gradient(180deg, rgba(56, 189, 248, 0.12), rgba(96, 165, 250, 0.08)); border-radius: 32px; padding: 28px; }
            .hero-illustration img { width: 100%; max-width: 420px; display: block; }
            .info-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 24px; }
            .info-card { background: #ffffff; border-radius: 24px; padding: 26px; box-shadow: 0 18px 40px rgba(15, 23, 42, 0.06); }
            .info-card h3 { margin-bottom: 14px; }
            .info-card p { color: #475569; line-height: 1.75; }
            .quick-links { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px; margin-top: 20px; }
            .quick-links .btn { width: 100%; justify-content: center; }
            @media (max-width: 1040px) { .main { margin-left: 0; padding: 24px 20px 32px; } .sidebar { position: relative; width: 100%; min-height: auto; top: 0; border-right: none; } .main { margin-left: 0; } }
            @media (max-width: 880px) { .hero-card { grid-template-columns: 1fr; } .hero-illustration { min-height: 260px; } .info-grid { grid-template-columns: 1fr; } }
        </style>
    </head>
    <body>
        <nav class="navbar">
            <div class="brand">
                <span class="brand-dot"></span>
                Gestion de Pharmacie Pro
            </div>
            <div class="nav-actions">
                <div style="display:flex; align-items:center; gap:12px;">
                    <span>{{ Auth::user()->name ?? 'Utilisateur' }}</span>
                    <span style="font-size:0.82rem; padding:6px 10px; border-radius:999px; background:rgba(255,255,255,0.14); color:#e2e8f0;">{{ Auth::user()->role_label }}</span>
                </div>
                <form action="{{ route('logout') }}" method="POST" style="display:inline;">
                    @csrf
                    <button type="submit">Déconnexion</button>
                </form>
            </div>
        </nav>

        @php $user = Auth::user(); @endphp
        <div class="sidebar">
            <a href="{{ route('dashboard') }}" class="@if(request()->routeIs('dashboard')) active @endif">📊 Tableau de bord</a>

            @if($user->isPharmacien() || $user->isPreparateur())
                <a href="{{ route('medicines.index') }}" class="@if(request()->routeIs('medicines.*')) active @endif">💊 Stock de médicaments</a>
            @endif

            @if($user->isPharmacien() || $user->isPreparateur())
                <a href="{{ route('orders.index') }}" class="@if(request()->routeIs('orders.*')) active @endif">📦 Commandes fournisseurs</a>
            @endif

            @if($user->isPharmacien() || $user->isCaissier())
                <a href="{{ route('sales.index') }}" class="@if(request()->routeIs('sales.*')) active @endif">🛒 Ventes</a>
            @endif

            @if($user->isPharmacien())
                <a href="{{ route('prescriptions.index') }}" class="@if(request()->routeIs('prescriptions.*')) active @endif">📄 Ordonnances</a>
                <a href="{{ route('suppliers.index') }}" class="@if(request()->routeIs('suppliers.*')) active @endif">🏭 Fournisseurs</a>
                <a href="{{ route('patients.index') }}" class="@if(request()->routeIs('patients.*')) active @endif">👥 Patients</a>
                <a href="{{ route('alerts.index') }}" class="@if(request()->routeIs('alerts.*')) active @endif">⚠️ Alertes</a>
            @elseif($user->isPreparateur())
                <a href="{{ route('suppliers.index') }}" class="@if(request()->routeIs('suppliers.*')) active @endif">🏭 Fournisseurs</a>
                <a href="{{ route('alerts.index') }}" class="@if(request()->routeIs('alerts.*')) active @endif">⚠️ Alertes</a>
            @elseif($user->isCaissier())
                <a href="{{ route('patients.index') }}" class="@if(request()->routeIs('patients.*')) active @endif">👥 Patients</a>
            @elseif($user->isPatient())
                <a href="{{ route('dashboard') }}" class="@if(request()->routeIs('dashboard')) active @endif">🧑‍⚕️ Mon espace patient</a>
                <a href="{{ route('patient.prescriptions') }}" class="@if(request()->routeIs('patient.prescriptions')) active @endif">📄 Mes ordonnances</a>
            @endif
        </div>

        <div class="main">
            <div class="container">
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                @if($errors->any())
                    <div class="alert alert-error">
                        @foreach($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                    </div>
                @endif

                @yield('content')
            </div>
        </div>

        @if(Auth::user() && Auth::user()->ai_chatbot_enabled)
            @include('components.chatbot')
        @endif
    </body>
</html>
