@extends('layouts.app')

@section('content')
    <div class="mx-auto max-w-3xl rounded-3xl border border-white/70 bg-white/80 p-6 shadow-soft">
        <h2 class="font-display text-2xl" style="color:var(--ink)">Tambah Produk</h2>
        <form id="form-create-product" action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data"
            class="mt-6 grid gap-4 md:grid-cols-2" novalidate>
            @csrf
            <div>
                <label class="mb-1 block text-sm font-medium" style="color:var(--ink)">Nama Produk</label>
                <input id="p-name" name="name" value="{{ old('name') }}" maxlength="150"
                    data-limit-msg="Nama produk maksimal 150 karakter." class="w-full rounded-2xl border px-4 py-3"
                    style="border-color:var(--line); background:var(--cream); color:var(--ink)">
                <div class="mt-1 flex items-center justify-between">
                    <p id="p-name-error" class="hidden text-xs" style="color:#B3455A">Nama produk wajib diisi</p>
                    <span id="p-name-count" class="ml-auto text-xs" style="color:var(--ink-soft)">0/150</span>
                </div>
            </div>
            <div>
                <label class="mb-1 block text-sm font-medium" style="color:var(--ink)">SKU</label>
                <input id="p-sku" name="sku" value="{{ old('sku') }}" maxlength="30"
                    data-limit-msg="SKU maksimal 30 karakter." class="w-full rounded-2xl border px-4 py-3"
                    style="border-color:var(--line); background:var(--cream); color:var(--ink)">
                <div class="mt-1 flex items-center justify-between">
                    <p id="p-sku-error" class="hidden text-xs" style="color:#B3455A">SKU wajib diisi</p>
                    <span id="p-sku-count" class="ml-auto text-xs" style="color:var(--ink-soft)">0/30</span>
                </div>
            </div>
            <div>
                <label class="mb-1 block text-sm font-medium" style="color:var(--ink)">Kategori</label>
                <select name="category_id" class="w-full rounded-2xl border px-4 py-3"
                    style="border-color:var(--line); background:var(--cream); color:var(--ink)" required>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" @selected(old('category_id') == $category->id)>{{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="mb-1 block text-sm font-medium" style="color:var(--ink)">Supplier</label>
                <select name="supplier_id" class="w-full rounded-2xl border px-4 py-3"
                    style="border-color:var(--line); background:var(--cream); color:var(--ink)" required>
                    @foreach($suppliers as $supplier)
                        <option value="{{ $supplier->id }}" @selected(old('supplier_id') == $supplier->id)>{{ $supplier->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="mb-1 block text-sm font-medium" style="color:var(--ink)">Harga Beli</label>
                <input id="p-purchase" type="number" step="0.01" name="purchase_price" value="{{ old('purchase_price') }}"
                    class="w-full rounded-2xl border px-4 py-3"
                    style="border-color:var(--line); background:var(--cream); color:var(--ink)">
                <p id="p-purchase-error" class="mt-1 hidden text-xs" style="color:#B3455A">Harga beli wajib diisi</p>
            </div>
            <div>
                <label class="mb-1 block text-sm font-medium" style="color:var(--ink)">Harga Jual</label>
                <input id="p-selling" type="number" step="0.01" name="selling_price" value="{{ old('selling_price') }}"
                    class="w-full rounded-2xl border px-4 py-3"
                    style="border-color:var(--line); background:var(--cream); color:var(--ink)">
                <p id="p-selling-error" class="mt-1 hidden text-xs" style="color:#B3455A">Harga jual wajib diisi</p>
            </div>
            <div>
                <label class="mb-1 block text-sm font-medium" style="color:var(--ink)">Stok Awal</label>
                <input type="number" name="initial_stock" min="0" value="{{ old('initial_stock', 0) }}"
                    class="w-full rounded-2xl border px-4 py-3"
                    style="border-color:var(--line); background:var(--cream); color:var(--ink)">
                <p class="mt-1 text-xs" style="color:var(--ink-soft)">Jumlah stok fisik produk ini saat pertama kali
                    didaftarkan (opsional, bisa 0).</p>
            </div>
            <div>
                <label class="mb-1 block text-sm font-medium" style="color:var(--ink)">Stok Minimum</label>
                <input type="number" name="minimum_stock" value="{{ old('minimum_stock', 0) }}"
                    class="w-full rounded-2xl border px-4 py-3"
                    style="border-color:var(--line); background:var(--cream); color:var(--ink)">
            </div>
            <div class="md:col-span-2">
                <label class="mb-1 block text-sm font-medium" style="color:var(--ink)">Deskripsi</label>
                <textarea name="description" class="w-full rounded-2xl border px-4 py-3"
                    style="border-color:var(--line); background:var(--cream); color:var(--ink)">{{ old('description') }}</textarea>
            </div>
            <div class="md:col-span-2">
                <label class="mb-1 block text-sm font-medium" style="color:var(--ink)">Gambar Produk</label>
                <input type="file" name="image" accept="image/*"
                    class="block w-full cursor-pointer rounded-2xl border text-sm file:mr-4 file:cursor-pointer file:rounded-full file:border-0 file:bg-[var(--rose-dark)] file:px-4 file:py-2.5 file:text-sm file:font-medium file:text-white"
                    style="border-color:var(--line); background:var(--cream); color:var(--ink)">
            </div>
            @if($errors->any())
                <div class="md:col-span-2 rounded-2xl px-4 py-3 text-sm" style="background:#FBE0E6; color:#B3455A">
                    <ul class="list-inside list-disc space-y-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <div class="md:col-span-2 flex justify-end gap-3">
                <a href="{{ route('products.index') }}" class="rounded-full px-4 py-2"
                    style="background:var(--blush); color:var(--ink)">Batal</a>
                <button class="rounded-full px-4 py-2 text-white" style="background:var(--rose-dark)">Simpan</button>
            </div>
        </form>
    </div>

    <script>
        function requireField(inputId, errorId) {
            const input = document.getElementById(inputId);
            const error = document.getElementById(errorId);
            input.addEventListener('input', function () {
                if (input.value.trim()) {
                    error.classList.add('hidden');
                    input.style.borderColor = '';
                }
            });
            return { input, error };
        }

        function bindCounter(inputId, countId, max) {
            const input = document.getElementById(inputId);
            const count = document.getElementById(countId);
            function update() {
                const len = input.value.length;
                count.textContent = len + '/' + max;
                count.style.color = len >= max ? '#B3455A' : 'var(--ink-soft)';
            }
            update();
            input.addEventListener('input', update);
        }
        bindCounter('p-name', 'p-name-count', 150);
        bindCounter('p-sku', 'p-sku-count', 30);

        const fields = [
            requireField('p-name', 'p-name-error'),
            requireField('p-sku', 'p-sku-error'),
            requireField('p-purchase', 'p-purchase-error'),
            requireField('p-selling', 'p-selling-error'),
        ];

        document.getElementById('form-create-product').addEventListener('submit', function (e) {
            let valid = true;
            fields.forEach(({ input, error }) => {
                if (!input.value.trim()) {
                    error.classList.remove('hidden');
                    input.style.borderColor = '#B3455A';
                    valid = false;
                }
            });
            if (!valid) e.preventDefault();
        });
    </script>
@endsection