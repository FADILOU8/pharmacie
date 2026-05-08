@extends('layouts.app')

@section('title', 'Pharmacies - Gestion Pharmacie')

@section('header-actions')
    <a href="{{ route('pharmacies.create') }}" class="button success">+ Nouvelle pharmacie</a>
@endsection

@section('content')
    @if ($pharmacies->isEmpty())
        <p>Aucune pharmacie enregistrée pour le moment.</p>
    @else
        <table class="table">
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Adresse</th>
                    <th>Ville</th>
                    <th>Téléphone</th>
                    <th>Email</th>
                    <th>Horaires</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($pharmacies as $pharmacy)
                    <tr>
                        <td>{{ $pharmacy->name }}</td>
                        <td>{{ $pharmacy->address }}</td>
                        <td>{{ $pharmacy->city }}</td>
                        <td>{{ $pharmacy->phone ?? '-' }}</td>
                        <td>{{ $pharmacy->email ?? '-' }}</td>
                        <td>{{ $pharmacy->open_hours ?? '-' }}</td>
                        <td>
                            <a href="{{ route('pharmacies.show', $pharmacy) }}" class="button secondary">Voir</a>
                            <a href="{{ route('pharmacies.edit', $pharmacy) }}" class="button">Modifier</a>
                            <form action="{{ route('pharmacies.destroy', $pharmacy) }}" method="POST" class="inline-form">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="button danger" onclick="return confirm('Supprimer cette pharmacie ?')">Supprimer</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
@endsection
