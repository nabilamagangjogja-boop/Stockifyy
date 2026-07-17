<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockTransaction extends Model
{
    use HasFactory;

    protected $fillable = ['product_id', 'user_id', 'type', 'quantity', 'date', 'status', 'notes'];

    protected $casts = [
        'date' => 'date',
    ];

    public function product()
    {
        // BelongsTo tidak punya method withTrashed() bawaan di Laravel 10,
        // jadi scope soft-delete dilepas manual supaya riwayat transaksi/
        // laporan lama tetap bisa menampilkan nama produk yang sudah
        // "dihapus" (soft delete) dari daftar produk aktif.
        return $this->belongsTo(Product::class)->withoutGlobalScope(\Illuminate\Database\Eloquent\SoftDeletingScope::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
