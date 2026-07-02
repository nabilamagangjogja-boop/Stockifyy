@extends('layouts.app')

@section('content')
    <div class="rounded-3xl border border-white/70 bg-white/80 p-6 shadow-soft">
        <div class="mb-6 flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-semibold">Daftar Supplier</h2>
                <p class="text-sm text-ink/70">Kelola data supplier untuk mendukung proses pembelian dan stok.</p>
            </div>
            <a href="{{ route('suppliers.index') }}"
                class="rounded-full bg-ink px-4 py-2 text-sm font-medium text-white">Tambah Supplier</a>
        </div>

        <div class="overflow-hidden rounded-2xl border border-blush/60">
            <table class="min-w-full divide-y divide-blush/60">
                <thead class="bg-blush/40">
                    <tr>
                        <th class="px-4 py-3 text-left text-sm font-semibold">Nama</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold">Alamat</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold">Telepon</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold">Email</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-blush/40 bg-white/70">
                    @foreach($suppliers as $supplier)
                        <tr>
                            <td class="px-4 py-3">{{ $supplier->name }}</td>
                            <td class="px-4 py-3">{{ $supplier->address ?? '-' }}</td>
                            <td class="px-4 py-3">{{ $supplier->phone ?? '-' }}</td>
                            <td class="px-4 py-3">{{ $supplier->email ?? '-' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection