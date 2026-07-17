@extends('layouts.app')

@section('title', 'Laporan')

@section('content')

    @php
        $totalProduk = \App\Models\Product::count();
        $totalTransaksi = \App\Models\StockTransaction::count();
        $totalSupplier = \App\Models\Supplier::count();
        $totalKategori = \App\Models\Category::count();

        $allProducts = \App\Models\Product::with(['category', 'supplier'])->get();
        $lowStockProducts = $allProducts
            ->filter(fn($p) => $p->current_stock <= ($p->minimum_stock ?? 0))
            ->sortBy('current_stock')
            ->values();
        $lowStockCount = $lowStockProducts->count();

        // Tren transaksi 6 bulan terakhir (Masuk vs Keluar)
        $monthLabels = [];
        $masukSeries = [];
        $keluarSeries = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $monthLabels[] = $month->translatedFormat('M');

            $masuk = \App\Models\StockTransaction::where('type', 'Masuk')
                ->whereYear('date', $month->year)
                ->whereMonth('date', $month->month)
                ->sum('quantity');
            $keluar = \App\Models\StockTransaction::where('type', 'Keluar')
                ->whereYear('date', $month->year)
                ->whereMonth('date', $month->month)
                ->sum('quantity');

            $masukSeries[] = (int) $masuk;
            $keluarSeries[] = (int) $keluar;
        }
        $totalPergerakan = array_sum($masukSeries) + array_sum($keluarSeries);

        // Distribusi produk per kategori
        $kategoriDist = \App\Models\Category::withCount('products')->orderByDesc('products_count')->get();
        $maxKategoriCount = max(1, $kategoriDist->max('products_count') ?? 1);
        $paletteBar = ['var(--rose-dark)', 'var(--rose)', 'var(--blush-deep)', 'var(--lilac)'];
    @endphp

    <div class="rounded-3xl border border-white/70 bg-white/80 p-6 shadow-soft md:p-8">
        <div class="flex flex-wrap items-start justify-between gap-4">
            <div>
                <h2 class="font-display text-2xl" style="color:var(--ink)">Laporan Stok dan Transaksi</h2>
                <p class="mt-1 text-sm" style="color:var(--ink-soft)">Ringkasan aktivitas stok dan produk yang
                    tersedia.</p>
            </div>
            @if(auth()->user()->role === 'Admin')
                <a href="{{ route('reports.activity') }}"
                    class="flex items-center gap-2 rounded-full px-4 py-2 text-sm font-medium"
                    style="background:var(--blush); color:var(--rose-dark)">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M12 8v4l3 3" />
                        <circle cx="12" cy="12" r="9" />
                    </svg>
                    Laporan Aktivitas Pengguna
                </a>
            @endif
            <div class="relative">
                <svg class="pointer-events-none absolute left-4 top-1/2 -translate-y-1/2" width="16" height="16"
                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" style="color:var(--ink-soft)">
                    <circle cx="11" cy="11" r="7" />
                    <path d="m20 20-3-3" />
                </svg>
                <input type="text" placeholder="Cari produk..."
                    class="h-11 w-64 rounded-full border border-white bg-white/70 pl-11 pr-4 text-sm outline-none"
                    style="color:var(--ink)">
            </div>
        </div>

        {{-- Stat cards --}}
        <div class="mt-7 grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
            <div class="rounded-2xl border border-white bg-white/70 p-5">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl" style="background:var(--blush)">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                        style="color:var(--rose-dark)">
                        <path d="M21 8 12 3 3 8v8l9 5 9-5V8Z" />
                        <path d="M3 8l9 5 9-5" />
                        <path d="M12 13v8" />
                    </svg>
                </div>
                <p class="mt-4 text-sm" style="color:var(--ink-soft)">Total Produk</p>
                <p class="mt-1 font-display text-2xl" style="color:var(--ink)">{{ $totalProduk }}</p>
            </div>

            <div class="rounded-2xl border border-white bg-white/70 p-5">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl" style="background:var(--peach)">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                        style="color:var(--rose-dark)">
                        <path d="M7 7h13l-2 5H8" />
                        <path d="M17 17H4l2-5" />
                        <circle cx="8.5" cy="20" r="1.4" />
                        <circle cx="17.5" cy="20" r="1.4" />
                    </svg>
                </div>
                <p class="mt-4 text-sm" style="color:var(--ink-soft)">Total Transaksi</p>
                <p class="mt-1 font-display text-2xl" style="color:var(--ink)">{{ $totalTransaksi }}</p>
            </div>

            <div class="rounded-2xl border border-white bg-white/70 p-5">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl" style="background:var(--blush-deep)">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                        style="color:#fff">
                        <path d="M4 19V9M12 19V4M20 19v-6" />
                    </svg>
                </div>
                <p class="mt-4 text-sm" style="color:var(--ink-soft)">Produk Stok Rendah</p>
                <p class="mt-1 font-display text-2xl" style="color:var(--ink)">{{ $lowStockCount }}</p>
            </div>

            <div class="rounded-2xl border border-white bg-white/70 p-5">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl" style="background:var(--lilac)">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                        style="color:#fff">
                        <rect x="3" y="3" width="8" height="8" rx="2" />
                        <rect x="13" y="3" width="8" height="8" rx="2" />
                        <rect x="3" y="13" width="8" height="8" rx="2" />
                        <rect x="13" y="13" width="8" height="8" rx="2" />
                    </svg>
                </div>
                <p class="mt-4 text-sm" style="color:var(--ink-soft)">Kategori Produk</p>
                <p class="mt-1 font-display text-2xl" style="color:var(--ink)">{{ $totalKategori }}</p>
            </div>
        </div>

        {{-- Chart + side panel --}}
        <div class="mt-6 grid gap-6 xl:grid-cols-[1.65fr_1fr]">

            {{-- Chart card --}}
            <div class="rounded-2xl border border-white bg-white/70 p-6">
                <div class="flex flex-wrap items-center justify-between gap-3">
                    <div>
                        <p class="text-sm" style="color:var(--ink-soft)">Total pergerakan stok</p>
                        <p class="mt-1 font-display text-3xl" style="color:var(--ink)">{{ $totalPergerakan }} unit</p>
                    </div>
                    <span class="rounded-full border border-white bg-white/80 px-4 py-1.5 text-xs font-medium"
                        style="color:var(--ink-soft)">6 Bulan Terakhir</span>
                </div>
                <div class="mt-5 h-72">
                    <canvas id="trendChart"></canvas>
                </div>
                <div class="mt-4 flex items-center gap-5 text-xs" style="color:var(--ink-soft)">
                    <span class="flex items-center gap-1.5">
                        <span class="h-2 w-2 rounded-full" style="background:var(--rose-dark)"></span> Stok Masuk
                    </span>
                    <span class="flex items-center gap-1.5">
                        <span class="h-2 w-2 rounded-full" style="background:var(--blush-deep)"></span> Stok Keluar
                    </span>
                </div>
            </div>

            {{-- Category distribution --}}
            <div class="rounded-2xl border border-white bg-white/70 p-6">
                <p class="font-display text-lg" style="color:var(--ink)">Distribusi Produk per Kategori</p>
                <div class="mt-5 space-y-4">
                    @forelse($kategoriDist as $i => $kat)
                        @php
                            $pct = $totalProduk > 0 ? round(($kat->products_count / $totalProduk) * 100, 1) : 0;
                            $barColor = $paletteBar[$i % count($paletteBar)];
                        @endphp
                        <div>
                            <div class="flex items-center justify-between text-sm">
                                <span style="color:var(--ink)">{{ $kat->name }}</span>
                                <span style="color:var(--ink-soft)">{{ $kat->products_count }} · {{ $pct }}%</span>
                            </div>
                            <div class="mt-1.5 h-1.5 w-full rounded-full" style="background:var(--line)">
                                <div class="h-1.5 rounded-full"
                                    style="width:{{ max(4, ($kat->products_count / $maxKategoriCount) * 100) }}%; background:{{ $barColor }}">
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="text-sm" style="color:var(--ink-soft)">Belum ada kategori.</p>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Low stock table --}}
        <div class="mt-6 rounded-2xl border border-white bg-white/70 p-6">
            <div class="mb-4 flex items-center justify-between">
                <p class="font-display text-lg" style="color:var(--ink)">Produk dengan Stok Terendah</p>
                <a href="{{ route('products.index') }}" class="text-sm font-medium" style="color:var(--rose-dark)">Lihat
                    semua produk →</a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="text-left" style="color:var(--ink-soft)">
                            <th class="pb-3 font-medium">Produk</th>
                            <th class="pb-3 font-medium">Kategori</th>
                            <th class="pb-3 font-medium">Stok Saat Ini</th>
                            <th class="pb-3 font-medium">Stok Minimum</th>
                            <th class="pb-3 font-medium">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($lowStockProducts->take(6) as $p)
                            <tr class="border-t" style="border-color:var(--line)">
                                <td class="py-3">
                                    <div class="flex items-center gap-3">
                                        <div class="flex h-9 w-9 items-center justify-center rounded-xl"
                                            style="background:var(--blush)">
                                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="1.8" style="color:var(--rose-dark)">
                                                <path d="M21 8 12 3 3 8v8l9 5 9-5V8Z" />
                                                <path d="M3 8l9 5 9-5" />
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="font-medium" style="color:var(--ink)">{{ $p->name }}</p>
                                            <p class="text-xs" style="color:var(--ink-soft)">SKU {{ $p->sku }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-3" style="color:var(--ink-soft)">{{ $p->category->name ?? '-' }}</td>
                                <td class="py-3 font-medium" style="color:var(--ink)">{{ $p->current_stock }}</td>
                                <td class="py-3" style="color:var(--ink-soft)">{{ $p->minimum_stock ?? 0 }}</td>
                                <td class="py-3">
                                    <span class="rounded-full px-3 py-1 text-xs font-semibold text-white"
                                        style="background:var(--rose-dark)">
                                        Stok Rendah
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-6 text-center text-sm" style="color:var(--ink-soft)">
                                    Semua stok produk dalam kondisi aman 🎉
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        {{-- Laporan Transaksi dengan Filter + Export --}}
        <div class="mt-6 rounded-2xl border border-white bg-white/70 p-6">
            <div class="mb-4 flex flex-wrap items-center justify-between gap-3">
                <p class="font-display text-lg" style="color:var(--ink)">Laporan Transaksi</p>
                <div class="flex flex-wrap gap-2">
                    <a href="{{ route('reports.export.csv', request()->query()) }}"
                        class="flex items-center gap-2 rounded-full px-4 py-2 text-sm font-medium text-white"
                        style="background:var(--rose-dark)">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M12 3v12m0 0-4-4m4 4 4-4" />
                            <path d="M4 17v3a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-3" />
                        </svg>
                        Export ke Excel
                    </a>
                    <a href="{{ route('reports.export.print', request()->query()) }}" target="_blank"
                        class="flex items-center gap-2 rounded-full px-4 py-2 text-sm font-medium"
                        style="background:var(--blush); color:var(--rose-dark)">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M6 9V2h12v7" />
                            <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2" />
                            <path d="M6 14h12v8H6z" />
                        </svg>
                        Cetak / Simpan PDF
                    </a>
                </div>
            </div>

            {{-- Form filter --}}
            <form method="GET" action="{{ route('reports.index') }}"
                class="mb-5 flex flex-wrap items-end gap-3 rounded-2xl border p-4"
                style="border-color:var(--line); background:var(--cream)">
                <div>
                    <label class="mb-1 block text-xs font-medium" style="color:var(--ink-soft)">Dari Tanggal</label>
                    <input type="date" name="date_from" value="{{ request('date_from') }}"
                        class="rounded-full border px-3 py-2 text-sm"
                        style="border-color:var(--line); background:white; color:var(--ink)">
                </div>
                <div>
                    <label class="mb-1 block text-xs font-medium" style="color:var(--ink-soft)">Sampai Tanggal</label>
                    <input type="date" name="date_to" value="{{ request('date_to') }}"
                        class="rounded-full border px-3 py-2 text-sm"
                        style="border-color:var(--line); background:white; color:var(--ink)">
                </div>
                <div>
                    <label class="mb-1 block text-xs font-medium" style="color:var(--ink-soft)">Kategori</label>
                    <select name="category_id" class="rounded-full border px-3 py-2 text-sm"
                        style="border-color:var(--line); background:white; color:var(--ink)">
                        <option value="">Semua Kategori</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" @selected(request('category_id') == $category->id)>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="mb-1 block text-xs font-medium" style="color:var(--ink-soft)">Tipe</label>
                    <select name="type" class="rounded-full border px-3 py-2 text-sm"
                        style="border-color:var(--line); background:white; color:var(--ink)">
                        <option value="">Semua Tipe</option>
                        <option value="Masuk" @selected(request('type') === 'Masuk')>Masuk</option>
                        <option value="Keluar" @selected(request('type') === 'Keluar')>Keluar</option>
                    </select>
                </div>
                <button class="rounded-full px-4 py-2 text-sm font-medium text-white"
                    style="background:var(--rose-dark)">Terapkan</button>
                @if(request()->anyFilled(['date_from', 'date_to', 'category_id', 'type']))
                    <a href="{{ route('reports.index') }}" class="rounded-full px-4 py-2 text-sm"
                        style="background:var(--blush); color:var(--ink)">Reset</a>
                @endif
            </form>

            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="text-left" style="color:var(--ink-soft)">
                            <th class="pb-3 font-medium">Tanggal</th>
                            <th class="pb-3 font-medium">Produk</th>
                            <th class="pb-3 font-medium">Kategori</th>
                            <th class="pb-3 font-medium">Tipe</th>
                            <th class="pb-3 font-medium">Jumlah</th>
                            <th class="pb-3 font-medium">Status</th>
                            <th class="pb-3 font-medium">Dicatat Oleh</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($transactions as $t)
                            <tr class="border-t" style="border-color:var(--line)">
                                <td class="py-3" style="color:var(--ink-soft)">{{ $t->date->format('d M Y') }}</td>
                                <td class="py-3" style="color:var(--ink)">{{ $t->product->name ?? '-' }}</td>
                                <td class="py-3" style="color:var(--ink-soft)">{{ $t->product->category->name ?? '-' }}</td>
                                <td class="py-3" style="color:var(--ink-soft)">{{ $t->type }}</td>
                                <td class="py-3 font-medium" style="color:var(--ink)">{{ $t->quantity }}</td>
                                <td class="py-3">
                                    <span class="rounded-full px-3 py-1 text-xs font-semibold text-white" style="background:
                                                                {{ $t->status === 'Pending' ? '#C99A3E' : '' }}
                                                                {{ in_array($t->status, ['Diterima', 'Dikeluarkan']) ? '#5C7D63' : '' }}
                                                                {{ $t->status === 'Ditolak' ? '#111111' : '' }}">
                                        {{ $t->status }}
                                    </span>
                                </td>
                                <td class="py-3" style="color:var(--ink-soft)">{{ $t->user->name ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-6 text-center text-sm" style="color:var(--ink-soft)">
                                    Tidak ada transaksi yang cocok dengan filter ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js@4"></script>
    <script>
        const ctx = document.getElementById('trendChart');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: @json($monthLabels),
                datasets: [
                    {
                        label: 'Stok Masuk',
                        data: @json($masukSeries),
                        borderColor: '#6F71B8',
                        backgroundColor: 'rgba(111, 113, 184, 0.12)',
                        tension: 0.4,
                        fill: true,
                        pointRadius: 3,
                        pointBackgroundColor: '#6F71B8',
                    },
                    {
                        label: 'Stok Keluar',
                        data: @json($keluarSeries),
                        borderColor: '#F9B2D7',
                        backgroundColor: 'rgba(249, 178, 215, 0.12)',
                        tension: 0.4,
                        fill: true,
                        pointRadius: 3,
                        pointBackgroundColor: '#F9B2D7',
                    },
                ],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    x: { grid: { display: false }, ticks: { color: '#8D7FA0' } },
                    y: { grid: { color: 'rgba(197, 179, 211, 0.25)' }, ticks: { color: '#8D7FA0' } },
                },
            },
        });
    </script>
@endsection