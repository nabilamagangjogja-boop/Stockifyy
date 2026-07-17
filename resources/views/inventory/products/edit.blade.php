@extends('layouts.app')

@section('content')
    <div class="mx-auto max-w-3xl rounded-3xl border border-white/70 bg-white/80 p-6 shadow-soft">
        <h2 class="font-display text-2xl" style="color:var(--ink)">Edit Produk</h2>
        <form action="{{ route('products.update', $product) }}" method="POST" enctype="multipart/form-data"
            class="mt-6 grid gap-4 md:grid-cols-2">
            @csrf
            @method('PUT')
            <div>
                <label class="mb-1 block text-sm font-medium" style="color:var(--ink)">Nama Produk</label>
                <input id="pe-name" name="name" value="{{ old('name', $product->name) }}" maxlength="150"
                    data-limit-msg="Nama produk maksimal 150 karakter." class="w-full rounded-2xl border px-4 py-3"
                    style="border-color:var(--line); background:var(--cream); color:var(--ink)" required>
                <div class="mt-1 flex justify-end">
                    <span id="pe-name-count" class="text-xs" style="color:var(--ink-soft)"></span>
                </div>
            </div>
            <div>
                <label class="mb-1 block text-sm font-medium" style="color:var(--ink)">SKU</label>
                <input id="pe-sku" name="sku" value="{{ old('sku', $product->sku) }}" maxlength="30"
                    data-limit-msg="SKU maksimal 30 karakter." class="w-full rounded-2xl border px-4 py-3"
                    style="border-color:var(--line); background:var(--cream); color:var(--ink)" required>
                <div class="mt-1 flex justify-end">
                    <span id="pe-sku-count" class="text-xs" style="color:var(--ink-soft)"></span>
                </div>
            </div>
            <div>
                <label class="mb-1 block text-sm font-medium" style="color:var(--ink)">Kategori</label>
                <select name="category_id" class="w-full rounded-2xl border px-4 py-3"
                    style="border-color:var(--line); background:var(--cream); color:var(--ink)" required>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" @selected(old('category_id', $product->category_id) == $category->id)>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="mb-1 block text-sm font-medium" style="color:var(--ink)">Supplier</label>
                <select name="supplier_id" class="w-full rounded-2xl border px-4 py-3"
                    style="border-color:var(--line); background:var(--cream); color:var(--ink)" required>
                    @foreach($suppliers as $supplier)
                        <option value="{{ $supplier->id }}" @selected(old('supplier_id', $product->supplier_id) == $supplier->id)>
                            {{ $supplier->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="mb-1 block text-sm font-medium" style="color:var(--ink)">Harga Beli</label>
                <input type="number" step="0.01" name="purchase_price"
                    value="{{ old('purchase_price', $product->purchase_price) }}"
                    class="w-full rounded-2xl border px-4 py-3"
                    style="border-color:var(--line); background:var(--cream); color:var(--ink)" required>
            </div>
            <div>
                <label class="mb-1 block text-sm font-medium" style="color:var(--ink)">Harga Jual</label>
                <input type="number" step="0.01" name="selling_price"
                    value="{{ old('selling_price', $product->selling_price) }}" class="w-full rounded-2xl border px-4 py-3"
                    style="border-color:var(--line); background:var(--cream); color:var(--ink)" required>
            </div>
            <div>
                <label class="mb-1 block text-sm font-medium" style="color:var(--ink)">Stok Minimum</label>
                <input type="number" name="minimum_stock" value="{{ old('minimum_stock', $product->minimum_stock) }}"
                    class="w-full rounded-2xl border px-4 py-3"
                    style="border-color:var(--line); background:var(--cream); color:var(--ink)">
            </div>
            <div class="md:col-span-2">
                <label class="mb-1 block text-sm font-medium" style="color:var(--ink)">Deskripsi</label>
                <textarea name="description" class="w-full rounded-2xl border px-4 py-3"
                    style="border-color:var(--line); background:var(--cream); color:var(--ink)">{{ old('description', $product->description) }}</textarea>
            </div>
            <div class="md:col-span-2">
                <label class="mb-1 block text-sm font-medium" style="color:var(--ink)">Gambar Produk</label>
                <input type="file" name="image" accept="image/*"
                    class="block w-full cursor-pointer rounded-2xl border text-sm file:mr-4 file:cursor-pointer file:rounded-full file:border-0 file:bg-[var(--rose-dark)] file:px-4 file:py-2.5 file:text-sm file:font-medium file:text-white"
                    style="border-color:var(--line); background:var(--cream); color:var(--ink)">
                @if($product->image)
                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}"
                        class="mt-3 h-24 w-24 rounded-2xl object-cover">
                @endif
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
                <button class="rounded-full px-4 py-2 text-white" style="background:var(--rose-dark)">Perbarui</button>
            </div>
        </form>

        {{-- Atribut Produk --}}
        <div class="mt-8 border-t pt-6" style="border-color:var(--line)">
            <h3 class="font-display text-lg" style="color:var(--ink)">Atribut Produk</h3>
            <p class="mt-1 text-sm" style="color:var(--ink-soft)">Tambahkan detail spesifik produk ini, misalnya Ukuran,
                Warna, atau Berat.</p>

            <div class="mt-4 space-y-2">
                @forelse($product->attributes as $attribute)
                    <div class="flex items-center justify-between rounded-2xl px-4 py-2" style="background:var(--cream)">
                        <span style="color:var(--ink)"><strong>{{ $attribute->name }}</strong>: {{ $attribute->value }}</span>
                        <form action="{{ route('attributes.destroy', [$product, $attribute]) }}" method="POST"
                            onsubmit="return confirm('Hapus atribut ini?')">
                            @csrf
                            @method('DELETE')
                            <button class="rounded-full px-3 py-1 text-xs font-medium text-white"
                                style="background:#111111">Hapus</button>
                        </form>
                    </div>
                @empty
                    <p class="text-sm" style="color:var(--ink-soft)">Belum ada atribut untuk produk ini.</p>
                @endforelse
            </div>

            <form id="form-add-attribute" action="{{ route('attributes.store', $product) }}" method="POST"
                class="mt-4 flex flex-wrap items-end gap-2" novalidate>
                @csrf
                <div>
                    <label class="mb-1 block text-xs font-medium" style="color:var(--ink-soft)">Nama Atribut</label>
                    <input id="attr-name" name="name" placeholder="Misal: Ukuran" maxlength="50"
                        data-limit-msg="Nama atribut maksimal 50 karakter." class="rounded-full border px-4 py-2 text-sm"
                        style="border-color:var(--line); background:var(--cream); color:var(--ink)">
                    <p id="attr-name-error" class="mt-1 hidden text-xs" style="color:#B3455A">Nama atribut wajib diisi</p>
                </div>
                <div>
                    <label class="mb-1 block text-xs font-medium" style="color:var(--ink-soft)">Nilai</label>
                    <input id="attr-value" name="value" placeholder="Misal: L" maxlength="100"
                        data-limit-msg="Nilai atribut maksimal 100 karakter." class="rounded-full border px-4 py-2 text-sm"
                        style="border-color:var(--line); background:var(--cream); color:var(--ink)">
                    <p id="attr-value-error" class="mt-1 hidden text-xs" style="color:#B3455A">Nilai wajib diisi</p>
                </div>
                <button class="rounded-full px-4 py-2 text-sm font-medium text-white" style="background:var(--rose-dark)">+
                    Tambah Atribut</button>
            </form>
        </div>

        <script>
            function bindCounter(inputId, countId, max) {
                const input = document.getElementById(inputId);
                const count = document.getElementById(countId);
                if (!input || !count) return;
                function update() {
                    const len = input.value.length;
                    count.textContent = len + '/' + max;
                    count.style.color = len >= max ? '#B3455A' : 'var(--ink-soft)';
                }
                update();
                input.addEventListener('input', update);
            }
            bindCounter('pe-name', 'pe-name-count', 150);
            bindCounter('pe-sku', 'pe-sku-count', 30);

            function attrRequire(inputId, errorId) {
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
            const attrFields = [attrRequire('attr-name', 'attr-name-error'), attrRequire('attr-value', 'attr-value-error')];
            document.getElementById('form-add-attribute').addEventListener('submit', function (e) {
                let valid = true;
                attrFields.forEach(({ input, error }) => {
                    if (!input.value.trim()) {
                        error.classList.remove('hidden');
                        input.style.borderColor = '#B3455A';
                        valid = false;
                    }
                });
                if (!valid) e.preventDefault();
            });
        </script>
    </div>
@endsection