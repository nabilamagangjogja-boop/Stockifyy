<?php

namespace App\Repositories;

use App\Models\StockTransaction;
use App\Repositories\Contracts\StockTransactionRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class StockTransactionRepository implements StockTransactionRepositoryInterface
{
    public function paginateWithRelations(int $perPage = 15): LengthAwarePaginator
    {
        return StockTransaction::with(['product', 'user'])->latest()->paginate($perPage);
    }

    public function latestWithRelations(int $limit = 10): Collection
    {
        return StockTransaction::with(['product', 'user'])->latest()->take($limit)->get();
    }

    public function create(array $data): StockTransaction
    {
        return StockTransaction::create($data);
    }

    public function todayConfirmedByType(string $type): Collection
    {
        $confirmedStatus = $type === 'Masuk' ? 'Diterima' : 'Dikeluarkan';

        return StockTransaction::with(['product', 'user'])
            ->where('type', $type)
            ->where('status', $confirmedStatus)
            ->whereDate('date', today())
            ->get();
    }

    public function pendingByType(string $type): Collection
    {
        return StockTransaction::with(['product', 'user'])
            ->where('type', $type)
            ->where('status', 'Pending')
            ->latest()
            ->get();
    }

    public function updateStatus(StockTransaction $transaction, string $status): StockTransaction
    {
        $transaction->update(['status' => $status]);
        return $transaction;
    }
}
