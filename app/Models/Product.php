<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'supplier_id',
        'name',
        'sku',
        'description',
        'purchase_price',
        'selling_price',
        'image',
        'minimum_stock',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function attributes()
    {
        return $this->hasMany(ProductAttribute::class);
    }

    public function transactions()
    {
        return $this->hasMany(StockTransaction::class);
    }

    public function getCurrentStockAttribute()
    {
        return $this->transactions()->whereIn('status', ['Diterima', 'Dikeluarkan'])->sum(
            fn($transaction) => $transaction->type === 'Masuk' ? $transaction->quantity : -$transaction->quantity
        );
    }
}
