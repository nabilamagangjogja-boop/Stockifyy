@extends('layouts.app')

@section('content')
    <div class="rounded-3xl border border-white/70 bg-white/80 p-6 shadow-soft">
        <div class="mb-6 flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-semibold">Kelola Kategori</h2>
                <p class="text-sm text-ink/70">Atur kategori produk dengan tampilan yang rapi dan nyaman.</p>
            </div>
            <a href="{{ route('categories.create') }}"
                class="rounded-full bg-ink px-4 py-2 text-sm font-medium text-white">Tambah Kategori</a>
        </div>

        <div class="overflow-hidden rounded-2xl border border-blush/60">
            <table class="min-w-full divide-y divide-blush/60">
                <thead class="bg-blush/40">
                    <tr>
                        <th class="px-4 py-3 text-left text-sm font-semibold">Nama</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold">Deskripsi</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-blush/40 bg-white/70">
                    @foreach($categories as $category)
                        <tr>
                            <td class="px-4 py-3">{{ $category->name }}</td>
                            <td class="px-4 py-3">{{ $category->description ?? '-' }}</td>
                            <td class="px-4 py-3">
                                <div class="flex gap-2">
                                    <a href="{{ route('categories.edit', $category) }}"
                                        class="rounded-full bg-rose px-3 py-1 text-sm">Edit</a>
                                    <form action="{{ route('categories.destroy', $category) }}" method="POST"
                                        onsubmit="return confirm('Hapus kategori ini?')">
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