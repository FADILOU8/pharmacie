@extends('layouts.app')

@section('title', 'Profil Patient - Gestion Pharmacie')

@section('header-actions')
    <a href="{{ route('patients.index') }}" class="button secondary">← Retour à la liste</a>
@endsection

@section('content')
    <div class="grid-2">
        <section class="section-panel">
            <h2>Informations personnelles</h2>
            <p><strong>Nom :</strong> {{ $patient->name }}</p>
            <p><strong>Téléphone :</strong> {{ $patient->phone ?? '-' }}</p>
            <p><strong>Email :</strong> {{ $patient->email ?? '-' }}</p>
            <p><strong>Adresse :</strong> {{ $patient->address ?? '-' }}</p>
            <p><strong>Points fidélité :</strong> {{ $patient->loyalty_points ?? 0 }}</p>
            <p><strong>Enregistré le :</strong> {{ $patient->registration_date?->format('d/m/Y') }}</p>

            <div class="actions-row">
                <a href="{{ route('patients.export.pdf', $patient) }}" class="button success">Télécharger PDF</a>
                <a href="{{ route('patients.export.csv', $patient) }}" class="button secondary">Télécharger CSV</a>
            </div>
        </section>

        <section class="section-panel">
            <h2>Historique des ordonnances</h2>

            @if ($prescriptions->isEmpty())
                <p>Vous n'avez aucune ordonnance associée à ce patient.</p>
            @else
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Date</th>
                            <th>Médecin</th>
                            <th>Statut</th>
                            <th>Médicaments</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($prescriptions as $prescription)
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
            @endif

            <div class="section-panel">
                <h3>Rappels</h3>
                <p>Ce patient n'a actuellement aucun rappel programmé. Vous pouvez utiliser l'espace ordonnance pour ajouter des notifications ou renouvellements.</p>
            </div>
        </section>
    </div>
@endsection
