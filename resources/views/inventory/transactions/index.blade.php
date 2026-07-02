@extends('layouts.app')

@section('content')
    <div class="rounded-3xl border border-white/70 bg-white/80 p-6 shadow-soft">
        <div class="mb-6 flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-semibold">Catatan Stok</h2>
                <p class="text-sm text-ink/70">Lihat transaksi masuk dan keluar serta status persetujuan.</p>
            </div>
            <form action="{{ route('transactions.store') }}" method="POST" class="flex flex-wrap gap-2">
                @csrf
                <input type="hidden" name="product_id" value="1">
                <input type="hidden" name="user_id" value="1">
                <select name="type" class="rounded-full border border-blush bg-cream px-3 py-2 text-sm">
                    <option value="Masuk">Masuk</option>
                    <option value="Keluar">Keluar</option>
                </select>
                <input type="number" name="quantity" value="1"
                    class="w-20 rounded-full border border-blush bg-cream px-3 py-2 text-sm">
                <input type="date" name="date" value="{{ now()->format('Y-m-d') }}"
                    class="rounded-full border border-blush bg-cream px-3 py-2 text-sm">
                <select name="status" class="rounded-full border border-blush bg-cream px-3 py-2 text-sm">
                    <option value="Diterima">Diterima</option>
                    <option value="Dikeluarkan">Dikeluarkan</option>
                </select>
                <button class="rounded-full bg-ink px-4 py-2 text-sm font-medium text-white">Catat</button>
            </form>
        </div>

        <div class="space-y-3">
            @foreach($transactions as $transaction)
                <div class="flex items-center justify-between rounded-2xl border border-blush/50 bg-cream/70 px-4 py-3">
                    <div>
                        <p class="font-medium">{{ $transaction->product->name ?? '-' }}</p>
                        <p class="text-sm text-ink/70">{{ $transaction->type }} • {{ $transaction->quantity }} unit •
                            {{ $transaction->date->format('d M Y') }}</p>
                    </div>
                    <span class="rounded-full bg-white px-3 py-1 text-sm">{{ $transaction->status }}</span>
                </div>
            @endforeach
        </div>
    </div>
@endsection