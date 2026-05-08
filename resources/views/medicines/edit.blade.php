@extends('layouts.admin')

@section('title', 'Modifier Médicament - Gestion Pharmacie')

@section('content')
    <h1>💊 Modifier Médicament</h1>

    <div class="card">
        <form action="{{ route('medicines.update', $medicine) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="dci">DCI <span style="color: red;">*</span></label>
                <input type="text" id="dci" name="dci" value="{{ old('dci', $medicine->dci) }}" required>
                @error('dci')<div style="color:#e74c3c; margin-top:4px;">{{ $message }}</div>@enderror
            </div>

            <div class="form-group">
                <label for="name">Nom <span style="color: red;">*</span></label>
                <input type="text" id="name" name="name" value="{{ old('name', $medicine->name) }}" required>
                @error('name')<div style="color:#e74c3c; margin-top:4px;">{{ $message }}</div>@enderror
            </div>

            <div class="form-group">
                <label for="form">Forme <span style="color: red;">*</span></label>
                <input type="text" id="form" name="form" value="{{ old('form', $medicine->form) }}" required>
                @error('form')<div style="color:#e74c3c; margin-top:4px;">{{ $message }}</div>@enderror
            </div>

            <div class="form-group">
                <label for="dosage">Dosage <span style="color: red;">*</span></label>
                <input type="text" id="dosage" name="dosage" value="{{ old('dosage', $medicine->dosage) }}" required>
                @error('dosage')<div style="color:#e74c3c; margin-top:4px;">{{ $message }}</div>@enderror
            </div>

            <div class="form-group">
                <label for="lot_number">Numéro de Lot <span style="color: red;">*</span></label>
                <input type="text" id="lot_number" name="lot_number" value="{{ old('lot_number', $medicine->lot_number) }}" required>
                @error('lot_number')<div style="color:#e74c3c; margin-top:4px;">{{ $message }}</div>@enderror
            </div>

            <div class="form-group">
                <label for="quantity">Quantité <span style="color: red;">*</span></label>
                <input type="number" id="quantity" name="quantity" value="{{ old('quantity', $medicine->quantity) }}" min="0" required>
                @error('quantity')<div style="color:#e74c3c; margin-top:4px;">{{ $message }}</div>@enderror
            </div>

            <div class="form-group">
                <label for="unit_price">Prix Unitaire (€) <span style="color: red;">*</span></label>
                <input type="number" id="unit_price" name="unit_price" value="{{ old('unit_price', $medicine->unit_price) }}" step="0.01" min="0" required>
                @error('unit_price')<div style="color:#e74c3c; margin-top:4px;">{{ $message }}</div>@enderror
            </div>

            <div class="form-group">
                <label for="expiration_date">Date d'Expiration <span style="color: red;">*</span></label>
                <input type="date" id="expiration_date" name="expiration_date" value="{{ old('expiration_date', $medicine->expiration_date) }}" required>
                @error('expiration_date')<div style="color:#e74c3c; margin-top:4px;">{{ $message }}</div>@enderror
            </div>

            <div style="margin-top: 24px; display: flex; gap: 12px;">
                <button type="submit" class="btn btn-success">✓ Mettre à jour</button>
                <a href="{{ route('medicines.index') }}" class="btn btn-secondary">Retour</a>
            </div>
        </form>
    </div>
@endsection
