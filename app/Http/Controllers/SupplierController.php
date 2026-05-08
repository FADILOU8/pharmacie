<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use App\Models\Pharmacy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SupplierController extends Controller
{
    public function index()
    {
        $pharmacy = Auth::user()->currentPharmacy();

        if (!$pharmacy) {
            return redirect()->route('dashboard');
        }

        $suppliers = Supplier::where('pharmacy_id', $pharmacy->id)->get();
        return view('suppliers.index', compact('suppliers', 'pharmacy'));
    }

    public function create()
    {
        $pharmacy = Auth::user()->currentPharmacy();
        return view('suppliers.create', compact('pharmacy'));
    }

    public function store(Request $request)
    {
        $pharmacy = Auth::user()->currentPharmacy();

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'contact_person' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
            'address' => 'required|string|max:500',
            'payment_terms' => 'nullable|string|max:255',
        ]);

        $data['pharmacy_id'] = $pharmacy->id;
        Supplier::create($data);

        return redirect()->route('suppliers.index')->with('success', 'Fournisseur ajouté.');
    }

    public function edit(Supplier $supplier)
    {
        $pharmacy = Auth::user()->currentPharmacy();

        if ($supplier->pharmacy_id !== $pharmacy->id) {
            abort(403);
        }

        return view('suppliers.edit', compact('supplier', 'pharmacy'));
    }

    public function update(Request $request, Supplier $supplier)
    {
        $pharmacy = Auth::user()->currentPharmacy();

        if ($supplier->pharmacy_id !== $pharmacy->id) {
            abort(403);
        }

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'contact_person' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
            'address' => 'required|string|max:500',
            'payment_terms' => 'nullable|string|max:255',
        ]);

        $supplier->update($data);

        return redirect()->route('suppliers.index')->with('success', 'Fournisseur mis à jour.');
    }

    public function destroy(Supplier $supplier)
    {
        $pharmacy = Auth::user()->currentPharmacy();

        if ($supplier->pharmacy_id !== $pharmacy->id) {
            abort(403);
        }

        $supplier->delete();

        return redirect()->route('suppliers.index')->with('success', 'Fournisseur supprimé.');
    }
}
