@extends('layouts.admin')

@section('title', 'Mes ordonnances - Espace patient')

@section('content')
    <div class="hero-card">
        <div class="hero-copy">
            <span class="eyebrow"><span></span>Ordonnances</span>
            <h1>Votre historique d'ordonnances</h1>
            <p>Consultez vos ordonnances récentes et suivez les rappels envoyés par votre pharmacie.</p>
            <div class="hero-actions">
                <a href="{{ route('dashboard') }}" class="btn btn-primary">Retour à mon espace</a>
            </div>
        </div>
        <div class="hero-illustration">
            <img src="{{ asset('images/illust-patient.svg') }}" alt="Illustration ordonnances patient">
        </div>
    </div>

    @if(!$patient)
        <div class="card">
            <h2>Aucun dossier patient trouvé</h2>
            <p>Nous n'avons pas trouvé de dossier associé à votre adresse email.</p>
            <p>Demandez à votre pharmacie de créer ou associer votre dossier patient avec votre adresse email actuelle pour accéder à vos ordonnances.</p>
        </div>
    @elseif($prescriptions->isEmpty())
        <div class="card">
            <h2>Aucune ordonnance disponible</h2>
            <p>Votre dossier patient est présent, mais aucune ordonnance n'a encore été enregistrée.</p>
            <p>Votre pharmacie pourra ajouter vos ordonnances et vous informer dès qu'elles seront disponibles.</p>
        </div>
    @else
        <div class="card">
            <h2>Historique des ordonnances</h2>
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Date</th>
                        <th>Médecin</th>
                        <th>Status</th>
                        <th>Médicaments</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($prescriptions as $prescription)
                        <tr>
                            <td>{{ $prescription->id }}</td>
                            <td>{{ $prescription->prescription_date->format('d/m/Y') }}</td>
                            <td>{{ $prescription->doctor_name }}</td>
                            <td>{{ ucfirst($prescription->status) }}</td>
                            <td class="pre-wrap">{{ $prescription->medicines }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="info-grid">
            <div class="info-card">
                <h2>Rappels et suivi</h2>
                <p>Les ordonnances marquées comme «dispensed» sont prêtes à être retirées. Si une ordonnance est en attente, contactez votre pharmacie pour accélérer le traitement.</p>
            </div>
            <div class="info-card">
                <h2>Conseil</h2>
                <p>Pour des rappels de prise ou de renouvellement, demandez à votre pharmacien de noter les dates de suivi directement dans votre dossier patient.</p>
            </div>
        </div>
    @endif
@endsection
