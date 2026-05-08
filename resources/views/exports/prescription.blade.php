<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ordonnance #{{ $prescription->id }}</title>
    <style>
        body { font-family: Arial, sans-serif; color: #111; }
        .header { margin-bottom: 24px; }
        .header h1 { margin-bottom: 0; }
        .details th { text-align: left; padding-right: 12px; vertical-align: top; }
        .table { width: 100%; border-collapse: collapse; margin-top: 24px; }
        .table th, .table td { border: 1px solid #ccc; padding: 8px; }
        .table th { background: #f3f3f3; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Ordonnance #{{ $prescription->id }}</h1>
        <p>Date : {{ $prescription->prescription_date->format('d/m/Y') }}</p>
        <p>Patient : {{ $prescription->patient->name }}</p>
        <p>Médecin : {{ $prescription->doctor_name }}</p>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th>Champ</th>
                <th>Valeur</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Statut</td>
                <td>{{ ucfirst($prescription->status) }}</td>
            </tr>
            <tr>
                <td>Médicaments</td>
                <td style="white-space: pre-wrap;">{{ $prescription->medicines }}</td>
            </tr>
        </tbody>
    </table>
</body>
</html>
