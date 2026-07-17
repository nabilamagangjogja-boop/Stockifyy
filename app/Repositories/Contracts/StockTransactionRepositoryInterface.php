<?php

namespace App\Repositories\Contracts;

use App\Models\StockTransaction;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface StockTransactionRepositoryInterface
{
    public function paginateWithRelations(int $perPage = 15): LengthAwarePaginator;

    public function latestWithRelations(int $limit = 10): Collection;

    public function create(array $data): StockTransaction;

    public function todayConfirmedByType(string $type): Collection;

    public function pendingByType(string $type): Collection;

    public function updateStatus(StockTransaction $transaction, string $status): StockTransaction;
}
