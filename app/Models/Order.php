<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'pharmacy_id',
        'supplier_id',
        'order_date',
        'delivery_date',
        'total_amount',
        'status',
        'medicines',
    ];

    protected $casts = [
        'medicines' => 'array',
        'order_date' => 'date',
        'delivery_date' => 'date',
    ];

    public function pharmacy()
    {
        return $this->belongsTo(Pharmacy::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }
}
