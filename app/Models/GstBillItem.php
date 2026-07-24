<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GstBillItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'gst_bill_id',
        'stock_id',
        'particulars',
        'qty',
        'rate',
        'per',
        'amount'
    ];

    protected $casts = [
        'qty' => 'integer',
        'rate' => 'decimal:2',
        'amount' => 'decimal:2',
    ];

    public function gstBill()
    {
        return $this->belongsTo(GstBill::class, 'gst_bill_id');
    }

    public function stock()
    {
        return $this->belongsTo(Stock::class, 'stock_id');
    }
}
