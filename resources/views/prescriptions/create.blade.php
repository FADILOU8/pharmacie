@extends('layouts.admin')

@section('title', 'Nouvelle Ordonnance - Gestion Pharmacie')

@section('content')
    <h1>📋 Enregistrer une Ordonnance</h1>

    <div class="card">
        <form action="{{ route('prescriptions.store') }}" method="POST">
            @csrf

            <div class="form-group">
                <label for="patient_id">Patient <span style="color: red;">*</span></label>
                <select id="patient_id" name="patient_id" required>
                    <option value="">-- Sélectionner un patient --</option>
                    @foreach($patients as $patient)
                        <option value="{{ $patient->id }}">{{ $patient->name }}</option>
                    @endforeach
                </select>
                @error('patient_id')<div style="color:#e74c3c; margin-top:4px;">{{ $message }}</div>@enderror
            </div>

            <div class="form-group">
                <label for="doctor_name">Nom du Médecin <span style="color: red;">*</span></label>
                <input type="text" id="doctor_name" name="doctor_name" required>
                @error('doctor_name')<div style="color:#e74c3c; margin-top:4px;">{{ $message }}</div>@enderror
            </div>

            <div class="form-group">
                <label for="prescription_date">Date de l'Ordonnance <span style="color: red;">*</span></label>
                <input type="date" id="prescription_date" name="prescription_date" required>
                @error('prescription_date')<div style="color:#e74c3c; margin-top:4px;">{{ $message }}</div>@enderror
            </div>

            <div class="form-group">
                <label for="medicines">Médicaments Prescrits <span style="color: red;">*</span></label>
                <textarea id="medicines" name="medicines" rows="6" placeholder="Liste des médicaments (un par ligne)" required></textarea>
                @error('medicines')<div style="color:#e74c3c; margin-top:4px;">{{ $message }}</div>@enderror
            </div>

            <div class="form-group">
                <label for="status">Statut <span style="color: red;">*</span></label>
                <select id="status" name="status" required>
                    <option value="pending">En attente</option>
                    <option value="dispensed">Délivrée</option>
                    <option value="archived">Archivée</option>
                </select>
                @error('status')<div style="color:#e74c3c; margin-top:4px;">{{ $message }}</div>@enderror
            </div>

            <div style="margin-top: 24px; display: flex; gap: 12px;">
                <button type="submit" class="btn btn-success">✓ Enregistrer Ordonnance</button>
                <a href="{{ route('prescriptions.index') }}" class="btn btn-secondary">Retour</a>
            </div>
        </form>
    </div>
@endsection
