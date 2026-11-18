<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'invoice_number',
        'user_id',
        'customer_name',
        'subtotal',
        'tax',
        'discount',
        'total',
        'payment_method',
        'payment_amount',
        'change_amount',
        'status',
        'notes',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'tax' => 'decimal:2',
        'discount' => 'decimal:2',
        'total' => 'decimal:2',
        'payment_amount' => 'decimal:2',
        'change_amount' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }


    public function items()
    {
        return $this->hasMany(TransactionItem::class);
    }

    public static function generateInvoiceNumber()
    {
        $prefix = 'INV';
        $date = now()->format('Ymd');
        $lastTransaction = self::whereDate('created_at', now()->toDateString())
            ->orderBy('id', 'desc')
            ->first();
        
        $number = $lastTransaction ? (int) substr($lastTransaction->invoice_number, -4) + 1 : 1;
        
        return $prefix . $date . str_pad($number, 4, '0', STR_PAD_LEFT);
    }

    public function getTotalProfitAttribute()
    {
        return $this->items->sum(function ($item) {
            return ($item->price - $item->cost) * $item->quantity;
        });
    }

    public function getTotalCostAttribute()
    {
        return $this->items->sum(function ($item) {
            return $item->cost * $item->quantity;
        });
    }
}
