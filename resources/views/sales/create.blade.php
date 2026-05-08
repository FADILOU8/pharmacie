@extends('layouts.admin')

@section('title', 'Nouvelle Vente - Gestion Pharmacie')

@section('content')
    <h1>🛍️ Enregistrer une Vente</h1>

    <div class="card">
        <form action="{{ route('sales.store') }}" method="POST">
            @csrf

            <div class="form-group">
                <label for="medicine_id">Médicament <span style="color: red;">*</span></label>
                <select id="medicine_id" name="medicine_id" required onchange="updatePrice()">
                    <option value="">-- Sélectionner un médicament --</option>
                    @foreach($medicines as $medicine)
                        <option value="{{ $medicine->id }}" data-price="{{ $medicine->unit_price }}" data-qty="{{ $medicine->quantity }}">
                            {{ $medicine->name }} ({{ $medicine->quantity }} en stock) - {{ number_format($medicine->unit_price, 2) }} €
                        </option>
                    @endforeach
                </select>
                @error('medicine_id')<div style="color:#e74c3c; margin-top:4px;">{{ $message }}</div>@enderror
            </div>

            <div class="form-group">
                <label for="quantity">Quantité <span style="color: red;">*</span></label>
                <input type="number" id="quantity" name="quantity" min="1" required onchange="calculateTotal()">
                @error('quantity')<div style="color:#e74c3c; margin-top:4px;">{{ $message }}</div>@enderror
            </div>

            <div class="form-group">
                <label for="discount">Remise (€)</label>
                <input type="number" id="discount" name="discount" step="0.01" min="0" value="0" onchange="calculateTotal()">
                @error('discount')<div style="color:#e74c3c; margin-top:4px;">{{ $message }}</div>@enderror
            </div>

            <div class="form-group">
                <label for="customer_name">Nom du Client</label>
                <input type="text" id="customer_name" name="customer_name">
                @error('customer_name')<div style="color:#e74c3c; margin-top:4px;">{{ $message }}</div>@enderror
            </div>

            <div class="form-group">
                <label for="payment_method">Mode de Paiement <span style="color: red;">*</span></label>
                <select id="payment_method" name="payment_method" required>
                    <option value="cash">Espèces</option>
                    <option value="card">Carte</option>
                    <option value="check">Chèque</option>
                </select>
                @error('payment_method')<div style="color:#e74c3c; margin-top:4px;">{{ $message }}</div>@enderror
            </div>

            <div style="background: #ecf0f1; padding: 16px; border-radius: 6px; margin: 16px 0;">
                <h3>Total: <span id="total">0.00</span> €</h3>
            </div>

            <div style="margin-top: 24px; display: flex; gap: 12px;">
                <button type="submit" class="btn btn-success">✓ Enregistrer Vente</button>
                <a href="{{ route('sales.index') }}" class="btn btn-secondary">Retour</a>
            </div>
        </form>
    </div>

    <script>
        function updatePrice() {
            const select = document.getElementById('medicine_id');
            const option = select.options[select.selectedIndex];
            calculateTotal();
        }

        function calculateTotal() {
            const medicineSelect = document.getElementById('medicine_id');
            const option = medicineSelect.options[medicineSelect.selectedIndex];
            const quantity = parseInt(document.getElementById('quantity').value) || 0;
            const discount = parseFloat(document.getElementById('discount').value) || 0;
            const price = parseFloat(option.dataset.price) || 0;

            const total = (price * quantity) - discount;
            document.getElementById('total').textContent = total.toFixed(2);
        }
    </script>
@endsection
