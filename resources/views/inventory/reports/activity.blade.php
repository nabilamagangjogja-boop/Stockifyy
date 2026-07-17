@extends('layouts.app')

@section('title', 'Laporan Aktivitas Pengguna')

@section('content')

    @php
        $actionLabels = [
            'login' => 'Login',
            'logout' => 'Logout',
            'register' => 'Daftar',
            'create' => 'Tambah',
            'update' => 'Perbarui',
            'delete' => 'Hapus',
            'confirm' => 'Konfirmasi',
            'reject' => 'Tolak',
        ];
        $actionColors = [
            'login' => '#5C7D63',
            'logout' => '#6B7280',
            'register' => '#5C7D63',
            'create' => '#5C7D63',
            'update' => '#C99A3E',
            'delete' => '#111111',
            'confirm' => '#5C7D63',
            'reject' => '#111111',
        ];
        $moduleOptions = ['Auth', 'Produk', 'Kategori', 'Supplier', 'Pengguna', 'Transaksi', 'Stock Opname'];
    @endphp

    <div class="rounded-3xl border border-white/70 bg-white/80 p-6 shadow-soft md:p-8">
        <div class="flex flex-wrap items-start justify-between gap-4">
            <div>
                <h2 class="font-display text-2xl" style="color:var(--ink)">Laporan Aktivitas Pengguna</h2>
                <p class="mt-1 text-sm" style="color:var(--ink-soft)">Riwayat login, dan perubahan data yang dilakukan
                    setiap pengguna di Stockify.</p>
            </div>
            <a href="{{ route('reports.index') }}" class="text-sm font-medium" style="color:var(--rose-dark)">
                ← Kembali ke Laporan Stok
            </a>
        </div>

        {{-- Form filter --}}
        <form method="GET" action="{{ route('reports.activity') }}"
            class="mt-6 mb-5 flex flex-wrap items-end gap-3 rounded-2xl border p-4"
            style="border-color:var(--line); background:var(--cream)">
            <div>
                <label class="mb-1 block text-xs font-medium" style="color:var(--ink-soft)">Pengguna</label>
                <select name="user_id" class="rounded-full border px-3 py-2 text-sm"
                    style="border-color:var(--line); background:white; color:var(--ink)">
                    <option value="">Semua Pengguna</option>
                    @foreach($users as $u)
                        <option value="{{ $u->id }}" @selected(request('user_id') == $u->id)>{{ $u->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="mb-1 block text-xs font-medium" style="color:var(--ink-soft)">Modul</label>
                <select name="module" class="rounded-full border px-3 py-2 text-sm"
                    style="border-color:var(--line); background:white; color:var(--ink)">
                    <option value="">Semua Modul</option>
                    @foreach($moduleOptions as $m)
                        <option value="{{ $m }}" @selected(request('module') === $m)>{{ $m }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="mb-1 block text-xs font-medium" style="color:var(--ink-soft)">Aksi</label>
                <select name="action" class="rounded-full border px-3 py-2 text-sm"
                    style="border-color:var(--line); background:white; color:var(--ink)">
                    <option value="">Semua Aksi</option>
                    @foreach($actionLabels as $key => $label)
                        <option value="{{ $key }}" @selected(request('action') === $key)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
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
            <button class="rounded-full px-4 py-2 text-sm font-medium text-white"
                style="background:var(--rose-dark)">Terapkan</button>
            @if(request()->anyFilled(['user_id', 'module', 'action', 'date_from', 'date_to']))
                <a href="{{ route('reports.activity') }}" class="rounded-full px-4 py-2 text-sm"
                    style="background:var(--blush); color:var(--ink)">Reset</a>
            @endif
        </form>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left" style="color:var(--ink-soft)">
                        <th class="pb-3 font-medium">Waktu</th>
                        <th class="pb-3 font-medium">Pengguna</th>
                        <th class="pb-3 font-medium">Modul</th>
                        <th class="pb-3 font-medium">Aksi</th>
                        <th class="pb-3 font-medium">Deskripsi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logs as $log)
                        <tr class="border-t" style="border-color:var(--line)">
                            <td class="whitespace-nowrap py-3" style="color:var(--ink-soft)">
                                {{ $log->created_at->format('d M Y, H:i') }}
                            </td>
                            <td class="py-3" style="color:var(--ink)">
                                {{ $log->user_name ?? 'Pengguna terhapus' }}
                                @if($log->user_role)
                                    <span class="block text-xs" style="color:var(--ink-soft)">{{ $log->user_role }}</span>
                                @endif
                            </td>
                            <td class="py-3" style="color:var(--ink-soft)">{{ $log->module }}</td>
                            <td class="py-3">
                                <span class="rounded-full px-3 py-1 text-xs font-semibold text-white"
                                    style="background: {{ $actionColors[$log->action] ?? '#6B7280' }}">
                                    {{ $actionLabels[$log->action] ?? $log->action }}
                                </span>
                            </td>
                            <td class="py-3" style="color:var(--ink-soft)">{{ $log->description }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-6 text-center text-sm" style="color:var(--ink-soft)">
                                Belum ada aktivitas yang cocok dengan filter ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-5">
            {{ $logs->links() }}
        </div>
    </div>

@endsection