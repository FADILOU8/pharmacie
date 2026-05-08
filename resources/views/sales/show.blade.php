@extends('layouts.admin')

@section('title', 'Détails de la Vente - Gestion Pharmacie')

@section('content')
    <div class="page-header">
        <h1>📋 Détails de la Vente</h1>
        <div class="breadcrumb">
            <a href="{{ route('sales.index') }}">Ventes</a> / Détails
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h2>Vente #{{ $sale->id }}</h2>
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="detail-item">
                        <strong>Médicament:</strong>
                        <p>{{ $medicine->name }}</p>
                    </div>
                    <div class="detail-item">
                        <strong>Quantité:</strong>
                        <p>{{ $sale->quantity }} unité(s)</p>
                    </div>
                    <div class="detail-item">
                        <strong>Prix Unitaire:</strong>
                        <p>{{ number_format($sale->unit_price, 2) }} €</p>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="detail-item">
                        <strong>Remise:</strong>
                        <p>{{ number_format($sale->discount, 2) }} €</p>
                    </div>
                    <div class="detail-item">
                        <strong>Prix Total:</strong>
                        <p class="highlight">{{ number_format($sale->total_price, 2) }} €</p>
                    </div>
                    <div class="detail-item">
                        <strong>Mode de Paiement:</strong>
                        <p>
                            @if($sale->payment_method === 'cash')
                                <span class="badge badge-info">Espèces</span>
                            @elseif($sale->payment_method === 'card')
                                <span class="badge badge-success">Carte</span>
                            @elseif($sale->payment_method === 'check')
                                <span class="badge badge-warning">Chèque</span>
                            @endif
                        </p>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="detail-item">
                        <strong>Nom du Client:</strong>
                        <p>{{ $sale->customer_name ?? 'Non spécifié' }}</p>
                    </div>
                    <div class="detail-item">
                        <strong>Date de Vente:</strong>
                        <p>{{ $sale->sale_date->format('d/m/Y H:i') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="card-footer">
            <a href="{{ route('sales.edit', $sale) }}" class="btn btn-primary">✏️ Modifier</a>
            <form action="{{ route('sales.destroy', $sale) }}" method="POST" style="display:inline;" onsubmit="return confirm('Êtes-vous sûr?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">🗑️ Supprimer</button>
            </form>
            <a href="{{ route('sales.index') }}" class="btn btn-secondary">← Retour</a>
        </div>
    </div>

    <style>
        .detail-item {
            margin-bottom: 20px;
        }
        .detail-item strong {
            display: block;
            color: #555;
            margin-bottom: 5px;
        }
        .detail-item p {
            margin: 0;
            font-size: 16px;
            color: #333;
        }
        .highlight {
            font-weight: bold;
            color: #27ae60;
            font-size: 18px;
        }
        .badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 4px;
            color: white;
            font-size: 12px;
        }
        .badge-info {
            background-color: #3498db;
        }
        .badge-success {
            background-color: #27ae60;
        }
        .badge-warning {
            background-color: #f39c12;
        }
        .card-footer {
            padding: 15px;
            border-top: 1px solid #ddd;
            text-align: right;
        }
        .btn {
            padding: 8px 16px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            margin-right: 10px;
        }
        .btn-primary {
            background-color: #3498db;
            color: white;
        }
        .btn-danger {
            background-color: #e74c3c;
            color: white;
        }
        .btn-secondary {
            background-color: #95a5a6;
            color: white;
        }
    </style>
@endsection
