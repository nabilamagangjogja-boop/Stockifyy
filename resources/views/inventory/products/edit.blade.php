@extends('layouts.app')

@section('content')
    <div class="mx-auto max-w-3xl rounded-3xl border border-white/70 bg-white/80 p-6 shadow-soft">
        <h2 class="text-2xl font-semibold">Edit Produk</h2>
        <form action="{{ route('products.update', $product) }}" method="POST" enctype="multipart/form-data"
            class="mt-6 grid gap-4 md:grid-cols-2">
            @csrf
            @method('PUT')
            <div>
                <label class="mb-1 block text-sm font-medium">Nama Produk</label>
                <input name="name" value="{{ $product->name }}"
                    class="w-full rounded-2xl border border-blush bg-cream px-4 py-3" required>
            </div>
            <div>
                <label class="mb-1 block text-sm font-medium">SKU</label>
                <input name="sku" value="{{ $product->sku }}"
                    class="w-full rounded-2xl border border-blush bg-cream px-4 py-3" required>
            </div>
            <div>
                <label class="mb-1 block text-sm font-medium">Kategori</label>
                <select name="category_id" class="w-full rounded-2xl border border-blush bg-cream px-4 py-3" required>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" @selected($product->category_id == $category->id)>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="mb-1 block text-sm font-medium">Supplier</label>
                <select name="supplier_id" class="w-full rounded-2xl border border-blush bg-cream px-4 py-3" required>
                    @foreach($suppliers as $supplier)
                        <option value="{{ $supplier->id }}" @selected($product->supplier_id == $supplier->id)>
                            {{ $supplier->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="mb-1 block text-sm font-medium">Harga Beli</label>
                <input type="number" step="0.01" name="purchase_price" value="{{ $product->purchase_price }}"
                    class="w-full rounded-2xl border border-blush bg-cream px-4 py-3" required>
            </div>
            <div>
                <label class="mb-1 block text-sm font-medium">Harga Jual</label>
                <input type="number" step="0.01" name="selling_price" value="{{ $product->selling_price }}"
                    class="w-full rounded-2xl border border-blush bg-cream px-4 py-3" required>
            </div>
            <div>
                <label class="mb-1 block text-sm font-medium">Stok Minimum</label>
                <input type="number" name="minimum_stock" value="{{ $product->minimum_stock }}"
                    class="w-full rounded-2xl border border-blush bg-cream px-4 py-3">
            </div>
            <div class="md:col-span-2">
                <label class="mb-1 block text-sm font-medium">Deskripsi</label>
                <textarea name="description"
                    class="w-full rounded-2xl border border-blush bg-cream px-4 py-3">{{ $product->description }}</textarea>
            </div>
            <div class="md:col-span-2">
                <label class="mb-1 block text-sm font-medium">Gambar Produk</label>
                <input type="file" name="image" accept="image/*"
                    class="w-full rounded-2xl border border-blush bg-cream px-4 py-3">
                @if($product->image)
                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}"
                        class="mt-3 h-24 w-24 rounded-2xl object-cover">
                @endif
            </div>
            <div class="md:col-span-2 flex justify-end gap-3">
                <a href="{{ route('products.index') }}" class="rounded-full bg-blush px-4 py-2">Batal</a>
                <button class="rounded-full bg-ink px-4 py-2 text-white">Perbarui</button>
            </div>
        </form>
    </div>
@endsection