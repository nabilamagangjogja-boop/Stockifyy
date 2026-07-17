@php
    $lowStock = $lowStockProducts->count();
@endphp

<div class="grid grid-cols-1 gap-6 xl:grid-cols-[1.55fr_0.85fr]">

    {{-- LEFT COLUMN --}}
    <div>
        <div class="flex flex-wrap items-start justify-between gap-4">
            <div class="flex items-center gap-4">
                <img src="{{ asset('images/logo.png') }}" alt="Logo Stockifyy" class="h-16 w-16 rounded-3xl border border-white bg-white/80 shadow-soft"
                    onerror="this.style.display='none'" />
                <div>
                    <p class="text-sm" style="color:var(--ink-soft)">Selamat datang kembali,</p>
                    <h1 class="font-display text-4xl leading-tight" style="color:var(--ink)">
                        {{ auth()->user()->name }} 👋
                    </h1>
                    <span class="mt-1 inline-block rounded-full px-3 py-1 text-xs font-medium" style="background:var(--blush); color:var(--rose-dark)">
                        Manajer Gudang
                    </span>
                </div>
            </div>
        </div>

        {{-- Stat cards: stok menipis, masuk hari ini, keluar hari ini --}}
        <div class="mt-6 grid gap-5 sm:grid-cols-3">
            <div class="rounded-3xl border border-white bg-white/80 p-6 shadow-soft">
                <p class="text-sm" style="color:var(--ink-soft)">Stok Menipis</p>
                <h2 class="mt-2 font-display text-3xl" style="color:var(--ink)">{{ $lowStock }}</h2>
                <p class="mt-1 text-xs" style="color:var(--ink-soft)">produk perlu di-restock</p>
            </div>
            <div class="rounded-3xl border border-white bg-white/80 p-6 shadow-soft">
                <p class="text-sm" style="color:var(--ink-soft)">Barang Masuk Hari Ini</p>
                <h2 class="mt-2 font-display text-3xl" style="color:var(--ink)">{{ $masukHariIni->sum('quantity') }}</h2>
                <p class="mt-1 text-xs" style="color:var(--ink-soft)">{{ $masukHariIni->count() }} transaksi</p>
            </div>
            <div class="rounded-3xl border border-white bg-white/80 p-6 shadow-soft">
                <p class="text-sm" style="color:var(--ink-soft)">Barang Keluar Hari Ini</p>
                <h2 class="mt-2 font-display text-3xl" style="color:var(--ink)">{{ $keluarHariIni->sum('quantity') }}</h2>
                <p class="mt-1 text-xs" style="color:var(--ink-soft)">{{ $keluarHariIni->count() }} transaksi</p>
            </div>
        </div>

        {{-- Produk stok menipis --}}
        <div class="mt-8">
            <div class="mb-4 flex items-center justify-between">
                <h3 class="font-display text-xl" style="color:var(--ink)">Produk Stok Menipis</h3>
                <a href="{{ route('products.index') }}" class="text-sm font-medium" style="color:var(--rose-dark)">Lihat semua produk →</a>
            </div>
            <div class="space-y-3">
                @forelse($lowStockProducts as $product)
                    <div class="flex items-center justify-between rounded-2xl border border-white bg-white/80 px-5 py-4 shadow-soft">
                        <div class="min-w-0">
                            <p class="truncate font-medium" style="color:var(--ink)">{{ $product->name }}</p>
                            <p class="text-xs" style="color:var(--ink-soft)">{{ $product->category->name ?? '-' }} · Min. stok {{ $product->minimum_stock ?? 0 }}</p>
                        </div>
                        <span class="shrink-0 rounded-full px-3 py-1 text-xs font-semibold text-white" style="background:var(--rose-dark)">
                            {{ $product->current_stock }} unit
                        </span>
                    </div>
                @empty
                    <div class="rounded-3xl border border-dashed p-8 text-center text-sm" style="border-color:var(--line); color:var(--ink-soft)">
                        Semua stok aman, belum ada yang perlu di-restock 🎉
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- RIGHT COLUMN --}}
    <div class="flex flex-col gap-6">

        <div class="rounded-3xl border border-white bg-white/80 p-6 text-center shadow-soft">
            <div class="mx-auto flex h-16 w-16 items-center justify-center overflow-hidden rounded-full font-display text-xl text-white" style="background:var(--rose-dark)">
                @if(auth()->user()->photo)
                    <img src="{{ asset('storage/' . auth()->user()->photo) }}" alt="Foto profil" class="h-full w-full object-cover">
                @else
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                @endif
            </div>
            <h3 class="mt-3 font-display text-lg" style="color:var(--ink)">{{ auth()->user()->name }}</h3>
            <span class="mt-1 inline-block rounded-full px-3 py-1 text-xs font-medium" style="background:var(--blush); color:var(--rose-dark)">
                Manajer Gudang
            </span>
        </div>

        <div class="rounded-3xl border border-white bg-white/80 p-6 shadow-soft">
            <div class="mb-4 flex items-center justify-between">
                <h3 class="font-display text-lg" style="color:var(--ink)">Transaksi Terbaru</h3>
                <a href="{{ route('transactions.index') }}" class="text-xs font-medium" style="color:var(--rose-dark)">Semua →</a>
            </div>
            <div class="space-y-3">
                @forelse($transactions as $transaction)
                    <div class="flex items-center justify-between rounded-2xl px-4 py-3" style="background:var(--peach)">
                        <div class="min-w-0">
                            <p class="truncate text-sm font-medium" style="color:var(--ink)">{{ $transaction->product->name ?? '-' }}</p>
                            <p class="text-xs" style="color:var(--ink-soft)">{{ \Carbon\Carbon::parse($transaction->date)->format('d M') }} · {{ $transaction->status }}</p>
                        </div>
                        <span class="shrink-0 rounded-full px-3 py-1 text-xs font-semibold text-white" style="background: {{ $transaction->type === 'Masuk' ? '#8FBF8A' : '#C97A9D' }}">
                            {{ $transaction->type === 'Masuk' ? '+' : '-' }}{{ $transaction->quantity }}
                        </span>
                    </div>
                @empty
                    <p class="text-sm" style="color:var(--ink-soft)">Belum ada transaksi.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>