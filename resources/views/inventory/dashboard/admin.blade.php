@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')

    @php
        $lowStockProducts = $products->filter(fn($p) => $p->current_stock <= ($p->minimum_stock ?? 0))->values();
        $lowStock = $lowStockProducts->count();
        $cardStyles = [
            ['bg' => '#F3F4F6', 'chip' => '#E5E7EB'],
            ['bg' => '#FAFAFA', 'chip' => '#F3F4F6'],
            ['bg' => '#F5F5F5', 'chip' => '#E5E5E5'],
            ['bg' => '#F3F4F6', 'chip' => '#E5E7EB'],
        ];

        // simple weekly activity shape from latest transactions, for the mini chart
        $days = ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'];
        $weekly = array_fill(0, 7, 0);
        foreach ($transactions as $t) {
            if ($t->date) {
                $idx = \Carbon\Carbon::parse($t->date)->dayOfWeekIso - 1;
                $weekly[$idx] += (int) $t->quantity;
            }
        }
        $maxWeekly = max(1, max($weekly));
    @endphp

    <div class="grid grid-cols-1 gap-6 xl:grid-cols-[1.55fr_0.85fr]">

        {{-- LEFT COLUMN --}}
        <div>
            {{-- Hero --}}
            <div class="flex flex-wrap items-start justify-between gap-4">
                <div class="flex items-center gap-4">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo Stockifyy"
                        class="h-16 w-16 rounded-3xl border border-white bg-white/80 shadow-soft"
                        onerror="this.style.display='none'" />
                    <div>
                        <p class="text-sm" style="color:var(--ink-soft)">Selamat datang kembali,</p>
                        <h1 class="font-display text-4xl leading-tight" style="color:var(--ink)">
                            {{ auth()->user()->name ?? 'Admin' }} 👋
                        </h1>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <div class="relative">
                        <button
                            onclick="document.getElementById('notif-dropdown').classList.toggle('hidden'); event.stopPropagation();"
                            class="relative flex h-11 w-11 items-center justify-center rounded-full bg-white/80 shadow-soft border border-white"
                            style="color:var(--rose-dark)">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="1.8">
                                <path d="M6 8a6 6 0 1 1 12 0c0 5 2 6 2 6H4s2-1 2-6" />
                                <path d="M10 20a2 2 0 0 0 4 0" />
                            </svg>
                            @if($lowStock > 0)
                                <span
                                    class="absolute -right-1 -top-1 flex h-5 w-5 items-center justify-center rounded-full text-[11px] font-semibold text-white"
                                    style="background:var(--rose-dark)">
                                    {{ $lowStock > 9 ? '9+' : $lowStock }}
                                </span>
                            @endif
                        </button>

                        <div id="notif-dropdown"
                            class="hidden absolute right-0 z-20 mt-2 w-80 rounded-2xl border border-white bg-white p-3 shadow-soft">
                            <div class="mb-2 flex items-center justify-between px-1">
                                <p class="text-sm font-semibold" style="color:var(--ink)">Stok Menipis</p>
                                <span class="text-xs" style="color:var(--ink-soft)">{{ $lowStock }} produk</span>
                            </div>
                            <div class="max-h-72 space-y-1.5 overflow-y-auto">
                                @forelse($lowStockProducts as $p)
                                    <a href="{{ route('products.edit', $p) }}"
                                        class="flex items-center justify-between rounded-xl px-3 py-2 text-sm hover:opacity-80"
                                        style="background:var(--blush)">
                                        <div class="min-w-0">
                                            <p class="truncate font-medium" style="color:var(--ink)">{{ $p->name }}</p>
                                            <p class="text-xs" style="color:var(--ink-soft)">Min. stok
                                                {{ $p->minimum_stock ?? 0 }}
                                            </p>
                                        </div>
                                        <span class="shrink-0 rounded-full px-2.5 py-1 text-xs font-semibold text-white"
                                            style="background:var(--rose-dark)">{{ $p->current_stock }}</span>
                                    </a>
                                @empty
                                    <p class="px-1 py-2 text-sm" style="color:var(--ink-soft)">Semua stok aman 🎉</p>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    <a href="{{ route('products.create') }}"
                        class="flex h-11 items-center gap-2 rounded-full px-5 text-sm font-medium text-white shadow-soft"
                        style="background:var(--rose-dark)">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M12 5v14M5 12h14" />
                        </svg>
                        Tambah Produk
                    </a>
                </div>
            </div>

            <script>
                document.addEventListener('click', function (e) {
                    var dropdown = document.getElementById('notif-dropdown');
                    if (dropdown && !dropdown.classList.contains('hidden') && !dropdown.contains(e.target)) {
                        dropdown.classList.add('hidden');
                    }
                });
            </script>

            {{-- Stat chips row --}}
            <div class="mt-6 flex flex-wrap gap-3">
                <span class="rounded-full px-5 py-2 text-sm font-medium text-white"
                    style="background:var(--ink)">Semua</span>
                <a href="{{ route('categories.index') }}"
                    class="rounded-full bg-white/70 px-5 py-2 text-sm border border-white transition hover:opacity-75"
                    style="color:var(--ink-soft)">{{ $categories->count() }} Kategori</a>
                <a href="{{ route('suppliers.index') }}"
                    class="rounded-full bg-white/70 px-5 py-2 text-sm border border-white transition hover:opacity-75"
                    style="color:var(--ink-soft)">{{ $suppliers->count() }} Supplier</a>
                <a href="{{ route('products.index') }}"
                    class="rounded-full px-5 py-2 text-sm border transition hover:opacity-75"
                    style="background:{{ $lowStock ? '#F5F5F5' : 'rgba(255,255,255,0.7)' }}; color:var(--rose-dark); border-color:var(--line)">
                    {{ $lowStock }} Stok Menipis
                </a>
            </div>

            {{-- Stat cards --}}
            <div class="mt-6 grid gap-5 sm:grid-cols-3">
                <a href="{{ route('products.index') }}"
                    class="rounded-3xl border border-white bg-white/80 p-6 shadow-soft transition hover:-translate-y-0.5">
                    <p class="text-sm" style="color:var(--ink-soft)">Total Produk</p>
                    <h2 class="mt-2 font-display text-3xl" style="color:var(--ink)">{{ $totalProducts }}</h2>
                </a>
                <a href="{{ route('products.index') }}"
                    class="rounded-3xl border border-white bg-white/80 p-6 shadow-soft transition hover:-translate-y-0.5">
                    <p class="text-sm" style="color:var(--ink-soft)">Total Stok Saat Ini</p>
                    <h2 class="mt-2 font-display text-3xl" style="color:var(--ink)">{{ $totalStock }}</h2>
                </a>
                <a href="{{ route('categories.index') }}"
                    class="rounded-3xl border border-white bg-white/80 p-6 shadow-soft transition hover:-translate-y-0.5">
                    <p class="text-sm" style="color:var(--ink-soft)">Kategori</p>
                    <h2 class="mt-2 font-display text-3xl" style="color:var(--ink)">{{ $categories->count() }}</h2>
                </a>
                <a href="{{ route('transactions.index') }}"
                    class="rounded-3xl border border-white bg-white/80 p-6 shadow-soft transition hover:-translate-y-0.5">
                    <p class="text-sm" style="color:var(--ink-soft)">Barang Masuk Hari Ini</p>
                    <h2 class="mt-2 font-display text-3xl" style="color:var(--ink)">{{ $masukHariIniQty }}</h2>
                </a>
                <a href="{{ route('transactions.index') }}"
                    class="rounded-3xl border border-white bg-white/80 p-6 shadow-soft transition hover:-translate-y-0.5">
                    <p class="text-sm" style="color:var(--ink-soft)">Barang Keluar Hari Ini</p>
                    <h2 class="mt-2 font-display text-3xl" style="color:var(--ink)">{{ $keluarHariIniQty }}</h2>
                </a>
            </div>

            {{-- Grafik stok per kategori --}}
            @php
                $stockByCategory = $products
                    ->groupBy(fn($p) => $p->category->name ?? 'Tanpa Kategori')
                    ->map(fn($group) => $group->sum('current_stock'))
                    ->sortDesc();
                $totalCategoryStock = max(1, $stockByCategory->sum());
                $donutPalette = ['#C9184A', '#2EC4B6', '#FF9F1C', '#5C7D63', '#7B61FF', '#264653', '#E76F51', '#4D96FF'];
                $cumulative = 0;
                $donutStops = [];
                $legend = [];
                foreach ($stockByCategory as $catName => $stock) {
                    $start = $cumulative;
                    $pct = ($stock / $totalCategoryStock) * 100;
                    $cumulative += $pct;
                    $color = $donutPalette[count($legend) % count($donutPalette)];
                    $donutStops[] = "{$color} " . round($start, 2) . "% " . round($cumulative, 2) . "%";
                    $legend[] = ['name' => $catName, 'stock' => $stock, 'pct' => round($pct, 1), 'color' => $color];
                }
                $donutGradient = implode(', ', $donutStops);
            @endphp
            <div class="mt-8 rounded-3xl border border-white bg-white/80 p-6 shadow-soft">
                <div class="mb-5 flex items-center justify-between">
                    <h3 class="font-display text-xl" style="color:var(--ink)">Grafik Stok Barang</h3>
                    <span class="text-xs" style="color:var(--ink-soft)">{{ $totalStock }} unit total</span>
                </div>
                @if($stockByCategory->isEmpty())
                    <div class="flex h-24 flex-col items-center justify-center gap-2 rounded-2xl border border-dashed"
                        style="border-color:var(--line)">
                        <p class="text-xs" style="color:var(--ink-soft)">Belum ada data stok.</p>
                    </div>
                @else
                    <div class="flex flex-col items-center gap-6 sm:flex-row sm:items-center sm:justify-center">
                        {{-- Donut --}}
                        <div class="relative h-48 w-48 shrink-0 rounded-full"
                            style="background: conic-gradient({{ $donutGradient }});">
                            <div
                                class="absolute inset-[20%] flex flex-col items-center justify-center rounded-full bg-white shadow-inner">
                                <span class="font-display text-2xl" style="color:var(--ink)">{{ $totalStock }}</span>
                                <span class="text-[11px]" style="color:var(--ink-soft)">unit total</span>
                            </div>
                        </div>

                        {{-- Legend --}}
                        <div class="w-full space-y-2.5 sm:max-w-[220px]">
                            @foreach($legend as $item)
                                <div class="flex items-center justify-between gap-3 text-sm">
                                    <div class="flex min-w-0 items-center gap-2.5">
                                        <span class="h-2.5 w-2.5 shrink-0 rounded-full"
                                            style="background:{{ $item['color'] }}"></span>
                                        <span class="truncate" style="color:var(--ink)">{{ $item['name'] }}</span>
                                    </div>
                                    <span class="shrink-0 text-xs font-medium" style="color:var(--ink-soft)">
                                        {{ $item['stock'] }} unit · {{ $item['pct'] }}%
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            {{-- Product cards --}}
            <div class="mt-8">
                <div class="mb-4 flex items-center justify-between">
                    <h3 class="font-display text-xl" style="color:var(--ink)">Produk Terbaru</h3>
                    <a href="{{ route('products.index') }}" class="text-sm font-medium" style="color:var(--rose-dark)">Lihat
                        semua →</a>
                </div>
                <div class="grid gap-5 sm:grid-cols-2">
                    @forelse($products->take(4) as $i => $product)
                        @php $style = $cardStyles[$i % count($cardStyles)]; @endphp
                        <a href="{{ route('products.edit', $product) }}"
                            class="rounded-3xl p-5 shadow-soft transition hover:-translate-y-0.5"
                            style="background:{{ $style['bg'] }}">
                            <div class="flex items-center justify-between">
                                <span class="rounded-full bg-white/70 px-3 py-1 text-xs font-medium"
                                    style="color:var(--ink-soft)">
                                    {{ $product->category->name ?? 'Tanpa kategori' }}
                                </span>
                                <span class="rounded-full px-3 py-1 text-xs font-semibold text-white"
                                    style="background:{{ $product->current_stock <= ($product->minimum_stock ?? 0) ? '#111111' : 'rgba(17,17,17,0.55)' }}">
                                    {{ $product->current_stock }} unit
                                </span>
                            </div>
                            <h4 class="mt-4 font-display text-lg leading-snug" style="color:var(--ink)">{{ $product->name }}
                            </h4>
                            <p class="mt-1 text-sm" style="color:var(--ink-soft)">SKU {{ $product->sku }} ·
                                {{ $product->supplier->name ?? '-' }}
                            </p>
                        </a>
                    @empty
                        <div class="col-span-2 rounded-3xl border border-dashed p-8 text-center text-sm"
                            style="border-color:var(--line); color:var(--ink-soft)">
                            Belum ada produk. Tambahkan produk pertamamu.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- RIGHT COLUMN --}}
        <div class="flex flex-col gap-6">

            {{-- Profile card --}}
            <div class="rounded-3xl border border-white bg-white/80 p-6 text-center shadow-soft">
                <div class="mx-auto flex h-16 w-16 items-center justify-center overflow-hidden rounded-full font-display text-xl text-white"
                    style="background:var(--rose-dark)">
                    @if(auth()->user()->photo)
                        <img src="{{ asset('storage/' . auth()->user()->photo) }}" alt="Foto profil"
                            class="h-full w-full object-cover">
                    @else
                        {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
                    @endif
                </div>
                <h3 class="mt-3 font-display text-lg" style="color:var(--ink)">{{ auth()->user()->name ?? 'Admin' }}
                </h3>
                <span class="mt-1 inline-block rounded-full px-3 py-1 text-xs font-medium"
                    style="background:var(--blush); color:var(--rose-dark)">
                    {{ auth()->user()->role ?? 'Staff Gudang' }}
                </span>

                <div class="mt-5 grid grid-cols-2 divide-x" style="border-color:var(--line)">
                    <div class="px-2">
                        <p class="font-display text-lg" style="color:var(--ink)">{{ $totalProducts }}</p>
                        <p class="text-xs" style="color:var(--ink-soft)">Produk dikelola</p>
                    </div>
                    <div class="px-2">
                        <p class="font-display text-lg" style="color:var(--ink)">{{ $transactions->count() }}</p>
                        <p class="text-xs" style="color:var(--ink-soft)">Transaksi terbaru</p>
                    </div>
                </div>
            </div>

            {{-- Weekly activity chart --}}
            <div class="rounded-3xl border border-white bg-white/80 p-6 shadow-soft">
                <div class="mb-4 flex items-center justify-between">
                    <p class="text-sm font-medium" style="color:var(--ink)">Aktivitas Stok</p>
                    <span class="text-xs" style="color:var(--ink-soft)">7 hari terakhir</span>
                </div>
                @if(array_sum($weekly) === 0)
                    <div class="flex h-32 flex-col items-center justify-center gap-2 rounded-2xl border border-dashed"
                        style="border-color:var(--line)">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"
                            style="color:var(--ink-soft)">
                            <path d="M4 19V9M12 19V4M20 19v-6" />
                        </svg>
                        <p class="text-xs" style="color:var(--ink-soft)">Belum ada aktivitas minggu ini</p>
                    </div>
                @else
                    <div class="flex h-32 items-end justify-between gap-3">
                        @foreach($weekly as $i => $val)
                            <div class="flex flex-1 flex-col items-center gap-2">
                                <div class="w-full max-w-[18px] rounded-full transition-all"
                                    style="height: {{ max(10, ($val / $maxWeekly) * 100) }}px; background: {{ $i === now()->dayOfWeekIso - 1 ? 'var(--rose-dark)' : 'var(--blush-deep)' }}">
                                </div>
                                <span class="text-[11px]" style="color:var(--ink-soft)">{{ $days[$i] }}</span>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- Recent transactions --}}
            <div class="rounded-3xl border border-white bg-white/80 p-6 shadow-soft">
                <div class="mb-4 flex items-center justify-between">
                    <h3 class="font-display text-lg" style="color:var(--ink)">Transaksi Terakhir</h3>
                    <a href="{{ route('transactions.index') }}" class="text-xs font-medium"
                        style="color:var(--rose-dark)">Semua →</a>
                </div>
                <div class="space-y-3">
                    @forelse($transactions as $transaction)
                        <div class="flex items-center justify-between rounded-2xl px-4 py-3" style="background:var(--peach)">
                            <div>
                                <p class="text-sm font-medium" style="color:var(--ink)">
                                    {{ $transaction->product->name ?? '-' }}
                                </p>
                                <p class="text-xs" style="color:var(--ink-soft)">
                                    {{ \Carbon\Carbon::parse($transaction->date)->format('d M') }} ·
                                    {{ $transaction->status }}
                                </p>
                            </div>
                            <span class="rounded-full px-3 py-1 text-xs font-semibold text-white"
                                style="background: {{ $transaction->type === 'Masuk' ? '#16A34A' : '#111111' }}">
                                {{ $transaction->type === 'Masuk' ? '+' : '-' }}{{ $transaction->quantity }}
                            </span>
                        </div>
                    @empty
                        <p class="text-sm" style="color:var(--ink-soft)">Belum ada transaksi.</p>
                    @endforelse
                </div>
            </div>

            {{-- Recent user activity --}}
            <div class="rounded-3xl border border-white bg-white/80 p-6 shadow-soft">
                <div class="mb-4 flex items-center justify-between">
                    <h3 class="font-display text-lg" style="color:var(--ink)">Aktivitas Pengguna Terbaru</h3>
                    <a href="{{ route('reports.activity') }}" class="text-xs font-medium"
                        style="color:var(--rose-dark)">Semua →</a>
                </div>
                <div class="space-y-3">
                    @forelse($recentUserActivity as $log)
                        <div class="rounded-2xl px-4 py-3" style="background:var(--peach)">
                            <p class="text-sm font-medium" style="color:var(--ink)">
                                {{ $log->user_name ?? 'Sistem' }}
                                <span class="font-normal" style="color:var(--ink-soft)">· {{ $log->module }}</span>
                            </p>
                            <p class="mt-0.5 text-xs" style="color:var(--ink-soft)">{{ $log->description }}</p>
                            <p class="mt-1 text-[11px]" style="color:var(--ink-soft)">
                                {{ $log->created_at->diffForHumans() }}
                            </p>
                        </div>
                    @empty
                        <p class="text-sm" style="color:var(--ink-soft)">Belum ada aktivitas pengguna.</p>
                    @endforelse
                </div>
            </div>
        </div>

@endsection