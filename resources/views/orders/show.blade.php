@extends('layouts.admin')

@section('title', 'Commande fournisseur - Gestion Pharmacie')

@section('content')
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
        <div>
            <h1>Commande #{{ $order->id }}</h1>
            <p>Fournisseur : {{ $order->supplier->name }}</p>
        </div>
        <div>
            <a href="{{ route('orders.index') }}" class="btn btn-secondary">Retour aux commandes</a>
        </div>
    </div>

    <div class="card" style="margin-bottom: 24px;">
        <h2>Détails de la commande</h2>
        <p><strong>Date de commande :</strong> {{ $order->order_date->format('d/m/Y') }}</p>
        <p><strong>Date de livraison prévue :</strong> {{ $order->delivery_date ? $order->delivery_date->format('d/m/Y') : '---' }}</p>
        <p><strong>Montant total :</strong> {{ number_format($order->total_amount, 2) }} €</p>
        <p><strong>Statut :</strong> {{ ucfirst($order->status) }}</p>
    </div>

    <div class="card">
        <h2>Articles commandés</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>DCI</th>
                    <th>Forme</th>
                    <th>Dosage</th>
                    <th>Lot</th>
                    <th>Quantité</th>
                    <th>Prix unitaire</th>
                    <th>Expiration</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->medicines as $item)
                    <tr>
                        <td>{{ $item['name'] }}</td>
                        <td>{{ $item['dci'] ?? '-' }}</td>
                        <td>{{ $item['form'] ?? '-' }}</td>
                        <td>{{ $item['dosage'] ?? '-' }}</td>
                        <td>{{ $item['lot_number'] }}</td>
                        <td>{{ $item['quantity'] }}</td>
                        <td>{{ number_format($item['unit_price'], 2) }} €</td>
                        <td>{{ $item['expiration_date'] ?? '-' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        @if($order->status === 'pending')
            <form action="{{ route('orders.receive', $order) }}" method="POST" style="margin-top: 20px;">
                @csrf
                <button type="submit" class="btn btn-success">Marquer reçu et mettre à jour le stock</button>
            </form>
        @endif
    </div>
@endsection
