<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Medicine;
use App\Models\Pharmacy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SaleController extends Controller
{
    public function index()
    {
        $pharmacy = Auth::user()->currentPharmacy();

        if (!$pharmacy) {
            return redirect()->route('dashboard');
        }

        $sales = Sale::where('pharmacy_id', $pharmacy->id)->latest()->get();
        return view('sales.index', compact('sales', 'pharmacy'));
    }

    public function create()
    {
        $pharmacy = Auth::user()->currentPharmacy();
        $medicines = Medicine::where('pharmacy_id', $pharmacy->id)->where('quantity', '>', 0)->get();

        return view('sales.create', compact('pharmacy', 'medicines'));
    }

    public function store(Request $request)
    {
        $pharmacy = Auth::user()->currentPharmacy();

        $data = $request->validate([
            'medicine_id' => 'required|exists:medicines,id',
            'quantity' => 'required|integer|min:1',
            'customer_name' => 'nullable|string|max:255',
            'discount' => 'nullable|numeric|min:0',
            'payment_method' => 'required|in:cash,card,check',
        ]);

        $medicine = Medicine::find($data['medicine_id']);

        if ($medicine->quantity < $data['quantity']) {
            return back()->withErrors(['quantity' => 'Stock insuffisant.']);
        }

        $total_price = ($medicine->unit_price * $data['quantity']) - ($data['discount'] ?? 0);

        Sale::create([
            'pharmacy_id' => $pharmacy->id,
            'medicine_id' => $data['medicine_id'],
            'quantity' => $data['quantity'],
            'unit_price' => $medicine->unit_price,
            'total_price' => $total_price,
            'discount' => $data['discount'] ?? 0,
            'customer_name' => $data['customer_name'],
            'payment_method' => $data['payment_method'],
            'sale_date' => now(),
        ]);

        $medicine->decrement('quantity', $data['quantity']);

        return redirect()->route('sales.index')->with('success', 'Vente enregistrée.');
    }

    public function show(Sale $sale)
    {
        $pharmacy = Auth::user()->currentPharmacy();

        if (!$pharmacy || $sale->pharmacy_id !== $pharmacy->id) {
            abort(403);
        }

        $medicine = $sale->medicine;
        return view('sales.show', compact('sale', 'medicine'));
    }

    public function edit(Sale $sale)
    {
        $pharmacy = Auth::user()->currentPharmacy();

        if (!$pharmacy || $sale->pharmacy_id !== $pharmacy->id) {
            abort(403);
        }

        $medicines = Medicine::where('pharmacy_id', $pharmacy->id)->get();
        return view('sales.edit', compact('sale', 'medicines', 'pharmacy'));
    }

    public function update(Request $request, Sale $sale)
    {
        $pharmacy = Auth::user()->currentPharmacy();

        if (!$pharmacy || $sale->pharmacy_id !== $pharmacy->id) {
            abort(403);
        }

        $data = $request->validate([
            'medicine_id' => 'required|exists:medicines,id',
            'quantity' => 'required|integer|min:1',
            'customer_name' => 'nullable|string|max:255',
            'discount' => 'nullable|numeric|min:0',
            'payment_method' => 'required|in:cash,card,check',
        ]);

        $oldMedicine = $sale->medicine;
        $newMedicine = Medicine::find($data['medicine_id']);

        // Restaurer l'ancienne quantité
        $oldMedicine->increment('quantity', $sale->quantity);

        // Vérifier la nouvelle quantité
        if ($newMedicine->quantity < $data['quantity']) {
            $oldMedicine->decrement('quantity', $sale->quantity);
            return back()->withErrors(['quantity' => 'Stock insuffisant.']);
        }

        $total_price = ($newMedicine->unit_price * $data['quantity']) - ($data['discount'] ?? 0);

        $sale->update([
            'medicine_id' => $data['medicine_id'],
            'quantity' => $data['quantity'],
            'unit_price' => $newMedicine->unit_price,
            'total_price' => $total_price,
            'discount' => $data['discount'] ?? 0,
            'customer_name' => $data['customer_name'],
            'payment_method' => $data['payment_method'],
        ]);

        // Décrémenter la nouvelle quantité
        $newMedicine->decrement('quantity', $data['quantity']);

        return redirect()->route('sales.index')->with('success', 'Vente mise à jour.');
    }

    public function destroy(Sale $sale)
    {
        $pharmacy = Auth::user()->currentPharmacy();

        if (!$pharmacy || $sale->pharmacy_id !== $pharmacy->id) {
            abort(403);
        }

        $medicine = $sale->medicine;
        $medicine->increment('quantity', $sale->quantity);

        $sale->delete();

        return redirect()->route('sales.index')->with('success', 'Vente supprimée.');
    }
}
