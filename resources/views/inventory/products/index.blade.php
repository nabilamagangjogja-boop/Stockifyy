@extends('layouts.app')

@section('content')
    <div class="rounded-3xl border border-white/70 bg-white/80 p-6 shadow-soft">
        <div class="mb-6 flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-semibold">Daftar Produk</h2>
                <p class="text-sm text-ink/70">Kelola data produk beserta stok minimum dan supplier.</p>
            </div>
            <a href="{{ route('products.create') }}"
                class="rounded-full bg-ink px-4 py-2 text-sm font-medium text-white">Tambah Produk</a>
        </div>

        <div class="overflow-hidden rounded-2xl border border-blush/60">
            <table class="min-w-full divide-y divide-blush/60">
                <thead class="bg-blush/40">
                    <tr>
                        <th class="px-4 py-3 text-left text-sm font-semibold">Nama</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold">SKU</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold">Kategori</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold">Supplier</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold">Stok</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-blush/40 bg-white/70">
                    @foreach($products as $product)
                        <tr>
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-3">
                                    @if($product->image)
                                        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="h-12 w-12 rounded-xl object-cover">
                                    @else
                                        <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-blush/60 text-xs text-ink/70">No Img</div>
                                    @endif
                                    <span>{{ $product->name }}</span>
                                </div>
                            </td>
                            <td class="px-4 py-3">{{ $product->sku }}</td>
                            <td class="px-4 py-3">{{ $product->category->name ?? '-' }}</td>
                            <td class="px-4 py-3">{{ $product->supplier->name ?? '-' }}</td>
                            <td class="px-4 py-3">{{ $product->current_stock }}</td>
                            <td class="px-4 py-3">
                                <div class="flex gap-2">
                                    <a href="{{ route('products.edit', $product) }}"
                                        class="rounded-full bg-rose px-3 py-1 text-sm">Edit</a>
                                    <form action="{{ route('products.destroy', $product) }}" method="POST"
                                        onsubmit="return confirm('Hapus produk ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="rounded-full bg-ink px-3 py-1 text-sm text-white">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection