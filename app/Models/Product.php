<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class Product extends Model
{
    use HasFactory, SoftDeletes;

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
        // BelongsTo tidak punya method withTrashed() bawaan di Laravel 10,
        // jadi scope soft-delete-nya dilepas manual di sini. Efeknya sama:
        // nama kategori tetap muncul di riwayat/laporan walau kategorinya
        // sudah dihapus (soft delete) setelah produk ini ada.
        return $this->belongsTo(Category::class)->withoutGlobalScope(SoftDeletingScope::class);
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
        return $this->transactions()
            ->whereIn('status', ['Diterima', 'Dikeluarkan'])
            ->get()
            ->sum(fn($transaction) => $transaction->type === 'Masuk' ? $transaction->quantity : -$transaction->quantity);
    }
}
