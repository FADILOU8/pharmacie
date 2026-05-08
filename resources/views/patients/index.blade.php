@extends('layouts.app')

@section('title', 'Patients - Gestion Pharmacie')

@section('header-actions')
    <a href="{{ route('patients.create') }}" class="button success">+ Ajouter un Patient</a>
@endsection

@section('content')
    @if ($patients->isEmpty())
        <p>Aucun patient enregistré pour le moment.</p>
    @else
        <table class="table">
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Téléphone</th>
                    <th>Email</th>
                    <th>Adresse</th>
                    <th>Points de fidélité</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($patients as $patient)
                    <tr>
                        <td>{{ $patient->name }}</td>
                        <td>{{ $patient->phone ?? '-' }}</td>
                        <td>{{ $patient->email ?? '-' }}</td>
                        <td>{{ $patient->address ?? '-' }}</td>
                        <td>{{ $patient->loyalty_points ?? 0 }}</td>
                        <td>
                            <a href="{{ route('patients.show', $patient) }}" class="button info">Voir</a>
                            <a href="{{ route('patients.export.pdf', $patient) }}" class="button success">PDF</a>
                            <a href="{{ route('patients.export.csv', $patient) }}" class="button secondary">CSV</a>
                            <a href="{{ route('patients.edit', $patient) }}" class="button">Modifier</a>
                            <form action="{{ route('patients.destroy', $patient) }}" method="POST" class="inline-form">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="button danger">Supprimer</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
@endsection
