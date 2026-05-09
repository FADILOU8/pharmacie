<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prescription extends Model
{
    use HasFactory;

    protected $fillable = [
        'pharmacy_id',
        'patient_id',
        'doctor_name',
        'prescription_date',
        'medicines',
        'status',
    ];

    public function pharmacy()
    {
        return $this->belongsTo(Pharmacy::class);
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    protected $casts = [
        'prescription_date' => 'date',
        // Encrypt sensitive medical data at rest.
        // doctor_name and medicines contain PHI — encrypted with AES-256-CBC via APP_KEY.
        'doctor_name' => 'encrypted',
        'medicines'   => 'encrypted',
    ];
}
