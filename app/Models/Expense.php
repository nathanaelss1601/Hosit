<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    protected $fillable = [
        'user_id',
        'category',
        'description',
        'amount',
        'expense_date',
        'receipt_image',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'expense_date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getReceiptImageUrlAttribute(): ?string
    {
        if (!$this->receipt_image) {
            return null;
        }

        return route('expenses.receipt', $this->id, absolute: false);
    }

    public static function getCategories()
    {
        return [
            'Gaji Karyawan',
            'Listrik',
            'Air',
            'Gas',
            'Bahan Baku',
            'Peralatan',
            'Promosi & Marketing',
            'Sewa Tempat',
            'Maintenance',
            'Lain-lain',
        ];
    }
}
