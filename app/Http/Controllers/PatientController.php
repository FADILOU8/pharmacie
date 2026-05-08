<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\Pharmacy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PatientController extends Controller
{
    protected function getPatientForCurrentUser(): ?Patient
    {
        return Patient::where('email', Auth::user()->email)->first();
    }

    public function myPrescriptions()
    {
        $patient = $this->getPatientForCurrentUser();

        if (!$patient) {
            return view('patients.my-prescriptions', [
                'patient' => null,
                'prescriptions' => collect(),
            ]);
        }

        $prescriptions = $patient->prescriptions()->latest('prescription_date')->get();

        return view('patients.my-prescriptions', compact('patient', 'prescriptions'));
    }

    public function index()
    {
        $pharmacy = Auth::user()->currentPharmacy();

        if (!$pharmacy) {
            return redirect()->route('dashboard');
        }

        $patients = Patient::where('pharmacy_id', $pharmacy->id)->get();
        return view('patients.index', compact('patients', 'pharmacy'));
    }

    public function create()
    {
        $pharmacy = Auth::user()->currentPharmacy();
        return view('patients.create', compact('pharmacy'));
    }

    public function store(Request $request)
    {
        $pharmacy = Auth::user()->currentPharmacy();

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string|max:500',
        ]);

        $data['pharmacy_id'] = $pharmacy->id;
        $data['loyalty_points'] = 0;
        $data['registration_date'] = now();

        Patient::create($data);

        return redirect()->route('patients.index')->with('success', 'Patient ajouté.');
    }

    public function edit(Patient $patient)
    {
        $pharmacy = Auth::user()->currentPharmacy();

        if ($patient->pharmacy_id !== $pharmacy->id) {
            abort(403);
        }

        return view('patients.edit', compact('patient', 'pharmacy'));
    }

    public function show(Patient $patient)
    {
        $pharmacy = Auth::user()->currentPharmacy();

        if ($patient->pharmacy_id !== $pharmacy->id) {
            abort(403);
        }

        $prescriptions = $patient->prescriptions()->orderByDesc('prescription_date')->get();

        return view('patients.show', compact('patient', 'prescriptions', 'pharmacy'));
    }

    public function update(Request $request, Patient $patient)
    {
        $pharmacy = Auth::user()->currentPharmacy();

        if ($patient->pharmacy_id !== $pharmacy->id) {
            abort(403);
        }

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string|max:500',
        ]);

        $patient->update($data);

        return redirect()->route('patients.index')->with('success', 'Patient mis à jour.');
    }

    public function destroy(Patient $patient)
    {
        $pharmacy = Auth::user()->currentPharmacy();

        if ($patient->pharmacy_id !== $pharmacy->id) {
            abort(403);
        }

        $patient->delete();

        return redirect()->route('patients.index')->with('success', 'Patient supprimé.');
    }
}
