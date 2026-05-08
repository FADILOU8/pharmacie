@extends('layouts.admin')

@section('title', 'Dashboard - Gestion Pharmacie')

@section('content')
    @php $user = Auth::user(); @endphp

    <div class="hero-card">
        <div class="hero-copy">
            @if($user->isPharmacien())
                <span class="eyebrow"><span></span>Contrôle intelligent</span>
                <h1>Gérez votre pharmacie avec élégance et rapidité</h1>
                <p>Suivez vos stocks, ventes, ordonnances et alertes d’expiration en temps réel. Accédez aux fonctionnalités critiques en un seul clic.</p>
                <div class="hero-actions">
                    <a href="{{ route('medicines.index') }}" class="btn btn-primary">Voir le stock</a>
                    <a href="{{ route('sales.index') }}" class="btn btn-secondary">Suivre les ventes</a>
                    <a href="{{ route('prescriptions.index') }}" class="btn btn-success">Ordonnances</a>
                </div>
            @elseif($user->isPreparateur())
                <span class="eyebrow"><span></span>Gestion des réceptions</span>
                <h1>Optimisez le stock et les commandes fournisseurs</h1>
                <p>Ajoutez, mettez à jour et organisez rapidement les médicaments et les fournisseurs pour garder votre stock au top.</p>
                <div class="hero-actions">
                    <a href="{{ route('medicines.index') }}" class="btn btn-primary">Voir le stock</a>
                    <a href="{{ route('suppliers.index') }}" class="btn btn-secondary">Fournisseurs</a>
                    <a href="{{ route('alerts.index') }}" class="btn btn-success">Alertes</a>
                </div>
            @elseif($user->isCaissier())
                <span class="eyebrow"><span></span>Service caisse</span>
                <h1>Encaissez rapidement et suivez les ventes</h1>
                <p>Enregistrez les transactions clients en quelques clics et consultez l’historique des ventes dès maintenant.</p>
                <div class="hero-actions">
                    <a href="{{ route('sales.index') }}" class="btn btn-primary">Voir les ventes</a>
                    <a href="{{ route('patients.index') }}" class="btn btn-secondary">Patients</a>
                </div>
            @else
                <span class="eyebrow"><span></span>Bienvenue</span>
                <h1>Gérez votre compte patient</h1>
                <p>Vous êtes connecté en tant que patient. Votre pharmacien vous fournira bientôt un accès aux ordonnances et rappels.</p>
                <div class="hero-actions">
                    <a href="{{ route('dashboard') }}" class="btn btn-primary">Retour au tableau</a>
                </div>
            @endif
        </div>
        <div class="hero-illustration">
            <img src="{{ asset('images/illust-pharmacy.svg') }}" alt="Illustration pharmacie moderne">
        </div>
    </div>

    @if(!$pharmacy)
        <div class="card">
            <div class="info-grid">
                <div class="info-card">
                    <h2>Bienvenue dans votre plateforme</h2>
                    <p>Créez rapidement votre première pharmacie et commencez à suivre vos produits, ventes et patients dès maintenant.</p>
                    <p>Le système est conçu pour rendre la gestion plus fluide, tout en gardant un accès rapide aux actions essentielles.</p>
                </div>
                <div class="info-card">
                    <h2>Créer votre pharmacie</h2>
                    <form action="{{ route('pharmacy.store') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="name">Nom de la pharmacie</label>
                            <input type="text" id="name" name="name" required>
                        </div>
                        <div class="form-group">
                            <label for="address">Adresse</label>
                            <input type="text" id="address" name="address" required>
                        </div>
                        <div class="form-group">
                            <label for="city">Ville</label>
                            <input type="text" id="city" name="city" required>
                        </div>
                        <div class="form-group">
                            <label for="phone">Téléphone</label>
                            <input type="tel" id="phone" name="phone">
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" id="email" name="email">
                        </div>
                        <div class="form-group">
                            <label for="open_hours">Horaires d'ouverture</label>
                            <input type="text" id="open_hours" name="open_hours" placeholder="Lun-Ven: 08h-20h">
                        </div>
                        <button type="submit" class="btn btn-success">Créer ma pharmacie</button>
                    </form>
                </div>
            </div>
        </div>
    @else
        <div class="dashboard-grid">
            <div class="stat-card">
                <div class="stat-value">{{ $medicinesCount }}</div>
                <div class="stat-label">Médicaments en stock</div>
            </div>
            <div class="stat-card">
                <div class="stat-value">{{ $salesCount }}</div>
                <div class="stat-label">Ventes enregistrées</div>
            </div>
            <div class="stat-card">
                <div class="stat-value">{{ $prescriptionsCount }}</div>
                <div class="stat-label">Ordonnances</div>
            </div>
            <div class="stat-card">
                <div class="stat-value">{{ $alertsCount }}</div>
                <div class="stat-label">Alertes actives</div>
            </div>
        </div>

        <div class="info-grid">
            <div class="info-card">
                <h2>{{ $pharmacy->name }}</h2>
                <p><strong>Adresse:</strong> {{ $pharmacy->address }}, {{ $pharmacy->city }}</p>
                <p><strong>Téléphone:</strong> {{ $pharmacy->phone ?? 'N/A' }}</p>
                <p><strong>Email:</strong> {{ $pharmacy->email ?? 'N/A' }}</p>
                <p><strong>Horaires:</strong> {{ $pharmacy->open_hours ?? 'N/A' }}</p>
            </div>
            <div class="info-card">
                <h2>Actions rapides</h2>
                <div class="quick-links">
                    @if($user->isPharmacien())
                        <a href="{{ route('medicines.index') }}" class="btn btn-primary">Voir le stock</a>
                        <a href="{{ route('medicines.create') }}" class="btn btn-success">Ajouter un médicament</a>
                        <a href="{{ route('sales.create') }}" class="btn btn-secondary">Nouvelle vente</a>
                        <a href="{{ route('prescriptions.index') }}" class="btn btn-primary">Voir les ordonnances</a>
                        <a href="{{ route('alerts.index') }}" class="btn btn-danger">Voir les alertes</a>
                    @elseif($user->isPreparateur())
                        <a href="{{ route('medicines.index') }}" class="btn btn-primary">Voir le stock</a>
                        <a href="{{ route('suppliers.index') }}" class="btn btn-success">Fournisseurs</a>
                        <a href="{{ route('orders.index') }}" class="btn btn-secondary">Commandes</a>
                        <a href="{{ route('alerts.index') }}" class="btn btn-danger">Voir les alertes</a>
                    @elseif($user->isCaissier())
                        <a href="{{ route('sales.index') }}" class="btn btn-primary">Voir les ventes</a>
                        <a href="{{ route('patients.index') }}" class="btn btn-secondary">Patients</a>
                    @endif
                </div>
            </div>
        </div>
    @endif
@endsection
