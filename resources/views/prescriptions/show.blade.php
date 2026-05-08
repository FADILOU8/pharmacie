@extends('layouts.admin')

@section('title', 'Détails Ordonnance - Gestion Pharmacie')

@section('content')
    <h1>📋 Détails de l'Ordonnance</h1>

    <div class="card" style="background: #f9f9f9; padding: 24px; border-radius: 8px;">
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px; margin-bottom: 24px;">
            <div>
                <h3 style="color: #333; margin-bottom: 12px;">Informations Générales</h3>

                <div style="margin-bottom: 16px;">
                    <strong style="color: #666;">Patient :</strong>
                    <p style="margin: 4px 0; font-size: 16px;">{{ $prescription->patient->name }}</p>
                </div>

                <div style="margin-bottom: 16px;">
                    <strong style="color: #666;">Médecin :</strong>
                    <p style="margin: 4px 0; font-size: 16px;">{{ $prescription->doctor_name }}</p>
                </div>

                <div style="margin-bottom: 16px;">
                    <strong style="color: #666;">Date de l'Ordonnance :</strong>
                    <p style="margin: 4px 0; font-size: 16px;">{{ $prescription->prescription_date->format('d/m/Y') }}</p>
                </div>
            </div>

            <div>
                <h3 style="color: #333; margin-bottom: 12px;">Statut</h3>

                <div style="margin-bottom: 16px;">
                    <strong style="color: #666;">Statut Actuel :</strong>
                    <p style="margin: 8px 0;">
                        @if($prescription->status === 'pending')
                            <span style="background: #fff3cd; color: #856404; padding: 8px 16px; border-radius: 4px; display: inline-block;">
                                ⏳ En attente
                            </span>
                        @elseif($prescription->status === 'dispensed')
                            <span style="background: #d4edda; color: #155724; padding: 8px 16px; border-radius: 4px; display: inline-block;">
                                ✓ Délivrée
                            </span>
                        @elseif($prescription->status === 'archived')
                            <span style="background: #d1ecf1; color: #0c5460; padding: 8px 16px; border-radius: 4px; display: inline-block;">
                                📦 Archivée
                            </span>
                        @endif
                    </p>
                </div>

                <div style="margin-bottom: 16px;">
                    <strong style="color: #666;">Créée le :</strong>
                    <p style="margin: 4px 0; font-size: 14px;">{{ $prescription->created_at->format('d/m/Y à H:i') }}</p>
                </div>

                <div style="margin-bottom: 16px;">
                    <strong style="color: #666;">Dernière modification :</strong>
                    <p style="margin: 4px 0; font-size: 14px;">{{ $prescription->updated_at->format('d/m/Y à H:i') }}</p>
                </div>
            </div>
        </div>

        <div style="margin-bottom: 24px; padding: 16px; background: white; border-radius: 6px;">
            <h3 style="color: #333; margin-bottom: 12px;">💊 Médicaments Prescrits</h3>
            <div style="white-space: pre-wrap; font-family: monospace; background: #f5f5f5; padding: 12px; border-radius: 4px; line-height: 1.6;">{{ $prescription->medicines }}</div>
        </div>

        <div style="display: flex; gap: 12px; padding-top: 16px; border-top: 1px solid #ddd;">
            <a href="{{ route('prescriptions.edit', $prescription) }}" class="btn btn-success" style="padding: 10px 20px; text-decoration: none; background-color: #007bff; color: white; border-radius: 4px; display: inline-block;">✏️ Modifier</a>
            <a href="{{ route('prescriptions.export.pdf', $prescription) }}" class="btn btn-success" style="padding: 10px 20px; text-decoration: none; background-color: #28a745; color: white; border-radius: 4px; display: inline-block;">📄 PDF</a>
            <a href="{{ route('prescriptions.export.csv', $prescription) }}" class="btn btn-secondary" style="padding: 10px 20px; text-decoration: none; background-color: #6c757d; color: white; border-radius: 4px; display: inline-block;">CSV</a>

            <form action="{{ route('prescriptions.destroy', $prescription) }}" method="POST" style="display: inline-block;">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger" style="padding: 10px 20px; text-decoration: none; background-color: #dc3545; color: white; border: none; border-radius: 4px; cursor: pointer;" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette ordonnance ?')">🗑️ Supprimer</button>
            </form>

            <a href="{{ route('prescriptions.index') }}" class="btn btn-secondary" style="padding: 10px 20px; text-decoration: none; background-color: #6c757d; color: white; border-radius: 4px; display: inline-block;">← Retour</a>
        </div>
    </div>
@endsection
