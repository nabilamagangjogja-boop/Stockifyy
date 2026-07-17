<?php

namespace App\Services;

use App\Models\StockTransaction;
use App\Repositories\Contracts\StockTransactionRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class StockTransactionService
{
    public function __construct(
        protected StockTransactionRepositoryInterface $transactions,
        protected ActivityLogService $activityLog
    ) {}

    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return $this->transactions->paginateWithRelations($perPage);
    }

    public function latest(int $limit = 10): Collection
    {
        return $this->transactions->latestWithRelations($limit);
    }

    public function create(Request $request): StockTransaction
    {
        $validated = Validator::make($request->all(), [
            'product_id' => 'required|exists:products,id',
            'type' => 'required|in:Masuk,Keluar',
            'quantity' => 'required|integer|min:1',
            'date' => 'required|date',
            'status' => 'required|in:Pending,Diterima,Ditolak,Dikeluarkan',
            'notes' => 'nullable|string',
        ])->after(function ($validator) use ($request) {
            $type = $request->input('type');
            $productId = $request->input('product_id');
            $quantity = (int) $request->input('quantity');

            if ($type === 'Keluar' && $productId && $quantity > 0) {
                $product = \App\Models\Product::find($productId);
                if ($product && $quantity > $product->current_stock) {
                    $validator->errors()->add(
                        'quantity',
                        "Jumlah melebihi stok yang tersedia. Sisa stok \"{$product->name}\" saat ini {$product->current_stock}, sedangkan Anda memasukkan {$quantity}."
                    );
                }
            }
        })->validate();

        $validated['user_id'] = $request->user()?->id ?? 1;

        $transaction = $this->transactions->create($validated);
        $this->activityLog->log(
            'create',
            'Transaksi',
            "Mencatat transaksi {$transaction->type} untuk produk \"{$transaction->product->name}\" sejumlah {$transaction->quantity}."
        );
        return $transaction;
    }

    /**
     * Staff Gudang mengonfirmasi barang masuk/keluar yang sebelumnya berstatus Pending.
     * Masuk -> Diterima (stok bertambah), Keluar -> Dikeluarkan (stok berkurang).
     */
    public function confirm(StockTransaction $transaction): StockTransaction
    {
        $status = $transaction->type === 'Masuk' ? 'Diterima' : 'Dikeluarkan';
        $transaction = $this->transactions->updateStatus($transaction, $status);
        $this->activityLog->log(
            'confirm',
            'Transaksi',
            "Mengonfirmasi transaksi {$transaction->type} untuk produk \"{$transaction->product->name}\" sejumlah {$transaction->quantity}."
        );
        return $transaction;
    }

    public function reject(StockTransaction $transaction): StockTransaction
    {
        $transaction = $this->transactions->updateStatus($transaction, 'Ditolak');
        $this->activityLog->log(
            'reject',
            'Transaksi',
            "Menolak transaksi {$transaction->type} untuk produk \"{$transaction->product->name}\" sejumlah {$transaction->quantity}."
        );
        return $transaction;
    }

    /**
     * Bikin transaksi penyesuaian otomatis dari Stock Opname. Langsung berstatus final
     * (Diterima/Dikeluarkan) karena stock opname itu sendiri sudah proses verifikasi
     * fisik — tidak perlu tahap konfirmasi tambahan lagi.
     */
    public function createAdjustment(\App\Models\Product $product, int $userId, string $type, int $quantity, string $date, string $notes): StockTransaction
    {
        return $this->transactions->create([
            'product_id' => $product->id,
            'user_id' => $userId,
            'type' => $type,
            'quantity' => $quantity,
            'date' => $date,
            'status' => $type === 'Masuk' ? 'Diterima' : 'Dikeluarkan',
            'notes' => $notes,
        ]);
    }
}
