@extends('layouts.admin')

@section('title', 'Espace patient - Gestion Pharmacie')

@section('content')
    <div class="hero-card">
        <div class="hero-copy">
            <span class="eyebrow"><span></span>Bienvenue patient</span>
            <h1>Votre espace personnel</h1>
            <p>Vous pouvez consulter vos informations de compte et suivre vos ordonnances dès que votre pharmacien vous aura associé à une pharmacie.</p>
            <div class="hero-actions">
                <a href="mailto:contact@pharmacie.fr" class="btn btn-primary">Contacter la pharmacie</a>
                <a href="{{ route('patient.prescriptions') }}" class="btn btn-secondary">Voir mes ordonnances</a>
            </div>
        </div>
        <div class="hero-illustration">
            <img src="{{ asset('images/illust-medical.svg') }}" alt="Illustration patient">
        </div>
    </div>

    <div class="info-grid">
        <div class="info-card">
            <h2>Votre profil</h2>
            <p><strong>Nom :</strong> {{ Auth::user()->name }}</p>
            <p><strong>Email :</strong> {{ Auth::user()->email }}</p>
            <p><strong>Rôle :</strong> {{ Auth::user()->role_label }}</p>
        </div>
        <div class="info-card">
            <h2>Prochaines étapes</h2>
            <p>En attendant, votre pharmacien peut vous envoyer vos ordonnances ou activer votre accès à l'espace patient complet.</p>
            <p>Les patients ont normalement un accès dédié à l'historique des ordonnances et aux rappels de traitement.</p>
        </div>
    </div>
@endsection
