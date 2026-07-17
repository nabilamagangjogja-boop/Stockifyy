<?php

namespace App\Services;

use App\Repositories\Contracts\CategoryRepositoryInterface;
use App\Repositories\Contracts\ProductRepositoryInterface;
use App\Repositories\Contracts\StockTransactionRepositoryInterface;
use App\Repositories\Contracts\SupplierRepositoryInterface;

class DashboardService
{
    public function __construct(
        protected ProductRepositoryInterface $products,
        protected CategoryRepositoryInterface $categories,
        protected SupplierRepositoryInterface $suppliers,
        protected StockTransactionRepositoryInterface $transactions,
        protected ActivityLogService $activityLogs
    ) {}

    /**
     * Bangun data dashboard sesuai role user yang login.
     * Admin: ringkasan lengkap. Manajer Gudang: stok & pergerakan hari ini.
     * Staff Gudang: daftar tugas konfirmasi.
     */
    public function summaryFor(string $role): array
    {
        return match ($role) {
            'Manajer Gudang' => $this->summaryForManager(),
            'Staff Gudang' => $this->summaryForStaff(),
            default => $this->summaryForAdmin(),
        };
    }

    protected function summaryForAdmin(): array
    {
        $products = $this->products->allWithRelations();
        $categories = $this->categories->all();
        $suppliers = $this->suppliers->all();
        $transactions = $this->transactions->latestWithRelations(10);

        $masukHariIni = $this->transactions->todayConfirmedByType('Masuk');
        $keluarHariIni = $this->transactions->todayConfirmedByType('Keluar');

        return [
            'products' => $products,
            'categories' => $categories,
            'suppliers' => $suppliers,
            'transactions' => $transactions,
            'totalProducts' => $products->count(),
            'totalStock' => $products->sum('current_stock'),
            'masukHariIniQty' => $masukHariIni->sum('quantity'),
            'keluarHariIniQty' => $keluarHariIni->sum('quantity'),
            // Aktivitas pengguna terbaru diambil dari modul Activity Log (dicatat tiap create/update/delete/login/dll).
            'recentUserActivity' => $this->activityLogs->paginateFiltered([], 5)->items(),
        ];
    }

    protected function summaryForManager(): array
    {
        $products = $this->products->allWithRelations();
        $lowStockProducts = $products->filter(fn($p) => $p->current_stock <= ($p->minimum_stock ?? 0))->values();

        $masukHariIni = $this->transactions->todayConfirmedByType('Masuk');
        $keluarHariIni = $this->transactions->todayConfirmedByType('Keluar');
        $transactions = $this->transactions->latestWithRelations(10);

        return [
            'products' => $products,
            'lowStockProducts' => $lowStockProducts,
            'masukHariIni' => $masukHariIni,
            'keluarHariIni' => $keluarHariIni,
            'transactions' => $transactions,
            'totalProducts' => $products->count(),
            'totalStock' => $products->sum('current_stock'),
        ];
    }

    protected function summaryForStaff(): array
    {
        return [
            'barangMasukPerlu' => $this->transactions->pendingByType('Masuk'),
            'barangKeluarPerlu' => $this->transactions->pendingByType('Keluar'),
        ];
    }
}
