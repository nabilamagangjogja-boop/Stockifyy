@extends('layouts.app')

@section('content')
    <div class="grid gap-6 lg:grid-cols-3">
        <div class="rounded-3xl border border-white/70 bg-white/80 p-6 shadow-soft">
            <p class="text-sm text-ink/70">Total Produk</p>
            <h2 class="mt-2 text-3xl font-semibold">{{ $totalProducts }}</h2>
        </div>
        <div class="rounded-3xl border border-white/70 bg-white/80 p-6 shadow-soft">
            <p class="text-sm text-ink/70">Total Stok Saat Ini</p>
            <h2 class="mt-2 text-3xl font-semibold">{{ $totalStock }}</h2>
        </div>
        <div class="rounded-3xl border border-white/70 bg-white/80 p-6 shadow-soft">
            <p class="text-sm text-ink/70">Kategori</p>
            <h2 class="mt-2 text-3xl font-semibold">{{ $categories->count() }}</h2>
        </div>
    </div>

    <div class="mt-8 grid gap-6 lg:grid-cols-[1.4fr_0.8fr]">
        <div class="rounded-3xl border border-white/70 bg-white/80 p-6 shadow-soft">
            <div class="mb-4 flex items-center justify-between">
                <h3 class="text-lg font-semibold">Produk Terbaru</h3>
                <a href="{{ route('products.index') }}" class="text-sm text-mauve">Lihat semua</a>
            </div>
            <div class="space-y-3">
                @foreach($products->take(5) as $product)
                    <div class="flex items-center justify-between rounded-2xl bg-blush/40 px-4 py-3">
                        <div>
                            <p class="font-medium">{{ $product->name }}</p>
                            <p class="text-sm text-ink/70">{{ $product->category->name ?? '-' }}</p>
                        </div>
                        <span class="rounded-full bg-white px-3 py-1 text-sm">{{ $product->current_stock }}</span>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="rounded-3xl border border-white/70 bg-white/80 p-6 shadow-soft">
            <h3 class="mb-4 text-lg font-semibold">Transaksi Terakhir</h3>
            <div class="space-y-3">
                @foreach($transactions as $transaction)
                    <div class="rounded-2xl bg-cream px-4 py-3 text-sm">
                        <p class="font-medium">{{ $transaction->product->name ?? '-' }}</p>
                        <p class="text-ink/70">{{ $transaction->type }} • {{ $transaction->quantity }} unit</p>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection