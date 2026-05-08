@extends('layouts.admin')

@section('title', 'Ajouter Patient - Gestion Pharmacie')

@section('content')
    <h1>👥 Ajouter un Patient</h1>

    <div class="card">
        <form action="{{ route('patients.store') }}" method="POST">
            @csrf

            <div class="form-group">
                <label for="name">Nom du Patient <span class="required-star">*</span></label>
                <input type="text" id="name" name="name" value="{{ old('name') }}" required>
                @error('name')<div class="error-text">{{ $message }}</div>@enderror
            </div>

            <div class="form-group">
                <label for="phone">Téléphone</label>
                <input type="tel" id="phone" name="phone" value="{{ old('phone') }}" placeholder="Ex: 06 12 34 56 78">
                @error('phone')<div class="error-text">{{ $message }}</div>@enderror
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" placeholder="patient@example.com">
                @error('email')<div class="error-text">{{ $message }}</div>@enderror
            </div>

            <div class="form-group">
                <label for="address">Adresse</label>
                <textarea id="address" name="address" rows="3" placeholder="Ex: 123 Rue de Paris, 75000 Paris">{{ old('address') }}</textarea>
                @error('address')<div class="error-text">{{ $message }}</div>@enderror
            </div>

            <div class="actions-row">
                <button type="submit" class="btn btn-success">✓ Ajouter</button>
                <a href="{{ route('patients.index') }}" class="btn btn-secondary">Retour</a>
            </div>
        </form>
    </div>
@endsection
