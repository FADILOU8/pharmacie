<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PharmacyController;
use App\Http\Controllers\MedicineController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\PrescriptionController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\ExpirationAlertController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\ChatbotController;
use App\Http\Controllers\ExportController;
use Illuminate\Support\Facades\Route;

// Guest routes
Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

// Public routes
Route::get('/', function () {
    return redirect('/dashboard');
});

// Protected routes
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::middleware('role:pharmacien')->group(function () {
        Route::post('/pharmacy', [DashboardController::class, 'storePharmacy'])->name('pharmacy.store');
        Route::resource('pharmacies', PharmacyController::class);

        Route::resource('prescriptions', PrescriptionController::class);
        Route::get('prescriptions/{prescription}/export/pdf', [ExportController::class, 'prescriptionPdf'])->name('prescriptions.export.pdf');
        Route::get('prescriptions/{prescription}/export/csv', [ExportController::class, 'prescriptionCsv'])->name('prescriptions.export.csv');
        Route::get('/prescriptions/export/pdf', [ExportController::class, 'allPrescriptionsPdf'])->name('export.prescriptions.pdf');
        Route::get('/prescriptions/export/csv', [ExportController::class, 'allPrescriptionsCsv'])->name('export.prescriptions.csv');
        Route::post('/prescriptions/{prescription}/whatsapp', [ExportController::class, 'sendWhatsApp'])->name('export.whatsapp');
    });

    Route::middleware('role:pharmacien,preparateur')->group(function () {
        Route::resource('medicines', MedicineController::class);
        Route::resource('suppliers', SupplierController::class);
        Route::resource('orders', OrderController::class)->except(['edit', 'update']);
        Route::post('/orders/{order}/receive', [OrderController::class, 'receive'])->name('orders.receive');
        Route::resource('alerts', ExpirationAlertController::class, ['only' => ['index']]);
        Route::post('/alerts/check', [ExpirationAlertController::class, 'checkExpiration'])->name('alerts.check');
        Route::post('/alerts/{alert}/resolve', [ExpirationAlertController::class, 'markResolved'])->name('alerts.resolve');
    });

    Route::middleware('role:pharmacien,caissier')->group(function () {
        Route::resource('sales', SaleController::class);
        Route::resource('patients', PatientController::class);
        Route::get('patients/{patient}/export/pdf', [ExportController::class, 'patientPdf'])->name('patients.export.pdf');
        Route::get('patients/{patient}/export/csv', [ExportController::class, 'patientCsv'])->name('patients.export.csv');
        Route::post('/sales/csv', [ExportController::class, 'salesCsv'])->name('export.sales.csv');
        Route::get('/patients/csv', [ExportController::class, 'patientsCsv'])->name('export.patients.csv');
    });

    Route::middleware('role:patient')->group(function () {
        Route::get('/patient/ordonnances', [PatientController::class, 'myPrescriptions'])->name('patient.prescriptions');
    });

    Route::post('/api/chatbot/respond', [ChatbotController::class, 'respond'])->name('chatbot.respond');
    Route::post('/api/chatbot/toggle', [ChatbotController::class, 'toggle'])->name('chatbot.toggle');
});

