<?php

namespace App\Repositories\Contracts;

use App\Models\StockOpname;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface StockOpnameRepositoryInterface
{
    public function paginateWithRelations(int $perPage = 15): LengthAwarePaginator;

    public function create(array $data): StockOpname;
}
