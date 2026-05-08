<?php

namespace App\Http\Controllers;

use App\Models\Pharmacy;
use Illuminate\Http\Request;

class PharmacyController extends Controller
{
    public function index()
    {
        $pharmacies = Pharmacy::orderBy('name')->get();

        return view('pharmacies.index', compact('pharmacies'));
    }

    public function create()
    {
        return view('pharmacies.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:500',
            'city' => 'required|string|max:255',
            'phone' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
            'open_hours' => 'nullable|string|max:255',
        ]);

        Pharmacy::create($data);

        return redirect()->route('pharmacies.index')->with('success', 'Pharmacie ajoutée avec succès.');
    }

    public function show(Pharmacy $pharmacy)
    {
        return view('pharmacies.show', compact('pharmacy'));
    }

    public function edit(Pharmacy $pharmacy)
    {
        return view('pharmacies.edit', compact('pharmacy'));
    }

    public function update(Request $request, Pharmacy $pharmacy)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:500',
            'city' => 'required|string|max:255',
            'phone' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
            'open_hours' => 'nullable|string|max:255',
        ]);

        $pharmacy->update($data);

        return redirect()->route('pharmacies.index')->with('success', 'Pharmacie mise à jour avec succès.');
    }

    public function destroy(Pharmacy $pharmacy)
    {
        $pharmacy->delete();

        return redirect()->route('pharmacies.index')->with('success', 'Pharmacie supprimée avec succès.');
    }
}
