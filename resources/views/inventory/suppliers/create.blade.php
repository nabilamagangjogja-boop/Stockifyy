@extends('layouts.app')

@section('content')
    <div class="mx-auto max-w-2xl rounded-3xl border border-white/70 bg-white/80 p-6 shadow-soft">
        <h2 class="font-display text-2xl" style="color:var(--ink)">Tambah Supplier</h2>
        <form id="form-create-supplier-page" action="{{ route('suppliers.store') }}" method="POST" class="mt-6 space-y-4"
            novalidate>
            @csrf
            <div>
                <label class="mb-1 block text-sm font-medium" style="color:var(--ink)">Nama Supplier</label>
                <input id="page-supplier-name" name="name" value="{{ old('name') }}" maxlength="100"
                    data-limit-msg="Nama supplier maksimal 100 karakter." class="w-full rounded-2xl border px-4 py-3"
                    style="border-color:var(--line); background:var(--cream); color:var(--ink)">
                <div class="mt-1 flex items-center justify-between">
                    <p id="page-supplier-name-error" class="hidden text-xs" style="color:#B3455A">Nama supplier wajib
                        diisi</p>
                    <span id="page-supplier-name-count" class="ml-auto text-xs" style="color:var(--ink-soft)">0/100</span>
                </div>
                @error('name')
                    <p class="mt-1 text-xs" style="color:#B3455A">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label class="mb-1 block text-sm font-medium" style="color:var(--ink)">Alamat</label>
                <textarea name="address" class="w-full rounded-2xl border px-4 py-3"
                    style="border-color:var(--line); background:var(--cream); color:var(--ink)">{{ old('address') }}</textarea>
                @error('address')
                    <p class="mt-1 text-xs" style="color:#B3455A">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label class="mb-1 block text-sm font-medium" style="color:var(--ink)">Telepon</label>
                <input name="phone" value="{{ old('phone') }}" type="tel" inputmode="tel" maxlength="15"
                    data-limit-msg="Nomor telepon maksimal 15 karakter."
                    oninput="this.value = this.value.replace(/[^0-9+\-\s()]/g, '')"
                    class="w-full rounded-2xl border px-4 py-3"
                    style="border-color:var(--line); background:var(--cream); color:var(--ink)">
                @error('phone')
                    <p class="mt-1 text-xs" style="color:#B3455A">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label class="mb-1 block text-sm font-medium" style="color:var(--ink)">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" maxlength="50"
                    data-limit-msg="Email maksimal 50 karakter." class="w-full rounded-2xl border px-4 py-3"
                    style="border-color:var(--line); background:var(--cream); color:var(--ink)">
                @error('email')
                    <p class="mt-1 text-xs" style="color:#B3455A">{{ $message }}</p>
                @enderror
            </div>
            <div class="flex justify-end gap-3">
                <a href="{{ route('suppliers.index') }}" class="rounded-full px-4 py-2"
                    style="background:var(--blush); color:var(--ink)">Batal</a>
                <button class="rounded-full px-4 py-2 text-white" style="background:var(--rose-dark)">Simpan</button>
            </div>
        </form>
    </div>

    <script>
        const pageSupplierInput = document.getElementById('page-supplier-name');
        const pageSupplierError = document.getElementById('page-supplier-name-error');
        const pageSupplierCount = document.getElementById('page-supplier-name-count');

        function updateNameCount() {
            const len = pageSupplierInput.value.length;
            pageSupplierCount.textContent = len + '/100';
            pageSupplierCount.style.color = len >= 100 ? '#B3455A' : 'var(--ink-soft)';
        }
        updateNameCount();

        pageSupplierInput.addEventListener('input', function () {
            updateNameCount();
            if (pageSupplierInput.value.trim()) {
                pageSupplierError.classList.add('hidden');
                pageSupplierInput.style.borderColor = '';
            }
        });

        document.getElementById('form-create-supplier-page').addEventListener('submit', function (e) {
            if (!pageSupplierInput.value.trim()) {
                e.preventDefault();
                pageSupplierError.classList.remove('hidden');
                pageSupplierInput.style.borderColor = '#B3455A';
                pageSupplierInput.focus();
            }
        });
    </script>
@endsection