@extends('layouts.admin')

@section('title', 'Commandes fournisseurs - Gestion Pharmacie')

@section('content')
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
        <h1>📦 Commandes fournisseurs</h1>
        <a href="{{ route('orders.create') }}" class="btn btn-success">+ Nouvelle commande</a>
    </div>

    @if($orders->isEmpty())
        <div class="card">
            <p>Aucune commande fournisseur enregistrée. <a href="{{ route('orders.create') }}">Créer une commande</a></p>
        </div>
    @else
        <div class="card">
            <table class="table">
                <thead>
                    <tr>
                        <th>Référence</th>
                        <th>Fournisseur</th>
                        <th>Date commande</th>
                        <th>Livraison</th>
                        <th>Montant</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $order)
                        <tr>
                            <td>#{{ $order->id }}</td>
                            <td>{{ $order->supplier->name }}</td>
                            <td>{{ $order->order_date->format('d/m/Y') }}</td>
                            <td>{{ $order->delivery_date ? $order->delivery_date->format('d/m/Y') : '---' }}</td>
                            <td>{{ number_format($order->total_amount, 2) }} €</td>
                            <td>{{ ucfirst($order->status) }}</td>
                            <td>
                                <a href="{{ route('orders.show', $order) }}" class="btn btn-primary" style="padding: 6px 12px; font-size: 12px;">Voir</a>
                                @if($order->status === 'pending')
                                    <form action="{{ route('orders.receive', $order) }}" method="POST" style="display:inline; margin-left:6px;">
                                        @csrf
                                        <button type="submit" class="btn btn-success" style="padding: 6px 12px; font-size: 12px;">Recevoir</button>
                                    </form>
                                    <form action="{{ route('orders.destroy', $order) }}" method="POST" style="display:inline; margin-left:6px;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger" style="padding: 6px 12px; font-size: 12px;" onclick="return confirm('Supprimer cette commande ?')">Supprimer</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
@endsection
