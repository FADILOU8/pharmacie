@extends('layouts.admin')

@section('title', 'Créer votre pharmacie - Gestion Pharmacie')

@section('content')
    <h1>📋 Bienvenue ! Créez votre pharmacie</h1>

    <div class="card">
        <p style="margin-bottom: 24px; font-size: 16px; color: #555;">
            Pour commencer à utiliser l'application, veuillez créer votre pharmacie. Ces informations seront utilisées dans votre gestion quotidienne.
        </p>

        <form action="{{ route('pharmacy.store') }}" method="POST">
            @csrf

            <div class="form-group">
                <label for="name">Nom de la pharmacie <span style="color: red;">*</span></label>
                <input type="text" id="name" name="name" required placeholder="Ex: Pharmacie du Centre">
                @error('name')<div style="color:#e74c3c; margin-top:4px;">{{ $message }}</div>@enderror
            </div>

            <div class="form-group">
                <label for="address">Adresse <span style="color: red;">*</span></label>
                <input type="text" id="address" name="address" required placeholder="Ex: 123 Rue Principale">
                @error('address')<div style="color:#e74c3c; margin-top:4px;">{{ $message }}</div>@enderror
            </div>

            <div class="form-group">
                <label for="city">Ville <span style="color: red;">*</span></label>
                <input type="text" id="city" name="city" required placeholder="Ex: Paris">
                @error('city')<div style="color:#e74c3c; margin-top:4px;">{{ $message }}</div>@enderror
            </div>

            <div class="form-group">
                <label for="phone">Téléphone</label>
                <input type="tel" id="phone" name="phone" placeholder="Ex: +33 1 23 45 67 89">
                @error('phone')<div style="color:#e74c3c; margin-top:4px;">{{ $message }}</div>@enderror
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" placeholder="Ex: info@pharmacie.fr">
                @error('email')<div style="color:#e74c3c; margin-top:4px;">{{ $message }}</div>@enderror
            </div>

            <div class="form-group">
                <label for="open_hours">Horaires d'ouverture</label>
                <input type="text" id="open_hours" name="open_hours" placeholder="Ex: Lun-Ven: 08h-20h, Sam: 09h-13h">
                @error('open_hours')<div style="color:#e74c3c; margin-top:4px;">{{ $message }}</div>@enderror
            </div>

            <div style="margin-top: 24px; display: flex; gap: 12px;">
                <button type="submit" class="btn btn-success">✓ Créer ma pharmacie</button>
            </div>
        </form>
    </div>
@endsection
