@extends('layouts.app')

@section('content')
    <div class="rounded-3xl border border-white/70 bg-white/80 p-6 shadow-soft">
        <h2 class="text-2xl font-semibold">Laporan Stok dan Transaksi</h2>
        <p class="mt-2 text-sm text-ink/70">Ringkasan aktivitas stok dan produk yang tersedia.</p>

        <div class="mt-6 grid gap-4 md:grid-cols-2">
            <div class="rounded-2xl bg-blush/40 p-4">
                <p class="text-sm font-medium">Produk dengan stok rendah</p>
                <p class="mt-2 text-2xl font-semibold">
                    {{ \App\Models\Product::whereColumn('minimum_stock', '>', 'id')->count() }}</p>
            </div>
            <div class="rounded-2xl bg-cream p-4">
                <p class="text-sm font-medium">Total transaksi</p>
                <p class="mt-2 text-2xl font-semibold">{{ \App\Models\StockTransaction::count() }}</p>
            </div>
        </div>
    </div>
@endsection