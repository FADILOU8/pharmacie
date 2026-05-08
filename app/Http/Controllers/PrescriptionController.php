<?php

namespace App\Http\Controllers;

use App\Models\Prescription;
use App\Models\Patient;
use App\Models\Pharmacy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PrescriptionController extends Controller
{
    public function index()
    {
        $pharmacy = Auth::user()->currentPharmacy();

        if (!$pharmacy) {
            return redirect()->route('dashboard');
        }

        $prescriptions = Prescription::where('pharmacy_id', $pharmacy->id)->latest()->get();
        return view('prescriptions.index', compact('prescriptions', 'pharmacy'));
    }

    public function create()
    {
        $pharmacy = Auth::user()->currentPharmacy();
        $patients = Patient::where('pharmacy_id', $pharmacy->id)->get();

        return view('prescriptions.create', compact('pharmacy', 'patients'));
    }

    public function store(Request $request)
    {
        $pharmacy = Auth::user()->currentPharmacy();

        $data = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'doctor_name' => 'required|string|max:255',
            'prescription_date' => 'required|date',
            'medicines' => 'required|string',
            'status' => 'required|in:pending,dispensed,archived',
        ]);

        $data['pharmacy_id'] = $pharmacy->id;
        Prescription::create($data);

        return redirect()->route('prescriptions.index')->with('success', 'Ordonnance enregistrée.');
    }

    public function show(Prescription $prescription)
    {
        $pharmacy = Auth::user()->currentPharmacy();

        if ($prescription->pharmacy_id !== $pharmacy->id) {
            abort(403);
        }

        return view('prescriptions.show', compact('prescription', 'pharmacy'));
    }

    public function edit(Prescription $prescription)
    {
        $pharmacy = Auth::user()->currentPharmacy();

        if ($prescription->pharmacy_id !== $pharmacy->id) {
            abort(403);
        }

        $patients = Patient::where('pharmacy_id', $pharmacy->id)->get();
        return view('prescriptions.edit', compact('prescription', 'patients', 'pharmacy'));
    }

    public function update(Request $request, Prescription $prescription)
    {
        $pharmacy = Auth::user()->currentPharmacy();

        if ($prescription->pharmacy_id !== $pharmacy->id) {
            abort(403);
        }

        $data = $request->validate([
            'status' => 'required|in:pending,dispensed,archived',
            'medicines' => 'required|string',
        ]);

        $prescription->update($data);

        return redirect()->route('prescriptions.index')->with('success', 'Ordonnance mise à jour.');
    }

    public function destroy(Prescription $prescription)
    {
        $pharmacy = Auth::user()->currentPharmacy();

        if ($prescription->pharmacy_id !== $pharmacy->id) {
            abort(403);
        }

        $prescription->delete();

        return redirect()->route('prescriptions.index')->with('success', 'Ordonnance supprimée.');
    }
}
