@extends('layouts.app')

@section('title', 'Fournisseurs - Gestion Pharmacie')

@section('header-actions')
    <a href="{{ route('suppliers.create') }}" class="button success">+ Ajouter un Fournisseur</a>
@endsection

@section('content')
    @if ($suppliers->isEmpty())
        <p>Aucun fournisseur enregistré pour le moment.</p>
    @else
        <table class="table">
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Personne de contact</th>
                    <th>Téléphone</th>
                    <th>Email</th>
                    <th>Adresse</th>
                    <th>Conditions de paiement</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($suppliers as $supplier)
                    <tr>
                        <td>{{ $supplier->name }}</td>
                        <td>{{ $supplier->contact_person ?? '-' }}</td>
                        <td>{{ $supplier->phone ?? '-' }}</td>
                        <td>{{ $supplier->email ?? '-' }}</td>
                        <td>{{ $supplier->address ?? '-' }}</td>
                        <td>{{ $supplier->payment_terms ?? '-' }}</td>
                        <td>
                            <a href="{{ route('suppliers.edit', $supplier) }}" class="button">Modifier</a>
                            <form action="{{ route('suppliers.destroy', $supplier) }}" method="POST" class="inline-form">
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
