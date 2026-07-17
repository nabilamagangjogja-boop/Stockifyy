@extends('layouts.app')

@section('content')
    <div class="mx-auto max-w-2xl rounded-3xl border border-white/70 bg-white/80 p-6 shadow-soft">
        <h2 class="text-2xl font-semibold">Edit Supplier</h2>
        <form action="{{ route('suppliers.update', $supplier) }}" method="POST" class="mt-6 space-y-4">
            @csrf
            @method('PUT')
            <div>
                <label class="mb-1 block text-sm font-medium">Nama Supplier</label>
                <input id="edit-supplier-name" name="name" value="{{ old('name', $supplier->name) }}" maxlength="100"
                    data-limit-msg="Nama supplier maksimal 100 karakter."
                    class="w-full rounded-2xl border border-blush bg-cream px-4 py-3" required>
                <div class="mt-1 flex items-center justify-between">
                    <span></span>
                    <span id="edit-supplier-name-count" class="ml-auto text-xs" style="color:var(--ink-soft)"></span>
                </div>
                @error('name')
                    <p class="mt-1 text-xs" style="color:#B3455A">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label class="mb-1 block text-sm font-medium">Alamat</label>
                <textarea name="address"
                    class="w-full rounded-2xl border border-blush bg-cream px-4 py-3">{{ old('address', $supplier->address) }}</textarea>
                @error('address')
                    <p class="mt-1 text-xs" style="color:#B3455A">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label class="mb-1 block text-sm font-medium">Telepon</label>
                <input id="edit-supplier-phone" name="phone" value="{{ old('phone', $supplier->phone) }}" maxlength="15"
                    data-limit-msg="Nomor telepon maksimal 15 karakter."
                    class="w-full rounded-2xl border border-blush bg-cream px-4 py-3">
                @error('phone')
                    <p class="mt-1 text-xs" style="color:#B3455A">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label class="mb-1 block text-sm font-medium">Email</label>
                <input id="edit-supplier-email" type="email" name="email" value="{{ old('email', $supplier->email) }}"
                    maxlength="50" data-limit-msg="Email maksimal 50 karakter."
                    class="w-full rounded-2xl border border-blush bg-cream px-4 py-3">
                @error('email')
                    <p class="mt-1 text-xs" style="color:#B3455A">{{ $message }}</p>
                @enderror
            </div>
            <div class="flex justify-end gap-3">
                <a href="{{ route('suppliers.index') }}" class="rounded-full bg-blush px-4 py-2">Batal</a>
                <button class="rounded-full bg-ink px-4 py-2 text-white">Perbarui</button>
            </div>
        </form>
    </div>

    <script>
        const editSupplierName = document.getElementById('edit-supplier-name');
        const editSupplierNameCount = document.getElementById('edit-supplier-name-count');

        function updateEditNameCount() {
            const len = editSupplierName.value.length;
            editSupplierNameCount.textContent = len + '/100';
            editSupplierNameCount.style.color = len >= 100 ? '#B3455A' : 'var(--ink-soft)';
        }
        updateEditNameCount();
        editSupplierName.addEventListener('input', updateEditNameCount);
    </script>
@endsection