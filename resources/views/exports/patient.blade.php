<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Fiche Patient - {{ $patient->name }}</title>
    <style>
        body { font-family: Arial, sans-serif; color: #111; }
        .header { margin-bottom: 24px; }
        .header h1 { margin-bottom: 0; }
        .section { margin-top: 24px; }
        .section h2 { margin-bottom: 12px; }
        .details th { text-align: left; padding-right: 12px; vertical-align: top; }
        .table { width: 100%; border-collapse: collapse; margin-top: 16px; }
        .table th, .table td { border: 1px solid #ccc; padding: 8px; }
        .table th { background: #f3f3f3; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Fiche Patient</h1>
        <p>Nom : {{ $patient->name }}</p>
        <p>Téléphone : {{ $patient->phone ?? '-' }}</p>
        <p>Email : {{ $patient->email ?? '-' }}</p>
        <p>Adresse : {{ $patient->address ?? '-' }}</p>
        <p>Points fidélité : {{ $patient->loyalty_points ?? 0 }}</p>
        <p>Enregistré le : {{ $patient->registration_date?->format('d/m/Y') }}</p>
    </div>

    <div class="section">
        <h2>Historique des ordonnances</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Date</th>
                    <th>Médecin</th>
                    <th>Statut</th>
                    <th>Médicaments</th>
                </tr>
            </thead>
            <tbody>
                @forelse($patient->prescriptions as $prescription)
                    <tr>
                        <td>{{ $prescription->id }}</td>
                        <td>{{ $prescription->prescription_date->format('d/m/Y') }}</td>
                        <td>{{ $prescription->doctor_name }}</td>
                        <td>{{ ucfirst($prescription->status) }}</td>
                        <td style="white-space: pre-wrap;">{{ $prescription->medicines }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5">Aucune ordonnance enregistrée.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</body>
</html>
