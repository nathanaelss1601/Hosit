<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'category_id',
        'name',
        'description',
        'price',
        'cost',
        'image',
        'is_available',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'cost' => 'decimal:2',
        'is_available' => 'boolean',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function transactionItems()
    {
        return $this->hasMany(TransactionItem::class);
    }

    public function getUnitProfitAttribute()
    {
        return $this->price - $this->cost;
    }

    public function getProfitMarginAttribute()
    {
        return $this->price > 0 ? (($this->price - $this->cost) / $this->price) * 100 : 0;
    }
}
