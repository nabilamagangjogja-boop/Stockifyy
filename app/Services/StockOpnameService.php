<?php

namespace App\Services;

use App\Models\Product;
use App\Models\StockOpname;
use App\Repositories\Contracts\StockOpnameRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class StockOpnameService
{
    public function __construct(
        protected StockOpnameRepositoryInterface $opnames,
        protected StockTransactionService $transactionService,
        protected ActivityLogService $activityLog
    ) {}

    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return $this->opnames->paginateWithRelations($perPage);
    }

    /**
     * Catat stock opname baru. Stok sistem diambil otomatis dari stok produk
     * saat ini, selisih dihitung, lalu jika ada selisih maka dibuatkan
     * transaksi penyesuaian otomatis (via StockTransactionService) supaya
     * stok sistem kembali sesuai dengan hasil hitung fisik.
     */
    public function create(Request $request): StockOpname
    {
        $validated = Validator::make($request->all(), [
            'product_id' => 'required|exists:products,id',
            'physical_stock' => 'required|integer|min:0',
            'opname_date' => 'required|date',
            'notes' => 'nullable|string',
        ])->validate();

        $product = Product::findOrFail($validated['product_id']);
        $systemStock = $product->current_stock;
        $physicalStock = (int) $validated['physical_stock'];
        $difference = $physicalStock - $systemStock;
        $userId = $request->user()?->id ?? 1;

        $opname = $this->opnames->create([
            'product_id' => $product->id,
            'user_id' => $userId,
            'system_stock' => $systemStock,
            'physical_stock' => $physicalStock,
            'difference' => $difference,
            'opname_date' => $validated['opname_date'],
            'notes' => $validated['notes'] ?? null,
        ]);

        if ($difference !== 0) {
            $this->transactionService->createAdjustment(
                $product,
                $userId,
                $difference > 0 ? 'Masuk' : 'Keluar',
                abs($difference),
                $validated['opname_date'],
                'Penyesuaian otomatis dari Stock Opname' . (!empty($validated['notes']) ? ': ' . $validated['notes'] : '')
            );
        }

        $this->activityLog->log(
            'create',
            'Stock Opname',
            "Melakukan stock opname produk \"{$product->name}\": stok sistem {$systemStock}, stok fisik {$physicalStock}"
                . ($difference !== 0 ? ", selisih {$difference}." : ", tidak ada selisih.")
        );

        return $opname;
    }
}
