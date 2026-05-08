@extends('layouts.admin')

@section('title', 'Nouvelle commande fournisseur - Gestion Pharmacie')

@section('content')
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
        <h1>🆕 Nouvelle commande fournisseur</h1>
        <a href="{{ route('orders.index') }}" class="btn btn-secondary">Retour aux commandes</a>
    </div>

    <div class="card">
        <form action="{{ route('orders.store') }}" method="POST">
            @csrf

            <div class="form-group">
                <label for="supplier_id">Fournisseur</label>
                <select id="supplier_id" name="supplier_id" required>
                    <option value="">Sélectionner un fournisseur</option>
                    @foreach($suppliers as $supplier)
                        <option value="{{ $supplier->id }}" {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}>{{ $supplier->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="order_date">Date de commande</label>
                <input type="date" id="order_date" name="order_date" value="{{ old('order_date', now()->toDateString()) }}" required>
            </div>

            <div class="form-group">
                <label for="delivery_date">Date de livraison prévue</label>
                <input type="date" id="delivery_date" name="delivery_date" value="{{ old('delivery_date') }}">
            </div>

            <h2 style="margin-top: 24px; margin-bottom: 16px;">Lignes de commande</h2>
            <div id="medicine-lines">
                @php
                    $oldLines = old('medicines', [
                        ['dci' => '', 'name' => '', 'form' => '', 'dosage' => '', 'lot_number' => '', 'quantity' => '', 'unit_price' => '', 'expiration_date' => ''],
                    ]);
                @endphp

                @foreach($oldLines as $index => $line)
                    <div class="card" style="padding: 18px; margin-bottom: 16px;">
                        <div class="form-group">
                            <label>DCI</label>
                            <input type="text" name="medicines[{{ $index }}][dci]" value="{{ $line['dci'] ?? '' }}">
                        </div>
                        <div class="form-group">
                            <label>Nom du médicament</label>
                            <input type="text" name="medicines[{{ $index }}][name]" value="{{ $line['name'] ?? '' }}" required>
                        </div>
                        <div class="form-group">
                            <label>Forme</label>
                            <input type="text" name="medicines[{{ $index }}][form]" value="{{ $line['form'] ?? '' }}">
                        </div>
                        <div class="form-group">
                            <label>Dosage</label>
                            <input type="text" name="medicines[{{ $index }}][dosage]" value="{{ $line['dosage'] ?? '' }}">
                        </div>
                        <div class="form-group">
                            <label>Numéro de lot</label>
                            <input type="text" name="medicines[{{ $index }}][lot_number]" value="{{ $line['lot_number'] ?? '' }}" required>
                        </div>
                        <div class="form-group">
                            <label>Quantité</label>
                            <input type="number" name="medicines[{{ $index }}][quantity]" value="{{ $line['quantity'] ?? '' }}" min="1" required>
                        </div>
                        <div class="form-group">
                            <label>Prix unitaire</label>
                            <input type="number" step="0.01" name="medicines[{{ $index }}][unit_price]" value="{{ $line['unit_price'] ?? '' }}" min="0" required>
                        </div>
                        <div class="form-group">
                            <label>Date d'expiration</label>
                            <input type="date" name="medicines[{{ $index }}][expiration_date]" value="{{ $line['expiration_date'] ?? '' }}">
                        </div>
                        <button type="button" class="btn btn-danger remove-line" style="margin-top:10px;">Supprimer cette ligne</button>
                    </div>
                @endforeach
            </div>

            <button type="button" id="add-line" class="btn btn-secondary" style="margin-bottom: 24px;">+ Ajouter une ligne</button>
            <button type="submit" class="btn btn-primary">Enregistrer la commande</button>
        </form>
    </div>

    <script>
        function createLine(index, values = {}) {
            return `
                <div class="card" style="padding: 18px; margin-bottom: 16px;">
                    <div class="form-group">
                        <label>DCI</label>
                        <input type="text" name="medicines[${index}][dci]" value="${values.dci || ''}">
                    </div>
                    <div class="form-group">
                        <label>Nom du médicament</label>
                        <input type="text" name="medicines[${index}][name]" value="${values.name || ''}" required>
                    </div>
                    <div class="form-group">
                        <label>Forme</label>
                        <input type="text" name="medicines[${index}][form]" value="${values.form || ''}">
                    </div>
                    <div class="form-group">
                        <label>Dosage</label>
                        <input type="text" name="medicines[${index}][dosage]" value="${values.dosage || ''}">
                    </div>
                    <div class="form-group">
                        <label>Numéro de lot</label>
                        <input type="text" name="medicines[${index}][lot_number]" value="${values.lot_number || ''}" required>
                    </div>
                    <div class="form-group">
                        <label>Quantité</label>
                        <input type="number" name="medicines[${index}][quantity]" value="${values.quantity || ''}" min="1" required>
                    </div>
                    <div class="form-group">
                        <label>Prix unitaire</label>
                        <input type="number" step="0.01" name="medicines[${index}][unit_price]" value="${values.unit_price || ''}" min="0" required>
                    </div>
                    <div class="form-group">
                        <label>Date d'expiration</label>
                        <input type="date" name="medicines[${index}][expiration_date]" value="${values.expiration_date || ''}">
                    </div>
                    <button type="button" class="btn btn-danger remove-line" style="margin-top:10px;">Supprimer cette ligne</button>
                </div>
            `;
        }

        const container = document.getElementById('medicine-lines');
        document.getElementById('add-line').addEventListener('click', function () {
            const index = container.children.length;
            container.insertAdjacentHTML('beforeend', createLine(index));
            bindRemove();
        });

        function bindRemove() {
            document.querySelectorAll('.remove-line').forEach(button => {
                button.onclick = function () {
                    this.closest('.card').remove();
                };
            });
        }

        bindRemove();
    </script>
@endsection
