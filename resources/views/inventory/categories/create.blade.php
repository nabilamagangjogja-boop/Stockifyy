@extends('layouts.app')

@section('content')
    <div class="mx-auto max-w-2xl rounded-3xl border border-white/70 bg-white/80 p-6 shadow-soft">
        <h2 class="font-display text-2xl" style="color:var(--ink)">Tambah Kategori</h2>
        <form id="form-create-category-page" action="{{ route('categories.store') }}" method="POST" class="mt-6 space-y-4"
            novalidate>
            @csrf
            <div>
                <label class="mb-1 block text-sm font-medium" style="color:var(--ink)">Nama Kategori</label>
                <input id="page-category-name" name="name" value="{{ old('name') }}" maxlength="50"
                    data-limit-msg="Nama kategori maksimal 50 karakter." class="w-full rounded-2xl border px-4 py-3"
                    style="border-color:var(--line); background:var(--cream); color:var(--ink)">
                <div class="mt-1 flex items-center justify-between">
                    <p id="page-category-name-error" class="hidden text-xs" style="color:#B3455A">Nama kategori wajib diisi
                    </p>
                    <span id="page-category-name-count" class="ml-auto text-xs" style="color:var(--ink-soft)">0/50</span>
                </div>
                @error('name')
                    <p class="mt-1 text-xs" style="color:#B3455A">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label class="mb-1 block text-sm font-medium" style="color:var(--ink)">Deskripsi</label>
                <textarea name="description" class="w-full rounded-2xl border px-4 py-3"
                    style="border-color:var(--line); background:var(--cream); color:var(--ink)">{{ old('description') }}</textarea>
                @error('description')
                    <p class="mt-1 text-xs" style="color:#B3455A">{{ $message }}</p>
                @enderror
            </div>
            <div class="flex justify-end gap-3">
                <a href="{{ route('categories.index') }}" class="rounded-full px-4 py-2"
                    style="background:var(--blush); color:var(--ink)">Batal</a>
                <button class="rounded-full px-4 py-2 text-white" style="background:var(--rose-dark)">Simpan</button>
            </div>
        </form>
    </div>

    <script>
        const pageCategoryInput = document.getElementById('page-category-name');
        const pageCategoryError = document.getElementById('page-category-name-error');
        const pageCategoryCount = document.getElementById('page-category-name-count');

        function updateCategoryCount() {
            const len = pageCategoryInput.value.length;
            pageCategoryCount.textContent = len + '/50';
            pageCategoryCount.style.color = len >= 50 ? '#B3455A' : 'var(--ink-soft)';
        }
        updateCategoryCount();

        pageCategoryInput.addEventListener('input', function () {
            updateCategoryCount();
            if (pageCategoryInput.value.trim()) {
                pageCategoryError.classList.add('hidden');
                pageCategoryInput.style.borderColor = '';
            }
        });

        document.getElementById('form-create-category-page').addEventListener('submit', function (e) {
            if (!pageCategoryInput.value.trim()) {
                e.preventDefault();
                pageCategoryError.classList.remove('hidden');
                pageCategoryInput.style.borderColor = '#B3455A';
                pageCategoryInput.focus();
            }
        });
    </script>
@endsection