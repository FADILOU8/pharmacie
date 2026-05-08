@extends('layouts.admin')

@section('title', 'Ordonnances - Gestion Pharmacie')

@section('content')
  <div style="display: flex; gap: 8px;">
    <a href="{{ route('export.prescriptions.pdf') }}" class="btn btn-secondary">⬇ PDF</a>
    <a href="{{ route('export.prescriptions.csv') }}" class="btn btn-secondary">⬇ CSV</a>
    <a href="{{ route('prescriptions.create') }}" class="btn btn-success">+ Nouvelle Ordonnance</a>
</div>

    @if($prescriptions->isEmpty())
        <div class="card">
            <p>Aucune ordonnance enregistrée. <a href="{{ route('prescriptions.create') }}">Ajouter une ordonnance</a></p>
        </div>
    @else
        <div class="card">
            <table class="table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Patient</th>
                        <th>Médecin</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($prescriptions as $prescription)
                        <tr>
                            <td>{{ $prescription->prescription_date->format('d/m/Y') }}</td>
                            <td>{{ $prescription->patient->name }}</td>
                            <td>{{ $prescription->doctor_name }}</td>
                            <td>
                                <span style="padding: 4px 12px; border-radius: 4px;
                                    @if($prescription->status === 'pending') background: #fff3cd; color: #856404;
                                    @elseif($prescription->status === 'dispensed') background: #d4edda; color: #155724;
                                    @else background: #d1ecf1; color: #0c5460; @endif">
                                    {{ ucfirst($prescription->status) }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('prescriptions.show', $prescription) }}" class="btn btn-primary" style="padding: 6px 12px; font-size: 12px;">Voir</a>
                                <a href="{{ route('prescriptions.edit', $prescription) }}" class="btn btn-primary" style="padding: 6px 12px; font-size: 12px;">Modifier</a>
                                <form action="{{ route('prescriptions.destroy', $prescription) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger" style="padding: 6px 12px; font-size: 12px;" onclick="return confirm('Confirmer la suppression ?')">Supprimer</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
@endsection
