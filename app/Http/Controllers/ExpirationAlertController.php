<?php

namespace App\Http\Controllers;

use App\Models\ExpirationAlert;
use App\Models\Medicine;
use App\Models\Pharmacy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExpirationAlertController extends Controller
{
    public function index()
    {
        $pharmacy = Auth::user()->currentPharmacy();

        if (!$pharmacy) {
            return redirect()->route('dashboard');
        }

        $alerts = ExpirationAlert::where('pharmacy_id', $pharmacy->id)
            ->orderBy('expiration_date')
            ->get();

        return view('alerts.index', compact('alerts', 'pharmacy'));
    }

    public function checkExpiration()
    {
        $pharmacy = Auth::user()->currentPharmacy();

        $medicines = Medicine::where('pharmacy_id', $pharmacy->id)->get();

        foreach ($medicines as $medicine) {
            $days_until_expiration = now()->diffInDays($medicine->expiration_date);

            $alert_type = 'normal';
            if ($days_until_expiration <= 0) {
                $alert_type = 'expired';
            } elseif ($days_until_expiration <= 30) {
                $alert_type = 'critical';
            }

            ExpirationAlert::updateOrCreate(
                ['medicine_id' => $medicine->id],
                [
                    'pharmacy_id' => $pharmacy->id,
                    'medicine_name' => $medicine->name,
                    'lot_number' => $medicine->lot_number,
                    'expiration_date' => $medicine->expiration_date,
                    'days_until_expiration' => $days_until_expiration,
                    'alert_type' => $alert_type,
                    'status' => 'active',
                ]
            );
        }

        return redirect()->route('alerts.index')->with('success', 'Vérification d\'expiration complétée.');
    }

    public function markResolved(ExpirationAlert $alert)
    {
        $pharmacy = Auth::user()->currentPharmacy();

        if ($alert->pharmacy_id !== $pharmacy->id) {
            abort(403);
        }

        $alert->update(['status' => 'resolved']);

        return redirect()->route('alerts.index')->with('success', 'Alerte marquée comme résolue.');
    }
}
