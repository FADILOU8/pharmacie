<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Liste des ordonnances</title>
    <style>
        body { font-family: Arial, sans-serif; color: #111; }
        h1 { margin-bottom: 16px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ccc; padding: 10px; }
        th { background: #f0f0f0; }
        td { vertical-align: top; }
    </style>
</head>
<body>
    <h1>Liste des ordonnances</h1>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Patient</th>
                <th>Médecin</th>
                <th>Date</th>
                <th>Statut</th>
                <th>Médicaments</th>
            </tr>
        </thead>
        <tbody>
            @foreach($prescriptions as $prescription)
                <tr>
                    <td>{{ $prescription->id }}</td>
                    <td>{{ $prescription->patient->name }}</td>
                    <td>{{ $prescription->doctor_name }}</td>
                    <td>{{ $prescription->prescription_date->format('d/m/Y') }}</td>
                    <td>{{ ucfirst($prescription->status) }}</td>
                    <td style="white-space: pre-wrap;">{{ $prescription->medicines }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
