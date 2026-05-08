@extends('layouts.admin')

@section('title', 'Ventes - Gestion Pharmacie')

@section('content')
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
        <h1>🛍️ Ventes & Caisse</h1>
        <a href="{{ route('sales.create') }}" class="btn btn-success">+ Nouvelle Vente</a>
    </div>

    @if($sales->isEmpty())
        <div class="card">
            <p>Aucune vente enregistrée. <a href="{{ route('sales.create') }}">Enregistrer une vente</a></p>
        </div>
    @else
        <div class="card">
            <table class="table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Médicament</th>
                        <th>Quantité</th>
                        <th>Prix Unit.</th>
                        <th>Remise</th>
                        <th>Total</th>
                        <th>Paiement</th>
                        <th>Client</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($sales as $sale)
                        <tr>
                            <td>{{ $sale->sale_date->format('d/m/Y H:i') }}</td>
                            <td>{{ $sale->medicine->name }}</td>
                            <td>{{ $sale->quantity }}</td>
                            <td>{{ number_format($sale->unit_price, 2) }} €</td>
                            <td>{{ number_format($sale->discount, 2) }} €</td>
                            <td><strong>{{ number_format($sale->total_price, 2) }} €</strong></td>
                            <td>{{ ucfirst($sale->payment_method) }}</td>
                            <td>{{ $sale->customer_name ?? '-' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
@endsection
