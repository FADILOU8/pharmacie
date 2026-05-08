@extends('layouts.app')

@section('title', 'Détails pharmacie - Gestion Pharmacie')

@section('header-actions')
    <a href="{{ route('pharmacies.index') }}" class="button secondary">← Retour à la liste</a>
@endsection

@section('content')
    <div class="field">
        <strong>Nom :</strong>
        <p>{{ $pharmacy->name }}</p>
    </div>
    <div class="field">
        <strong>Adresse :</strong>
        <p>{{ $pharmacy->address }}</p>
    </div>
    <div class="field">
        <strong>Ville :</strong>
        <p>{{ $pharmacy->city }}</p>
    </div>
    <div class="field">
        <strong>Téléphone :</strong>
        <p>{{ $pharmacy->phone ?? '-' }}</p>
    </div>
    <div class="field">
        <strong>Email :</strong>
        <p>{{ $pharmacy->email ?? '-' }}</p>
    </div>
    <div class="field">
        <strong>Horaires :</strong>
        <p>{{ $pharmacy->open_hours ?? '-' }}</p>
    </div>

    <a href="{{ route('pharmacies.index') }}" class="button secondary">Retour à la liste</a>
    <a href="{{ route('pharmacies.edit', $pharmacy) }}" class="button">Modifier</a>
@endsection
