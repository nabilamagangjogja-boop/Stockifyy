@extends('layouts.app')

@section('content')
    <div class="mx-auto max-w-3xl rounded-3xl border border-white/70 bg-white/80 p-6 shadow-soft">
        <h2 class="text-2xl font-semibold">Tambah Produk</h2>
        <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data"
            class="mt-6 grid gap-4 md:grid-cols-2">
            @csrf
            <div>
                <label class="mb-1 block text-sm font-medium">Nama Produk</label>
                <input name="name" class="w-full rounded-2xl border border-blush bg-cream px-4 py-3" required>
            </div>
            <div>
                <label class="mb-1 block text-sm font-medium">SKU</label>
                <input name="sku" class="w-full rounded-2xl border border-blush bg-cream px-4 py-3" required>
            </div>
            <div>
                <label class="mb-1 block text-sm font-medium">Kategori</label>
                <select name="category_id" class="w-full rounded-2xl border border-blush bg-cream px-4 py-3" required>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="mb-1 block text-sm font-medium">Supplier</label>
                <select name="supplier_id" class="w-full rounded-2xl border border-blush bg-cream px-4 py-3" required>
                    @foreach($suppliers as $supplier)
                        <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="mb-1 block text-sm font-medium">Harga Beli</label>
                <input type="number" step="0.01" name="purchase_price"
                    class="w-full rounded-2xl border border-blush bg-cream px-4 py-3" required>
            </div>
            <div>
                <label class="mb-1 block text-sm font-medium">Harga Jual</label>
                <input type="number" step="0.01" name="selling_price"
                    class="w-full rounded-2xl border border-blush bg-cream px-4 py-3" required>
            </div>
            <div>
                <label class="mb-1 block text-sm font-medium">Stok Minimum</label>
                <input type="number" name="minimum_stock" value="0"
                    class="w-full rounded-2xl border border-blush bg-cream px-4 py-3">
            </div>
            <div class="md:col-span-2">
                <label class="mb-1 block text-sm font-medium">Deskripsi</label>
                <textarea name="description" class="w-full rounded-2xl border border-blush bg-cream px-4 py-3"></textarea>
            </div>
            <div class="md:col-span-2">
                <label class="mb-1 block text-sm font-medium">Gambar Produk</label>
                <input type="file" name="image" accept="image/*"
                    class="w-full rounded-2xl border border-blush bg-cream px-4 py-3">
            </div>
            <div class="md:col-span-2 flex justify-end gap-3">
                <a href="{{ route('products.index') }}" class="rounded-full bg-blush px-4 py-2">Batal</a>
                <button class="rounded-full bg-ink px-4 py-2 text-white">Simpan</button>
            </div>
        </form>
    </div>
@endsection