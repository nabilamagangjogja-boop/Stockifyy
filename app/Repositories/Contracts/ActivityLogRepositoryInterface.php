<?php

namespace App\Repositories\Contracts;

use App\Models\ActivityLog;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface ActivityLogRepositoryInterface
{
    public function create(array $data): ActivityLog;

    public function paginateFiltered(array $filters, int $perPage = 20): LengthAwarePaginator;
}
