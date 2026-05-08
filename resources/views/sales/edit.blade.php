@extends('layouts.admin')

@section('title', 'Modifier une Vente - Gestion Pharmacie')

@section('content')
    <div class="page-header">
        <h1>✏️ Modifier une Vente</h1>
        <div class="breadcrumb">
            <a href="{{ route('sales.index') }}">Ventes</a> / Modifier
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h2>Vente #{{ $sale->id }}</h2>
        </div>

        <form action="{{ route('sales.update', $sale) }}" method="POST" class="card-body">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="medicine_id">Médicament <span style="color: red;">*</span></label>
                <select id="medicine_id" name="medicine_id" required onchange="updatePrice()">
                    @foreach($medicines as $medicine)
                        <option value="{{ $medicine->id }}" 
                                data-price="{{ $medicine->unit_price }}" 
                                data-qty="{{ $medicine->quantity }}"
                                @if($sale->medicine_id === $medicine->id) selected @endif>
                            {{ $medicine->name }} - {{ number_format($medicine->unit_price, 2) }} €
                        </option>
                    @endforeach
                </select>
                @error('medicine_id')<div style="color:#e74c3c; margin-top:4px;">{{ $message }}</div>@enderror
            </div>

            <div class="form-group">
                <label for="quantity">Quantité <span style="color: red;">*</span></label>
                <input type="number" id="quantity" name="quantity" min="1" value="{{ $sale->quantity }}" required onchange="calculateTotal()">
                @error('quantity')<div style="color:#e74c3c; margin-top:4px;">{{ $message }}</div>@enderror
            </div>

            <div class="form-group">
                <label for="discount">Remise (€)</label>
                <input type="number" id="discount" name="discount" step="0.01" min="0" value="{{ $sale->discount }}" onchange="calculateTotal()">
                @error('discount')<div style="color:#e74c3c; margin-top:4px;">{{ $message }}</div>@enderror
            </div>

            <div class="form-group">
                <label for="customer_name">Nom du Client</label>
                <input type="text" id="customer_name" name="customer_name" value="{{ $sale->customer_name }}">
                @error('customer_name')<div style="color:#e74c3c; margin-top:4px;">{{ $message }}</div>@enderror
            </div>

            <div class="form-group">
                <label for="payment_method">Mode de Paiement <span style="color: red;">*</span></label>
                <select id="payment_method" name="payment_method" required>
                    <option value="cash" @if($sale->payment_method === 'cash') selected @endif>Espèces</option>
                    <option value="card" @if($sale->payment_method === 'card') selected @endif>Carte</option>
                    <option value="check" @if($sale->payment_method === 'check') selected @endif>Chèque</option>
                </select>
                @error('payment_method')<div style="color:#e74c3c; margin-top:4px;">{{ $message }}</div>@enderror
            </div>

            <div class="form-group">
                <strong>Prix Total (Calculé):</strong>
                <div id="totalPrice" style="font-size: 18px; color: #27ae60; font-weight: bold;">
                    {{ number_format($sale->total_price, 2) }} €
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">💾 Enregistrer les modifications</button>
                <a href="{{ route('sales.show', $sale) }}" class="btn btn-secondary">← Retour</a>
            </div>
        </form>
    </div>

    <style>
        .page-header {
            margin-bottom: 20px;
        }
        .page-header h1 {
            margin: 0 0 10px 0;
        }
        .breadcrumb {
            font-size: 12px;
            color: #7f8c8d;
        }
        .breadcrumb a {
            color: #3498db;
            text-decoration: none;
        }
        .card {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
        .card-header {
            padding: 15px;
            border-bottom: 1px solid #ecf0f1;
            background: #f8f9fa;
            border-radius: 8px 8px 0 0;
        }
        .card-header h2 {
            margin: 0;
            font-size: 18px;
            color: #2c3e50;
        }
        .card-body {
            padding: 20px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: 600;
            color: #2c3e50;
        }
        .form-group input,
        .form-group select {
            width: 100%;
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
            box-sizing: border-box;
        }
        .form-group input:focus,
        .form-group select:focus {
            outline: none;
            border-color: #3498db;
            box-shadow: 0 0 4px rgba(52, 152, 219, 0.3);
        }
        .form-actions {
            padding: 15px;
            border-top: 1px solid #ecf0f1;
            text-align: right;
        }
        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            margin-right: 10px;
            font-weight: 600;
        }
        .btn-primary {
            background-color: #27ae60;
            color: white;
        }
        .btn-primary:hover {
            background-color: #229954;
        }
        .btn-secondary {
            background-color: #95a5a6;
            color: white;
        }
        .btn-secondary:hover {
            background-color: #7f8c8d;
        }
    </style>

    <script>
        function updatePrice() {
            calculateTotal();
        }

        function calculateTotal() {
            const medicineSelect = document.getElementById('medicine_id');
            const selectedOption = medicineSelect.options[medicineSelect.selectedIndex];
            const price = parseFloat(selectedOption.dataset.price);
            const quantity = parseInt(document.getElementById('quantity').value) || 0;
            const discount = parseFloat(document.getElementById('discount').value) || 0;

            const total = (price * quantity) - discount;
            document.getElementById('totalPrice').textContent = total.toFixed(2) + ' €';
        }

        // Calculer au chargement
        document.addEventListener('DOMContentLoaded', calculateTotal);
    </script>
@endsection
