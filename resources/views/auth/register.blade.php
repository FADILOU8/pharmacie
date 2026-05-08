<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Inscription - Gestion Pharmacie</title>
        <style>
            * { margin: 0; padding: 0; box-sizing: border-box; }
            html { font-family: 'Inter', system-ui, sans-serif; }
            body { min-height: 100vh; display: flex; align-items: center; justify-content: center; background: radial-gradient(circle at top left, rgba(56, 189, 248, 0.26), transparent 35%), linear-gradient(135deg, #0f172a 0%, #1e293b 100%); color: #e2e8f0; }
            .auth-page { display: grid; grid-template-columns: 1.05fr 0.95fr; gap: 32px; max-width: 960px; width: 100%; padding: 24px; }
            .auth-panel { background: rgba(15, 23, 42, 0.88); border: 1px solid rgba(148, 163, 184, 0.16); border-radius: 28px; box-shadow: 0 32px 80px rgba(15, 23, 42, 0.32); overflow: hidden; }
            .auth-hero { padding: 48px 40px; display: flex; flex-direction: column; justify-content: center; gap: 24px; }
            .auth-hero h1 { font-size: clamp(2rem, 2.8vw, 2.8rem); line-height: 1.05; color: #ffffff; }
            .auth-hero p { color: #cbd5e1; font-size: 1rem; line-height: 1.8; }
            .auth-hero .badge { display: inline-flex; align-items: center; gap: 10px; padding: 10px 16px; border-radius: 9999px; background: rgba(56, 189, 248, 0.16); color: #a5f3fc; font-size: 0.92rem; font-weight: 700; }
            .auth-hero img { width: 100%; max-width: 380px; display: block; margin-top: 10px; }
            .auth-form { background: #ffffff; border-radius: 28px; padding: 40px; box-shadow: 0 24px 70px rgba(15, 23, 42, 0.12); color: #0f172a; }
            .auth-form h2 { margin-bottom: 24px; font-size: 1.9rem; }
            .form-group { margin-bottom: 20px; }
            .form-group label { display: block; margin-bottom: 10px; font-weight: 700; color: #0f172a; }
            .form-group input, .form-group select { width: 100%; padding: 14px 16px; border-radius: 16px; border: 1px solid #cbd5e1; font-size: 1rem; }
            .form-group input:focus, .form-group select:focus { outline: none; border-color: #0ea5e9; box-shadow: 0 0 0 3px rgba(56, 189, 248, 0.12); }
            .btn { width: 100%; padding: 14px 16px; border-radius: 18px; border: none; background: #059669; color: #ffffff; font-weight: 700; font-size: 1rem; cursor: pointer; }
            .btn:hover { background: #047857; }
            .link { margin-top: 18px; text-align: center; font-size: 0.95rem; color: #475569; }
            .link a { color: #0f172a; text-decoration: none; font-weight: 700; }
            .alert { background: #fee2e2; color: #991b1b; border-radius: 14px; padding: 14px 16px; margin-bottom: 20px; }
            @media (max-width: 900px) { .auth-page { grid-template-columns: 1fr; gap: 20px; padding: 16px; } .auth-hero { padding: 32px 28px; } .auth-form { padding: 32px 28px; } }
        </style>
    </head>
    <body>
        <div class="auth-page">
            <div class="auth-panel auth-hero">
                <span class="badge">Nouvel accès</span>
                <h1>Commencez à gérer votre pharmacie comme un pro</h1>
                <p>Créez votre compte en quelques secondes et bénéficiez d’un tableau de bord clair pour vos ventes, patients et commandes.</p>
                <img src="{{ asset('images/illust-pharmacy.svg') }}" alt="Illustration pharmacie moderne">
            </div>
            <div class="auth-form">
                <h2>Inscription</h2>

                @if($errors->any())
                    <div class="alert">
                        @foreach($errors->all() as $error)
                            {{ $error }}<br>
                        @endforeach
                    </div>
                @endif

                <form method="POST" action="{{ route('register') }}">
                    @csrf
                    <div class="form-group">
                        <label for="name">Nom</label>
                        <input type="text" id="name" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Mot de passe</label>
                        <input type="password" id="password" name="password" required>
                    </div>
                    <div class="form-group">
                        <label for="password_confirmation">Confirmer mot de passe</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" required>
                    </div>
                    <div class="form-group">
                        <label for="role">Rôle</label>
                        <select id="role" name="role" required>
                            <option value="pharmacien" {{ old('role') === 'pharmacien' ? 'selected' : '' }}>Pharmacien Titulaire</option>
                            <option value="preparateur" {{ old('role') === 'preparateur' ? 'selected' : '' }}>Préparateur</option>
                            <option value="caissier" {{ old('role') === 'caissier' ? 'selected' : '' }}>Caissier</option>
                            <option value="patient" {{ old('role') === 'patient' ? 'selected' : '' }}>Patient</option>
                        </select>
                    </div>
                    <button type="submit" class="btn">S'inscrire</button>
                </form>

                <div class="link">
                    Déjà inscrit ? <a href="{{ route('login') }}">Se connecter</a>
                </div>
            </div>
        </div>
    </body>
</html>
