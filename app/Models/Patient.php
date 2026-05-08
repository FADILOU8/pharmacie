<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    use HasFactory;

    protected $fillable = [
        'pharmacy_id',
        'name',
        'phone',
        'email',
        'address',
        'loyalty_points',
        'registration_date',
    ];

    protected $casts = [
        'registration_date' => 'datetime',
    ];

    public function pharmacy()
    {
        return $this->belongsTo(Pharmacy::class);
    }

    public function prescriptions()
    {
        return $this->hasMany(Prescription::class);
    }
}
