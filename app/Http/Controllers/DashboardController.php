<?php

namespace App\Http\Controllers;

use App\Models\Pharmacy;
use App\Models\Medicine;
use App\Models\Sale;
use App\Models\Prescription;
use App\Models\ExpirationAlert;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $pharmacy = $user->currentPharmacy();

        if ($user->isPatient()) {
            return view('dashboard.patient');
        }

        if (!$pharmacy) {
            if ($user->isPharmacien()) {
                return view('dashboard.create-pharmacy');
            }

            return view('dashboard.no-pharmacy');
        }

        $medicinesCount = Medicine::where('pharmacy_id', $pharmacy->id)->count();
        $salesCount = Sale::where('pharmacy_id', $pharmacy->id)->count();
        $prescriptionsCount = Prescription::where('pharmacy_id', $pharmacy->id)->count();
        $alertsCount = ExpirationAlert::where('pharmacy_id', $pharmacy->id)
            ->where('status', '!=', 'resolved')
            ->count();

        return view('dashboard.index', compact('pharmacy', 'medicinesCount', 'salesCount', 'prescriptionsCount', 'alertsCount'));
    }

    public function storePharmacy(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:500',
            'city' => 'required|string|max:255',
            'phone' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
            'open_hours' => 'nullable|string|max:255',
        ]);

        $data['user_id'] = Auth::id();

        Pharmacy::create($data);

        return redirect()->route('dashboard')->with('success', 'Pharmacie créée avec succès.');
    }
}
