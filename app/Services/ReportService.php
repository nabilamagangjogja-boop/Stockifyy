<?php

namespace App\Services;

use App\Models\StockTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class ReportService
{
    /**
     * Ambil transaksi sesuai filter (tanggal dari-sampai, kategori, tipe).
     * Dipakai bareng oleh tampilan tabel, export CSV, dan cetak PDF — supaya
     * hasil yang di-export selalu sama persis dengan yang ditampilkan di layar.
     */
    public function filteredTransactions(Request $request): Collection
    {
        $query = StockTransaction::with(['product.category', 'user']);

        if ($request->filled('date_from')) {
            $query->whereDate('date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('date', '<=', $request->date_to);
        }

        if ($request->filled('category_id')) {
            $query->whereHas('product', function ($q) use ($request) {
                $q->where('category_id', $request->category_id);
            });
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        return $query->latest('date')->get();
    }
}
