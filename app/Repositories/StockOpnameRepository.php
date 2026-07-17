<?php

namespace App\Repositories;

use App\Models\StockOpname;
use App\Repositories\Contracts\StockOpnameRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class StockOpnameRepository implements StockOpnameRepositoryInterface
{
    public function paginateWithRelations(int $perPage = 15): LengthAwarePaginator
    {
        return StockOpname::with(['product', 'user'])->latest('opname_date')->latest('id')->paginate($perPage);
    }

    public function create(array $data): StockOpname
    {
        return StockOpname::create($data);
    }
}
