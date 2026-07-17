<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Supplier;
use App\Services\ActivityLogService;
use App\Services\CategoryService;
use App\Services\DashboardService;
use App\Services\ProductService;
use App\Services\ReportService;
use App\Services\StockTransactionService;
use App\Services\SupplierService;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    public function __construct(
        protected DashboardService $dashboardService,
        protected CategoryService $categoryService,
        protected SupplierService $supplierService,
        protected ProductService $productService,
        protected StockTransactionService $transactionService,
        protected ReportService $reportService,
        protected \App\Services\ProductAttributeService $attributeService,
        protected \App\Services\StockOpnameService $stockOpnameService,
        protected ActivityLogService $activityLogService
    ) {}

    public function dashboard(Request $request)
    {
        $role = $request->user()->role;

        return view('inventory.dashboard', array_merge(
            ['role' => $role],
            $this->dashboardService->summaryFor($role)
        ));
    }

    public function categoriesIndex()
    {
        $categories = $this->categoryService->paginate(10);
        return view('inventory.categories.index', compact('categories'));
    }

    public function categoriesCreate()
    {
        return view('inventory.categories.create');
    }

    public function categoriesStore(Request $request)
    {
        $this->categoryService->create($request->all());
        return redirect()->route('categories.index')->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function categoriesEdit(Category $category)
    {
        return view('inventory.categories.edit', compact('category'));
    }

    public function categoriesUpdate(Request $request, Category $category)
    {
        $this->categoryService->update($category, $request->all());
        return redirect()->route('categories.index')->with('success', 'Kategori berhasil diperbarui.');
    }

    public function categoriesDestroy(Category $category)
    {
        try {
            $this->categoryService->delete($category);
        } catch (\App\Exceptions\HasRelatedDataException $e) {
            return redirect()->route('categories.index')
                ->with('error', $e->getMessage());
        }
        return redirect()->route('categories.index')->with('success', 'Kategori berhasil dihapus.');
    }

    public function suppliersIndex()
    {
        $suppliers = $this->supplierService->paginate(10);
        return view('inventory.suppliers.index', compact('suppliers'));
    }

    public function suppliersCreate()
    {
        return view('inventory.suppliers.create');
    }

    public function suppliersStore(Request $request)
    {
        $this->supplierService->create($request->all());
        return redirect()->route('suppliers.index')->with('success', 'Supplier berhasil ditambahkan.');
    }

    public function suppliersEdit(Supplier $supplier)
    {
        return view('inventory.suppliers.edit', compact('supplier'));
    }

    public function suppliersUpdate(Request $request, Supplier $supplier)
    {
        $this->supplierService->update($supplier, $request->all());
        return redirect()->route('suppliers.index')->with('success', 'Supplier berhasil diperbarui.');
    }

    public function suppliersDestroy(Supplier $supplier)
    {
        try {
            $this->supplierService->delete($supplier);
        } catch (\Illuminate\Database\QueryException $e) {
            return redirect()->route('suppliers.index')
                ->with('error', 'Supplier "' . $supplier->name . '" tidak bisa dihapus karena masih dipakai oleh satu atau lebih produk.');
        }
        return redirect()->route('suppliers.index')->with('success', 'Supplier berhasil dihapus.');
    }

    public function productsIndex(Request $request)
    {
        $status = $request->query('status', 'active'); // default: cuma tampilkan yang aktif
        $products = $this->productService->paginate(10, $status === 'all' ? null : $status);
        return view('inventory.products.index', compact('products', 'status'));
    }

    public function productsCreate()
    {
        $categories = $this->categoryService->all();
        $suppliers = $this->supplierService->all();
        return view('inventory.products.create', compact('categories', 'suppliers'));
    }

    public function productsStore(Request $request)
    {
        $this->productService->create($request);
        return redirect()->route('products.index')->with('success', 'Produk berhasil ditambahkan.');
    }

    public function productsEdit(Product $product)
    {
        $categories = $this->categoryService->all();
        $suppliers = $this->supplierService->all();
        $product->load('attributes');
        return view('inventory.products.edit', compact('product', 'categories', 'suppliers'));
    }

    public function productsUpdate(Request $request, Product $product)
    {
        $this->productService->update($request, $product);
        return redirect()->route('products.index')->with('success', 'Produk berhasil diperbarui.');
    }

    public function productsDestroy(Product $product)
    {
        try {
            $this->productService->delete($product);
        } catch (\Illuminate\Database\QueryException $e) {
            return redirect()->route('products.index')
                ->with('error', 'Produk "' . $product->name . '" tidak bisa dihapus karena masih punya riwayat transaksi stok. Coba nonaktifkan produk ini saja agar datanya tetap aman.');
        }
        return redirect()->route('products.index')->with('success', 'Produk berhasil dihapus.');
    }

    public function productsExportCsv()
    {
        $products = $this->productService->exportRows();
        $filename = 'produk-' . now()->format('Y-m-d_His') . '.csv';

        $callback = function () use ($products) {
            $handle = fopen('php://output', 'w');
            fwrite($handle, "\xEF\xBB\xBF");
            fputcsv($handle, ['nama', 'sku', 'kategori', 'supplier', 'deskripsi', 'harga_beli', 'harga_jual', 'stok_minimum']);

            foreach ($products as $p) {
                fputcsv($handle, [
                    $p->name,
                    $p->sku,
                    $p->category->name ?? '',
                    $p->supplier->name ?? '',
                    $p->description,
                    $p->purchase_price,
                    $p->selling_price,
                    $p->minimum_stock,
                ]);
            }

            fclose($handle);
        };

        return response()->stream($callback, 200, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    public function productsImport(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt|max:5120',
        ]);

        $result = $this->productService->importFromCsv($request->file('file'));

        $message = "{$result['created']} produk baru ditambahkan, {$result['updated']} produk diperbarui.";

        if (!empty($result['errors'])) {
            return redirect()->route('products.index')->with(
                'error',
                $message . ' Ada ' . count($result['errors']) . ' baris gagal: ' . implode(' | ', $result['errors'])
            );
        }

        return redirect()->route('products.index')->with('success', $message);
    }

    public function transactionsIndex()
    {
        $transactions = $this->transactionService->paginate(15);
        $products = $this->productService->activeOnly(); // produk nonaktif tidak muncul di form transaksi baru
        return view('inventory.transactions.index', compact('transactions', 'products'));
    }

    public function productsArchive(Product $product)
    {
        $this->productService->archive($product);
        return back()->with('success', 'Produk "' . $product->name . '" berhasil dinonaktifkan. Riwayat transaksinya tetap aman.');
    }

    public function productsRestore(Product $product)
    {
        $this->productService->restore($product);
        return back()->with('success', 'Produk "' . $product->name . '" berhasil diaktifkan kembali.');
    }

    public function transactionsStore(Request $request)
    {
        $this->transactionService->create($request);
        return redirect()->route('transactions.index')->with('success', 'Transaksi stok berhasil dicatat.');
    }

    public function transactionsConfirm(\App\Models\StockTransaction $transaction)
    {
        $this->transactionService->confirm($transaction);
        return back()->with('success', 'Transaksi berhasil dikonfirmasi.');
    }

    public function transactionsReject(\App\Models\StockTransaction $transaction)
    {
        $this->transactionService->reject($transaction);
        return back()->with('success', 'Transaksi ditolak.');
    }

    public function reportsIndex(Request $request)
    {
        $transactions = $this->reportService->filteredTransactions($request);
        $categories = $this->categoryService->all();

        return view('inventory.reports.index', compact('transactions', 'categories'));
    }

    public function reportsExportCsv(Request $request)
    {
        $transactions = $this->reportService->filteredTransactions($request);
        $filename = 'laporan-transaksi-' . now()->format('Y-m-d_His') . '.csv';

        $callback = function () use ($transactions) {
            $handle = fopen('php://output', 'w');
            // BOM biar Excel baca UTF-8 dengan benar (nama produk pakai karakter non-ASCII tetap kebaca)
            fwrite($handle, "\xEF\xBB\xBF");
            fputcsv($handle, ['Tanggal', 'Produk', 'Kategori', 'Tipe', 'Jumlah', 'Status', 'Dicatat Oleh', 'Catatan']);

            foreach ($transactions as $t) {
                fputcsv($handle, [
                    $t->date->format('Y-m-d'),
                    $t->product->name ?? '-',
                    $t->product->category->name ?? '-',
                    $t->type,
                    $t->quantity,
                    $t->status,
                    $t->user->name ?? '-',
                    $t->notes ?? '',
                ]);
            }

            fclose($handle);
        };

        return response()->stream($callback, 200, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    public function reportsPrint(Request $request)
    {
        $transactions = $this->reportService->filteredTransactions($request);
        return view('inventory.reports.print', compact('transactions', 'request'));
    }

    public function reportsActivity(Request $request)
    {
        $logs = $this->activityLogService->paginateFiltered(
            $request->only(['user_id', 'module', 'action', 'date_from', 'date_to']),
            15
        );
        $users = \App\Models\User::orderBy('name')->get();

        return view('inventory.reports.activity', compact('logs', 'users'));
    }

    public function attributesStore(Request $request, Product $product)
    {
        $this->attributeService->create($product, $request);
        return back()->with('success', 'Atribut produk berhasil ditambahkan.');
    }

    public function attributesDestroy(Product $product, \App\Models\ProductAttribute $attribute)
    {
        $this->attributeService->delete($attribute);
        return back()->with('success', 'Atribut produk berhasil dihapus.');
    }

    public function stockOpnameIndex()
    {
        $opnames = $this->stockOpnameService->paginate(15);
        $products = $this->productService->activeOnly();
        return view('inventory.stock-opname.index', compact('opnames', 'products'));
    }

    public function stockOpnameStore(Request $request)
    {
        $this->stockOpnameService->create($request);
        return redirect()->route('stock-opname.index')->with('success', 'Stock opname berhasil dicatat. Stok sistem sudah disesuaikan otomatis.');
    }
}
