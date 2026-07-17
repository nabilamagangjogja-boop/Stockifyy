@extends('layouts.app')

@section('content')
    <div class="mx-auto max-w-2xl rounded-3xl border border-white/70 bg-white/80 p-6 shadow-soft">
        <h2 class="text-2xl font-semibold">Edit Kategori</h2>
        <form action="{{ route('categories.update', $category) }}" method="POST" class="mt-6 space-y-4">
            @csrf
            @method('PUT')
            <div>
                <label class="mb-1 block text-sm font-medium">Nama Kategori</label>
                <input id="edit-category-name" name="name" value="{{ old('name', $category->name) }}" maxlength="50"
                    data-limit-msg="Nama kategori maksimal 50 karakter."
                    class="w-full rounded-2xl border border-blush bg-cream px-4 py-3" required>
                <div class="mt-1 flex justify-end">
                    <span id="edit-category-name-count" class="text-xs" style="color:var(--ink-soft)"></span>
                </div>
                @error('name')
                    <p class="mt-1 text-xs" style="color:#B3455A">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label class="mb-1 block text-sm font-medium">Deskripsi</label>
                <textarea name="description"
                    class="w-full rounded-2xl border border-blush bg-cream px-4 py-3">{{ old('description', $category->description) }}</textarea>
                @error('description')
                    <p class="mt-1 text-xs" style="color:#B3455A">{{ $message }}</p>
                @enderror
            </div>
            <div class="flex justify-end gap-3">
                <a href="{{ route('categories.index') }}" class="rounded-full bg-blush px-4 py-2">Batal</a>
                <button class="rounded-full bg-ink px-4 py-2 text-white">Perbarui</button>
            </div>
        </form>
    </div>

    <script>
        const editCategoryName = document.getElementById('edit-category-name');
        const editCategoryNameCount = document.getElementById('edit-category-name-count');

        function updateEditCategoryCount() {
            const len = editCategoryName.value.length;
            editCategoryNameCount.textContent = len + '/50';
            editCategoryNameCount.style.color = len >= 50 ? '#B3455A' : 'var(--ink-soft)';
        }
        updateEditCategoryCount();
        editCategoryName.addEventListener('input', updateEditCategoryCount);
    </script>
@endsection