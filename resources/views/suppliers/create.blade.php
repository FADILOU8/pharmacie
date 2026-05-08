@extends('layouts.admin')

@section('title', 'Ajouter Fournisseur - Gestion Pharmacie')

@section('content')
    <h1>🏭 Ajouter un Fournisseur</h1>

    <div class="card">
        <form action="{{ route('suppliers.store') }}" method="POST">
            @csrf

            <div class="form-group">
                <label for="name">Nom du Fournisseur <span class="required-star">*</span></label>
                <input type="text" id="name" name="name" value="{{ old('name') }}" required>
                @error('name')<div class="error-text">{{ $message }}</div>@enderror
            </div>

            <div class="form-group">
                <label for="contact_person">Personne de Contact</label>
                <input type="text" id="contact_person" name="contact_person" value="{{ old('contact_person') }}" placeholder="Ex: Jean Dupont">
                @error('contact_person')<div class="error-text">{{ $message }}</div>@enderror
            </div>

            <div class="form-group">
                <label for="phone">Téléphone</label>
                <input type="tel" id="phone" name="phone" value="{{ old('phone') }}" placeholder="Ex: 03 12 34 56 78">
                @error('phone')<div class="error-text">{{ $message }}</div>@enderror
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" placeholder="contact@fournisseur.com">
                @error('email')<div class="error-text">{{ $message }}</div>@enderror
            </div>

            <div class="form-group">
                <label for="address">Adresse <span class="error-text">*</span></label>
                <textarea id="address" name="address" rows="3" required placeholder="Ex: 123 Rue de Paris, 75000 Paris">{{ old('address') }}</textarea>
                @error('address')<div class="error-text">{{ $message }}</div>@enderror
            </div>

            <div class="form-group">
                <label for="payment_terms">Conditions de Paiement</label>
                <input type="text" id="payment_terms" name="payment_terms" value="{{ old('payment_terms') }}" placeholder="Ex: Net 30 jours">
                @error('payment_terms')<div class="error-text">{{ $message }}</div>@enderror
            </div>

            <div class="actions-row">
                <button type="submit" class="btn btn-success">✓ Ajouter</button>
                <a href="{{ route('suppliers.index') }}" class="btn btn-secondary">Retour</a>
            </div>
        </form>
    </div>
@endsection
