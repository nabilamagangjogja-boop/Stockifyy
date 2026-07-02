<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\StockTransaction;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class InventoryController extends Controller
{
    public function dashboard()
    {
        $products = Product::with(['category', 'supplier'])->get();
        $categories = Category::all();
        $suppliers = Supplier::all();
        $transactions = StockTransaction::with(['product', 'user'])->latest()->take(10)->get();
        $totalProducts = $products->count();
        $totalStock = $products->sum('current_stock');

        return view('inventory.dashboard', compact('products', 'categories', 'suppliers', 'transactions', 'totalProducts', 'totalStock'));
    }

    public function categoriesIndex()
    {
        $categories = Category::latest()->paginate(10);
        return view('inventory.categories.index', compact('categories'));
    }

    public function categoriesCreate()
    {
        return view('inventory.categories.create');
    }

    public function categoriesStore(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        Category::create($request->only(['name', 'description']));

        return redirect()->route('categories.index')->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function categoriesEdit(Category $category)
    {
        return view('inventory.categories.edit', compact('category'));
    }

    public function categoriesUpdate(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $category->update($request->only(['name', 'description']));

        return redirect()->route('categories.index')->with('success', 'Kategori berhasil diperbarui.');
    }

    public function categoriesDestroy(Category $category)
    {
        $category->delete();
        return redirect()->route('categories.index')->with('success', 'Kategori berhasil dihapus.');
    }

    public function suppliersIndex()
    {
        $suppliers = Supplier::latest()->paginate(10);
        return view('inventory.suppliers.index', compact('suppliers'));
    }

    public function suppliersStore(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable|string',
            'phone' => 'nullable|string',
            'email' => 'nullable|email',
        ]);

        Supplier::create($request->only(['name', 'address', 'phone', 'email']));

        return redirect()->route('suppliers.index')->with('success', 'Supplier berhasil ditambahkan.');
    }

    public function productsIndex()
    {
        $products = Product::with(['category', 'supplier'])->latest()->paginate(10);
        return view('inventory.products.index', compact('products'));
    }

    public function productsCreate()
    {
        $categories = Category::all();
        $suppliers = Supplier::all();
        return view('inventory.products.create', compact('categories', 'suppliers'));
    }

    public function productsStore(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'supplier_id' => 'required|exists:suppliers,id',
            'name' => 'required|string|max:255',
            'sku' => 'required|string|unique:products,sku',
            'description' => 'nullable|string',
            'purchase_price' => 'required|numeric',
            'selling_price' => 'required|numeric',
            'minimum_stock' => 'nullable|integer',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $data = $request->only([
            'category_id',
            'supplier_id',
            'name',
            'sku',
            'description',
            'purchase_price',
            'selling_price',
            'minimum_stock'
        ]);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        Product::create($data);

        return redirect()->route('products.index')->with('success', 'Produk berhasil ditambahkan.');
    }

    public function productsEdit(Product $product)
    {
        $categories = Category::all();
        $suppliers = Supplier::all();
        return view('inventory.products.edit', compact('product', 'categories', 'suppliers'));
    }

    public function productsUpdate(Request $request, Product $product)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'supplier_id' => 'required|exists:suppliers,id',
            'name' => 'required|string|max:255',
            'sku' => 'required|string|unique:products,sku,' . $product->id,
            'description' => 'nullable|string',
            'purchase_price' => 'required|numeric',
            'selling_price' => 'required|numeric',
            'minimum_stock' => 'nullable|integer',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $data = $request->only([
            'category_id',
            'supplier_id',
            'name',
            'sku',
            'description',
            'purchase_price',
            'selling_price',
            'minimum_stock'
        ]);

        if ($request->hasFile('image')) {
            if ($product->image && Storage::disk('public')->exists($product->image)) {
                Storage::disk('public')->delete($product->image);
            }
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        $product->update($data);

        return redirect()->route('products.index')->with('success', 'Produk berhasil diperbarui.');
    }

    public function productsDestroy(Product $product)
    {
        $product->delete();
        return redirect()->route('products.index')->with('success', 'Produk berhasil dihapus.');
    }

    public function transactionsIndex()
    {
        $transactions = StockTransaction::with(['product', 'user'])->latest()->paginate(15);
        return view('inventory.transactions.index', compact('transactions'));
    }

    public function transactionsStore(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'type' => 'required|in:Masuk,Keluar',
            'quantity' => 'required|integer|min:1',
            'date' => 'required|date',
            'status' => 'required|in:Pending,Diterima,Ditolak,Dikeluarkan',
            'notes' => 'nullable|string',
        ]);

        StockTransaction::create([
            'product_id' => $request->product_id,
            'user_id' => 1,
            'type' => $request->type,
            'quantity' => $request->quantity,
            'date' => $request->date,
            'status' => $request->status,
            'notes' => $request->notes,
        ]);

        return redirect()->route('transactions.index')->with('success', 'Transaksi stok berhasil dicatat.');
    }
}
