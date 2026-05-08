@extends('layouts.admin')

@section('title', 'Modifier Ordonnance - Gestion Pharmacie')

@section('content')
    <h1>📋 Modifier l'Ordonnance</h1>

    <div class="card">
        <form action="{{ route('prescriptions.update', $prescription) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label>Patient</label>
                <input type="text" value="{{ $prescription->patient->name }}" disabled>
            </div>

            <div class="form-group">
                <label>Médecin</label>
                <input type="text" value="{{ $prescription->doctor_name }}" disabled>
            </div>

            <div class="form-group">
                <label for="medicines">Médicaments <span style="color: red;">*</span></label>
                <textarea id="medicines" name="medicines" rows="6" required>{{ old('medicines', $prescription->medicines) }}</textarea>
                @error('medicines')<div style="color:#e74c3c; margin-top:4px;">{{ $message }}</div>@enderror
            </div>

            <div class="form-group">
                <label for="status">Statut <span style="color: red;">*</span></label>
                <select id="status" name="status" required>
                    <option value="pending" @if($prescription->status === 'pending') selected @endif>En attente</option>
                    <option value="dispensed" @if($prescription->status === 'dispensed') selected @endif>Délivrée</option>
                    <option value="archived" @if($prescription->status === 'archived') selected @endif>Archivée</option>
                </select>
                @error('status')<div style="color:#e74c3c; margin-top:4px;">{{ $message }}</div>@enderror
            </div>

            <div style="margin-top: 24px; display: flex; gap: 12px;">
                <button type="submit" class="btn btn-success">✓ Mettre à jour</button>
                <a href="{{ route('prescriptions.index') }}" class="btn btn-secondary">Retour</a>
            </div>
        </form>
    </div>
@endsection
