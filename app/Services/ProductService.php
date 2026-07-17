<?php

namespace App\Services;

use App\Models\Product;
use App\Repositories\Contracts\ProductRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProductService
{
    public function __construct(
        protected ProductRepositoryInterface $products,
        protected ActivityLogService $activityLog,
        protected StockTransactionService $stockTransactions
    ) {}

    public function all(): Collection
    {
        return $this->products->allWithRelations();
    }

    /**
     * Produk aktif (belum di-soft-delete/di-nonaktifkan) buat dipilih di
     * form Stock Opname. Soft-deleted product otomatis sudah tidak ikut
     * kebawa karena global scope SoftDeletes di model Product.
     */
    public function activeOnly(): Collection
    {
        return $this->products->allWithRelations();
    }

    public function paginate(int $perPage = 10): LengthAwarePaginator
    {
        return $this->products->paginateWithRelations($perPage);
    }

    public function create(Request $request): Product
    {
        $validated = $this->validateData($request, uniqueSkuExcept: null);
        $initialStock = (int) $request->input('initial_stock', 0);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('products', 'public');
        }

        $product = $this->products->create($validated);
        $this->activityLog->log('create', 'Produk', "Menambahkan produk \"{$product->name}\" (SKU {$product->sku}).");

        // Kalau ada stok awal diisi, langsung dicatat sebagai transaksi Masuk yang
        // sudah final (bukan Pending) — supaya stok produk baru tidak nol terus
        // sampai ada yang mencatat transaksi/stock opname secara terpisah.
        if ($initialStock > 0) {
            $this->stockTransactions->createAdjustment(
                product: $product,
                userId: $request->user()->id,
                type: 'Masuk',
                quantity: $initialStock,
                date: now()->format('Y-m-d'),
                notes: 'Stok awal saat produk dibuat'
            );
        }

        return $product;
    }

    public function update(Request $request, Product $product): Product
    {
        $validated = $this->validateData($request, uniqueSkuExcept: $product->id);

        if ($request->hasFile('image')) {
            $this->deleteImage($product);
            $validated['image'] = $request->file('image')->store('products', 'public');
        }

        $product = $this->products->update($product, $validated);
        $this->activityLog->log('update', 'Produk', "Memperbarui produk \"{$product->name}\" (SKU {$product->sku}).");
        return $product;
    }

    public function delete(Product $product): void
    {
        // Soft delete: baris produk tidak benar-benar hilang dari DB (hanya
        // ditandai deleted_at), jadi riwayat transaksi stok yang masih
        // merujuk ke produk ini tetap aman & tetap bisa ditampilkan di
        // laporan. Gambar produk sengaja TIDAK dihapus di sini supaya masih
        // bisa dipakai lagi kalau produknya suatu saat dipulihkan.
        $this->products->delete($product);
        $this->activityLog->log('delete', 'Produk', "Menghapus produk \"{$product->name}\" (SKU {$product->sku}).");
    }

    protected function validateData(Request $request, ?int $uniqueSkuExcept): array
    {
        $skuUniqueRule = $uniqueSkuExcept
            ? 'unique:products,sku,' . $uniqueSkuExcept
            : 'unique:products,sku';

        return Validator::make($request->all(), [
            'category_id' => 'required|exists:categories,id',
            'supplier_id' => 'required|exists:suppliers,id',
            'name' => 'required|string|max:150',
            'sku' => ['required', 'string', 'max:30', $skuUniqueRule],
            'description' => 'nullable|string',
            'purchase_price' => 'required|numeric',
            'selling_price' => 'required|numeric',
            'minimum_stock' => 'nullable|integer',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ], [
            'name.max' => 'Nama produk maksimal 150 karakter.',
            'sku.max' => 'SKU maksimal 30 karakter.',
        ])->validate();
    }

    protected function deleteImage(Product $product): void
    {
        if ($product->image && Storage::disk('public')->exists($product->image)) {
            Storage::disk('public')->delete($product->image);
        }
    }

    /**
     * Data produk lengkap buat di-export ke CSV.
     */
    public function exportRows(): Collection
    {
        return $this->products->allWithRelations();
    }

    /**
     * Import produk dari CSV. Kolom wajib (header, urutan bebas):
     * nama, sku, kategori, supplier, deskripsi, harga_beli, harga_jual, stok_minimum
     *
     * SKU yang sudah ada -> produk di-update. SKU baru -> produk baru dibuat.
     * Kategori/supplier dicocokkan berdasarkan nama (case-insensitive); kalau
     * tidak ketemu, baris itu gagal dan dicatat di $errors tapi baris lain
     * tetap lanjut diproses.
     */
    public function importFromCsv(\Illuminate\Http\UploadedFile $file): array
    {
        $handle = fopen($file->getRealPath(), 'r');
        $header = fgetcsv($handle);
        $header = array_map(fn($h) => strtolower(trim((string) $h)), $header ?: []);

        $created = 0;
        $updated = 0;
        $errors = [];
        $rowNumber = 1; // baris 1 = header

        while (($row = fgetcsv($handle)) !== false) {
            $rowNumber++;

            if (count(array_filter($row, fn($v) => trim((string) $v) !== '')) === 0) {
                continue; // lewati baris kosong
            }

            $data = array_combine($header, array_pad($row, count($header), null));

            $name = trim((string) ($data['nama'] ?? ''));
            $sku = trim((string) ($data['sku'] ?? ''));
            $categoryName = trim((string) ($data['kategori'] ?? ''));
            $supplierName = trim((string) ($data['supplier'] ?? ''));

            if ($name === '' || $sku === '') {
                $errors[] = "Baris {$rowNumber}: nama dan sku wajib diisi.";
                continue;
            }

            if (mb_strlen($name) > 150) {
                $errors[] = "Baris {$rowNumber}: nama produk maksimal 150 karakter.";
                continue;
            }

            if (mb_strlen($sku) > 30) {
                $errors[] = "Baris {$rowNumber}: sku maksimal 30 karakter.";
                continue;
            }

            $category = \App\Models\Category::whereRaw('LOWER(name) = ?', [strtolower($categoryName)])->first();
            if (!$category) {
                $errors[] = "Baris {$rowNumber}: kategori \"{$categoryName}\" tidak ditemukan.";
                continue;
            }

            $supplier = \App\Models\Supplier::whereRaw('LOWER(name) = ?', [strtolower($supplierName)])->first();
            if (!$supplier) {
                $errors[] = "Baris {$rowNumber}: supplier \"{$supplierName}\" tidak ditemukan.";
                continue;
            }

            $payload = [
                'category_id' => $category->id,
                'supplier_id' => $supplier->id,
                'name' => $name,
                'sku' => $sku,
                'description' => trim((string) ($data['deskripsi'] ?? '')) ?: null,
                'purchase_price' => (float) ($data['harga_beli'] ?? 0),
                'selling_price' => (float) ($data['harga_jual'] ?? 0),
                'minimum_stock' => (int) ($data['stok_minimum'] ?? 0),
            ];

            $existing = Product::withTrashed()->where('sku', $sku)->first();

            if ($existing) {
                $existing->update($payload);
                $updated++;
            } else {
                $this->products->create($payload);
                $created++;
            }
        }

        fclose($handle);

        $this->activityLog->log(
            'create',
            'Produk',
            "Import CSV produk: {$created} baru, {$updated} diperbarui" . (count($errors) ? ', ' . count($errors) . ' baris gagal' : '.')
        );

        return compact('created', 'updated', 'errors');
    }
}
