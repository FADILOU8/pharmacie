<?php

namespace App\Http\Controllers;

use App\Models\Medicine;
use App\Models\Pharmacy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MedicineController extends Controller
{
    public function index()
    {
        $pharmacy = Auth::user()->currentPharmacy();

        if (!$pharmacy) {
            return redirect()->route('dashboard');
        }

        $medicines = Medicine::where('pharmacy_id', $pharmacy->id)->get();
        return view('medicines.index', compact('medicines', 'pharmacy'));
    }

    public function create()
    {
        $pharmacy = Auth::user()->currentPharmacy();
        return view('medicines.create', compact('pharmacy'));
    }

    public function store(Request $request)
    {
        $pharmacy = Auth::user()->currentPharmacy();

        $data = $request->validate([
            'dci' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'form' => 'required|string|max:255',
            'dosage' => 'required|string|max:100',
            'lot_number' => 'required|string|max:100',
            'quantity' => 'required|integer|min:0',
            'unit_price' => 'required|numeric|min:0',
            'expiration_date' => 'required|date',
            'supplier_id' => 'nullable|exists:suppliers,id',
        ]);

        $data['pharmacy_id'] = $pharmacy->id;
        Medicine::create($data);

        return redirect()->route('medicines.index')->with('success', 'Médicament ajouté.');
    }

    public function edit(Medicine $medicine)
    {
        $pharmacy = Auth::user()->currentPharmacy();

        if ($medicine->pharmacy_id !== $pharmacy->id) {
            abort(403);
        }

        return view('medicines.edit', compact('medicine', 'pharmacy'));
    }

    public function update(Request $request, Medicine $medicine)
    {
        $pharmacy = Auth::user()->currentPharmacy();

        if ($medicine->pharmacy_id !== $pharmacy->id) {
            abort(403);
        }

        $data = $request->validate([
            'dci' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'form' => 'required|string|max:255',
            'dosage' => 'required|string|max:100',
            'lot_number' => 'required|string|max:100',
            'quantity' => 'required|integer|min:0',
            'unit_price' => 'required|numeric|min:0',
            'expiration_date' => 'required|date',
        ]);

        $medicine->update($data);

        return redirect()->route('medicines.index')->with('success', 'Médicament mis à jour.');
    }

    public function destroy(Medicine $medicine)
    {
        $pharmacy = Auth::user()->currentPharmacy();

        if ($medicine->pharmacy_id !== $pharmacy->id) {
            abort(403);
        }

        $medicine->delete();

        return redirect()->route('medicines.index')->with('success', 'Médicament supprimé.');
    }
}
