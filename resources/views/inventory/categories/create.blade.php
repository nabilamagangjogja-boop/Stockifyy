@extends('layouts.app')

@section('content')
    <div class="mx-auto max-w-2xl rounded-3xl border border-white/70 bg-white/80 p-6 shadow-soft">
        <h2 class="text-2xl font-semibold">Tambah Kategori</h2>
        <form action="{{ route('categories.store') }}" method="POST" class="mt-6 space-y-4">
            @csrf
            <div>
                <label class="mb-1 block text-sm font-medium">Nama Kategori</label>
                <input name="name" class="w-full rounded-2xl border border-blush bg-cream px-4 py-3" required>
            </div>
            <div>
                <label class="mb-1 block text-sm font-medium">Deskripsi</label>
                <textarea name="description" class="w-full rounded-2xl border border-blush bg-cream px-4 py-3"></textarea>
            </div>
            <div class="flex justify-end gap-3">
                <a href="{{ route('categories.index') }}" class="rounded-full bg-blush px-4 py-2">Batal</a>
                <button class="rounded-full bg-ink px-4 py-2 text-white">Simpan</button>
            </div>
        </form>
    </div>
@endsection