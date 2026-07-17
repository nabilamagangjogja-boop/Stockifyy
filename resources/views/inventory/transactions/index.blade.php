@extends('layouts.app')

@section('content')
    <div class="rounded-3xl border border-white/70 bg-white/80 p-6 shadow-soft">
        <div class="mb-6 flex items-center justify-between">
            <div>
                <h2 class="font-display text-2xl" style="color:var(--ink)">Catatan Stok</h2>
                <p class="text-sm" style="color:var(--ink-soft)">Lihat transaksi masuk dan keluar serta status persetujuan.
                </p>
            </div>
        </div>

        @if(in_array(auth()->user()->role, ['Admin', 'Manajer Gudang']))
            <form action="{{ route('transactions.store') }}" method="POST"
                class="mb-6 flex flex-wrap items-end gap-2 rounded-2xl border p-4"
                style="border-color:var(--line); background:var(--cream)">
                @csrf
                <input type="hidden" name="status" value="Pending">
                <div>
                    <label class="mb-1 block text-xs font-medium" style="color:var(--ink-soft)">Produk</label>
                    <select name="product_id" class="rounded-full border px-3 py-2 text-sm"
                        style="border-color:var(--line); background:white; color:var(--ink)" required>
                        <option value="">Pilih produk</option>
                        @foreach($products as $product)
                            <option value="{{ $product->id }}">{{ $product->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="mb-1 block text-xs font-medium" style="color:var(--ink-soft)">Tipe</label>
                    <select name="type" class="rounded-full border px-3 py-2 text-sm"
                        style="border-color:var(--line); background:white; color:var(--ink)" required>
                        <option value="Masuk">Masuk</option>
                        <option value="Keluar">Keluar</option>
                    </select>
                </div>
                <div>
                    <label class="mb-1 block text-xs font-medium" style="color:var(--ink-soft)">Jumlah</label>
                    <input type="number" name="quantity" value="1" min="1" class="w-24 rounded-full border px-3 py-2 text-sm"
                        style="border-color:var(--line); background:white; color:var(--ink)" required>
                </div>
                <div>
                    <label class="mb-1 block text-xs font-medium" style="color:var(--ink-soft)">Tanggal</label>
                    <input type="date" name="date" value="{{ now()->format('Y-m-d') }}"
                        class="rounded-full border px-3 py-2 text-sm"
                        style="border-color:var(--line); background:white; color:var(--ink)" required>
                </div>
                <div>
                    <label class="mb-1 block text-xs font-medium" style="color:var(--ink-soft)">Catatan</label>
                    <input type="text" name="notes" placeholder="Opsional" class="rounded-full border px-3 py-2 text-sm"
                        style="border-color:var(--line); background:white; color:var(--ink)">
                </div>
                <button class="rounded-full px-4 py-2 text-sm font-medium text-white"
                    style="background:var(--rose-dark)">Catat</button>
            </form>
        @endif

        <div class="space-y-3">
            @forelse($transactions as $transaction)
                <div class="flex flex-wrap items-center justify-between gap-3 rounded-2xl border px-4 py-3"
                    style="border-color:var(--line); background:var(--cream)">
                    <div>
                        <p class="font-medium" style="color:var(--ink)">{{ $transaction->product->name ?? '-' }}</p>
                        <p class="text-sm" style="color:var(--ink-soft)">{{ $transaction->type }} • {{ $transaction->quantity }}
                            unit •
                            {{ $transaction->date->format('d M Y') }}
                            @if($transaction->notes) • {{ $transaction->notes }} @endif
                        </p>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="rounded-full px-3 py-1 text-sm text-white" style="background:
                                                        {{ $transaction->status === 'Pending' ? '#C99A3E' : '' }}
                                                        {{ $transaction->status === 'Diterima' ? '#5C7D63' : '' }}
                                                        {{ $transaction->status === 'Dikeluarkan' ? '#5C7D63' : '' }}
                                                        {{ $transaction->status === 'Ditolak' ? '#8F355C' : '' }}">
                            {{ $transaction->status }}
                        </span>

                        @if($transaction->status === 'Pending' && in_array(auth()->user()->role, ['Admin', 'Staff Gudang']))
                            <form action="{{ route('transactions.confirm', $transaction) }}" method="POST">
                                @csrf
                                <button class="rounded-full px-3 py-1 text-sm text-white"
                                    style="background:#5C7D63">Konfirmasi</button>
                            </form>
                            <form action="{{ route('transactions.reject', $transaction) }}" method="POST">
                                @csrf
                                <button class="rounded-full px-3 py-1 text-sm text-white" style="background:#8F355C">Tolak</button>
                            </form>
                        @endif
                    </div>
                </div>
            @empty
                <p class="text-sm" style="color:var(--ink-soft)">Belum ada transaksi.</p>
            @endforelse
        </div>

        @if(method_exists($transactions, 'links'))
            <div class="mt-4">{{ $transactions->links() }}</div>
        @endif
    </div>
@endsection