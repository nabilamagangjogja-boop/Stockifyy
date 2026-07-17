<?php

namespace App\Services;

use App\Models\ActivityLog;
use App\Models\User;
use App\Repositories\Contracts\ActivityLogRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;

class ActivityLogService
{
    public function __construct(
        protected ActivityLogRepositoryInterface $logs
    ) {}

    /**
     * Catat satu aktivitas. Dipanggil dari service lain (Category, Product,
     * Supplier, User, StockTransaction, StockOpname, Auth) tiap ada
     * create/update/delete/login/logout/confirm/reject.
     *
     * $actor dioper manual buat kasus login (user baru saja login, belum
     * tentu ke-set di Auth facade tergantung urutan pemanggilan). Kalau
     * tidak dioper, dipakai auth()->user() yang lagi login.
     */
    public function log(string $action, string $module, string $description, ?User $actor = null): ActivityLog
    {
        $user = $actor ?? Auth::user();

        return $this->logs->create([
            'user_id' => $user?->id,
            'user_name' => $user?->name,
            'user_role' => $user?->role,
            'action' => $action,
            'module' => $module,
            'description' => $description,
        ]);
    }

    public function paginateFiltered(array $filters, int $perPage = 20): LengthAwarePaginator
    {
        return $this->logs->paginateFiltered($filters, $perPage);
    }
}
