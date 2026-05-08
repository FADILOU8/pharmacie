<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pharmacy extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'address',
        'city',
        'phone',
        'email',
        'open_hours',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function medicines()
    {
        return $this->hasMany(Medicine::class);
    }

    public function sales()
    {
        return $this->hasMany(Sale::class);
    }

    public function prescriptions()
    {
        return $this->hasMany(Prescription::class);
    }

    public function suppliers()
    {
        return $this->hasMany(Supplier::class);
    }

    public function patients()
    {
        return $this->hasMany(Patient::class);
    }
}
