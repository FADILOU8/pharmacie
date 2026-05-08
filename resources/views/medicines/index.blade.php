@extends('layouts.admin')

@section('title', 'Médicaments - Gestion Pharmacie')

@section('content')
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
        <h1>💊 Stock Médicaments</h1>
        <a href="{{ route('medicines.create') }}" class="btn btn-success">+ Ajouter Médicament</a>
    </div>

    @if($medicines->isEmpty())
        <div class="card">
            <p>Aucun médicament enregistré. <a href="{{ route('medicines.create') }}">Ajouter le premier médicament</a></p>
        </div>
    @else
        <div class="card">
            <table class="table">
                <thead>
                    <tr>
                        <th>DCI</th>
                        <th>Nom</th>
                        <th>Forme</th>
                        <th>Dosage</th>
                        <th>Lot</th>
                        <th>Quantité</th>
                        <th>Prix Unit.</th>
                        <th>Expiration</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($medicines as $medicine)
                        <tr>
                            <td>{{ $medicine->dci }}</td>
                            <td>{{ $medicine->name }}</td>
                            <td>{{ $medicine->form }}</td>
                            <td>{{ $medicine->dosage }}</td>
                            <td>{{ $medicine->lot_number }}</td>
                            <td><strong>{{ $medicine->quantity }}</strong></td>
                            <td>{{ number_format($medicine->unit_price, 2) }} €</td>
                            <td>{{ $medicine->expiration_date }}</td>
                            <td>
                                <a href="{{ route('medicines.edit', $medicine) }}" class="btn btn-primary" style="padding: 6px 12px; font-size: 12px;">Modifier</a>
                                <form action="{{ route('medicines.destroy', $medicine) }}" method="POST" style="display:inline;">
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
