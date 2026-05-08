@extends('layouts.app')

@section('title', 'Alertes - Gestion Pharmacie')

@section('header-actions')
    <form action="{{ route('alerts.check') }}" method="POST" class="inline-form">
        @csrf
        <button type="submit" class="button info">🔄 Vérifier les expirations</button>
    </form>
@endsection

@section('content')
    @if ($alerts->isEmpty())
        <p>Aucune alerte d'expiration pour le moment.</p>
    @else
        <table class="table">
            <thead>
                <tr>
                    <th>Médicament</th>
                    <th>Numéro de lot</th>
                    <th>Date d'expiration</th>
                    <th>Jours restants</th>
                    <th>Type d'alerte</th>
                    <th>Statut</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($alerts as $alert)
                    <tr>
                        <td>{{ $alert->medicine_name }}</td>
                        <td>{{ $alert->lot_number }}</td>
                        <td>{{ $alert->expiration_date->format('d/m/Y') }}</td>
                        <td>{{ $alert->days_until_expiration }}</td>
                        <td>
                            @if ($alert->alert_type === 'expired')
                                <span class="badge badge-warning">⚠️ Expiré</span>
                            @elseif ($alert->alert_type === 'critical')
                                <span class="badge badge-warning">⚠️ Critique</span>
                            @else
                                <span class="badge badge-success">✓ Normal</span>
                            @endif
                        </td>
                        <td>
                            @if ($alert->status === 'resolved')
                                <span class="badge badge-success">Résolue</span>
                            @else
                                <span class="badge badge-info">Actif</span>
                            @endif
                        </td>
                        <td>
                            @if ($alert->status === 'active')
                                <form action="{{ route('alerts.resolve', $alert) }}" method="POST" class="inline-form">
                                    @csrf
                                    <button type="submit" class="button success">Marquer résolu</button>
                                </form>
                            @else
                                <span class="badge badge-info">Aucune action</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
@endsection
