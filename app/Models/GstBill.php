<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GstBill extends Model
{
    use HasFactory;

    protected $fillable = [
        'bill_number',
        'order_id',
        'customer_name',
        'customer_address',
        'customer_gstin',
        'bill_date',
        'hsn_code',
        'transport',
        'no_of_cases',
        'place_of_supply',
        'subtotal',
        'cgst_rate',
        'cgst_amount',
        'sgst_rate',
        'sgst_amount',
        'igst_rate',
        'igst_amount',
        'round_off',
        'grand_total',
        'amount_in_words'
    ];

    protected $casts = [
        'bill_date' => 'date',
        'subtotal' => 'decimal:2',
        'cgst_rate' => 'decimal:2',
        'cgst_amount' => 'decimal:2',
        'sgst_rate' => 'decimal:2',
        'sgst_amount' => 'decimal:2',
        'igst_rate' => 'decimal:2',
        'igst_amount' => 'decimal:2',
        'round_off' => 'decimal:2',
        'grand_total' => 'decimal:2',
    ];

    public function items()
    {
        return $this->hasMany(GstBillItem::class, 'gst_bill_id');
    }

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }
}
