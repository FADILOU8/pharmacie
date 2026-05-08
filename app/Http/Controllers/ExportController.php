<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\Prescription;
use App\Models\Sale;
use App\Models\Pharmacy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class ExportController extends Controller
{
    /**
     * Exporter une prescription en PDF
     */
    public function prescriptionPdf(Prescription $prescription)
    {
        if ($prescription->pharmacy_id !== Auth::user()->currentPharmacy()->id) {
            abort(403);
        }

        $pdf = Pdf::loadView('exports.prescription', ['prescription' => $prescription]);
        return $pdf->download('ordonnance-' . $prescription->id . '.pdf');
    }

    /**
     * Exporter une prescription en CSV
     */
    public function prescriptionCsv(Prescription $prescription)
    {
        if ($prescription->pharmacy_id !== Auth::user()->currentPharmacy()->id) {
            abort(403);
        }

        return response()->streamDownload(function () use ($prescription) {
            echo "Ordonnance ID,Patient,Médecin,Date,Médicaments,Statut\n";
            echo $prescription->id . ",";
            echo $prescription->patient->name . ",";
            echo $prescription->doctor_name . ",";
            echo $prescription->prescription_date->format('d/m/Y') . ",";
            echo "\"" . str_replace('"', '""', $prescription->medicines) . "\",";
            echo $prescription->status . "\n";
        }, 'ordonnance-' . $prescription->id . '.csv');
    }

    /**
     * Envoyer une prescription via WhatsApp
     */
    public function patientPdf(Patient $patient)
    {
        $pharmacy = Auth::user()->currentPharmacy();

        if (!$pharmacy || $patient->pharmacy_id !== $pharmacy->id) {
            abort(403);
        }

        $pdf = Pdf::loadView('exports.patient', ['patient' => $patient]);
        return $pdf->download('patient-' . $patient->id . '.pdf');
    }

    public function patientCsv(Patient $patient)
    {
        $pharmacy = Auth::user()->currentPharmacy();

        if (!$pharmacy || $patient->pharmacy_id !== $pharmacy->id) {
            abort(403);
        }

        return response()->streamDownload(function () use ($patient) {
            echo "Nom,Telephone,Email,Adresse,Points de fidélité,Date d'enregistrement\n";
            echo $patient->name . ",";
            echo ($patient->phone ?? '-') . ",";
            echo ($patient->email ?? '-') . ",";
            echo '"' . str_replace('"', '""', $patient->address) . '",';
            echo ($patient->loyalty_points ?? 0) . ",";
            echo $patient->registration_date?->format('d/m/Y') . "\n";

            echo "\nOrdonnances\n";
            echo "ID,Date,Médecin,Statut,Médicaments\n";
            foreach ($patient->prescriptions as $prescription) {
                echo $prescription->id . ",";
                echo $prescription->prescription_date->format('d/m/Y') . ",";
                echo '"' . str_replace('"', '""', $prescription->doctor_name) . '",';
                echo $prescription->status . ",";
                echo '"' . str_replace('"', '""', $prescription->medicines) . '"\n';
            }
        }, 'patient-' . $patient->id . '.csv');
    }

    public function allPrescriptionsPdf()
    {
        $pharmacy = Auth::user()->currentPharmacy();

        if (!$pharmacy) {
            abort(403);
        }

        $prescriptions = Prescription::where('pharmacy_id', $pharmacy->id)->latest()->get();

        $pdf = Pdf::loadView('exports.prescriptions-list', compact('prescriptions'));
        return $pdf->download('ordonnances-' . now()->format('Y-m-d') . '.pdf');
    }

    public function allPrescriptionsCsv()
    {
        $pharmacy = Auth::user()->currentPharmacy();

        if (!$pharmacy) {
            abort(403);
        }

        $prescriptions = Prescription::where('pharmacy_id', $pharmacy->id)->latest()->get();

        return response()->streamDownload(function () use ($prescriptions) {
            echo "ID,Patient,Médecin,Date,Statut,Médicaments\n";
            foreach ($prescriptions as $prescription) {
                echo $prescription->id . ",";
                echo '"' . str_replace('"', '""', $prescription->patient->name) . '",';
                echo '"' . str_replace('"', '""', $prescription->doctor_name) . '",';
                echo $prescription->prescription_date->format('d/m/Y') . ",";
                echo $prescription->status . ",";
                echo '"' . str_replace('"', '""', $prescription->medicines) . '"\n';
            }
        }, 'ordonnances-' . now()->format('Y-m-d') . '.csv');
    }

    public function sendWhatsApp(Request $request, Prescription $prescription)
    {
        $request->validate([
            'phone' => 'required|string|regex:/^[0-9\+\-\s\(\)]*$/',
        ]);

        $pharmacy = Auth::user()->currentPharmacy();

        if (!$pharmacy || $prescription->pharmacy_id !== $pharmacy->id) {
            abort(403);
        }

        $phone = $this->normalizePhoneNumber($request->phone);
        $message = $this->formatPrescriptionMessage($prescription);

        // Construire l'URL WhatsApp
        $whatsappUrl = "https://wa.me/{$phone}?text=" . urlencode($message);

        return redirect($whatsappUrl);
    }

    /**
     * Exporter les ventes en CSV
     */
    public function salesCsv(Request $request)
    {
        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date');
        $pharmacy = Auth::user()->currentPharmacy();

        $query = Sale::where('pharmacy_id', $pharmacy->id);

        if ($startDate) {
            $query->whereDate('sale_date', '>=', $startDate);
        }
        if ($endDate) {
            $query->whereDate('sale_date', '<=', $endDate);
        }

        $sales = $query->get();

        return response()->streamDownload(function () use ($sales) {
            echo "Date,Médicament,Quantité,Prix Unitaire,Remise,Total,Mode Paiement,Client\n";
            foreach ($sales as $sale) {
                echo $sale->sale_date->format('d/m/Y H:i') . ",";
                echo $sale->medicine->name . ",";
                echo $sale->quantity . ",";
                echo $sale->unit_price . ",";
                echo $sale->discount . ",";
                echo $sale->total_price . ",";
                echo $sale->payment_method . ",";
                echo $sale->customer_name . "\n";
            }
        }, 'ventes-' . now()->format('Y-m-d') . '.csv');
    }

    /**
     * Exporter les patients en CSV
     */
    public function patientsCsv()
    {
        $pharmacy = Auth::user()->currentPharmacy();
        $patients = \App\Models\Patient::where('pharmacy_id', $pharmacy->id)->get();

        return response()->streamDownload(function () use ($patients) {
            echo "Nom,Téléphone,Email,Adresse,Points Fidélité,Date d'Enregistrement\n";
            foreach ($patients as $patient) {
                echo $patient->name . ",";
                echo $patient->phone . ",";
                echo $patient->email . ",";
                echo "\"" . str_replace('"', '""', $patient->address) . "\",";
                echo $patient->loyalty_points . ",";
                echo $patient->registration_date->format('d/m/Y') . "\n";
            }
        }, 'patients-' . now()->format('Y-m-d') . '.csv');
    }

    /**
     * Normaliser un numéro de téléphone pour WhatsApp
     */
    private function normalizePhoneNumber($phone)
    {
        $phone = preg_replace('/[^0-9+]/', '', $phone);

        // Si commence par 0, le remplacer par +33
        if (substr($phone, 0, 1) === '0') {
            $phone = '33' . substr($phone, 1);
        }

        // Ajouter + si absent
        if (substr($phone, 0, 1) !== '+') {
            $phone = '+' . $phone;
        }

        return $phone;
    }

    /**
     * Formatter une prescription pour WhatsApp
     */
    private function formatPrescriptionMessage($prescription)
    {
        return sprintf(
            "Ordonnance ID: %s\n\n" .
            "Patient: %s\n" .
            "Médecin: %s\n" .
            "Date: %s\n\n" .
            "Médicaments:\n%s\n\n" .
            "Statut: %s",
            $prescription->id,
            $prescription->patient->name,
            $prescription->doctor_name,
            $prescription->prescription_date->format('d/m/Y'),
            $prescription->medicines,
            $prescription->status
        );
    }
}
