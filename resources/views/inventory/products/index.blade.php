@extends('layouts.app')

@section('content')
    <div class="rounded-3xl border border-white/70 bg-white/80 p-6 shadow-soft">
        <div class="mb-6 flex items-center justify-between">
            <div>
                <h2 class="font-display text-2xl" style="color:var(--ink)">Daftar Produk</h2>
                <p class="text-sm" style="color:var(--ink-soft)">Kelola data produk beserta stok minimum dan supplier.</p>
            </div>
            @if(in_array(auth()->user()->role, ['Admin']))
                <div class="flex flex-wrap gap-2">
                    <a href="{{ route('products.export') }}" class="rounded-full px-4 py-2 text-sm font-medium"
                        style="background:var(--blush); color:var(--rose-dark)">Export CSV</a>
                    <button type="button" id="btn-open-import" class="rounded-full px-4 py-2 text-sm font-medium"
                        style="background:var(--blush); color:var(--rose-dark)">Import CSV</button>
                    <a href="{{ route('products.create') }}" class="rounded-full px-4 py-2 text-sm font-medium text-white"
                        style="background:var(--rose-dark)">Tambah Produk</a>
                </div>
            @endif
        </div>

        {{-- Modal Import CSV --}}
        @if(in_array(auth()->user()->role, ['Admin']))
            <div id="import-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/40 p-4">
                <div class="w-full max-w-md rounded-2xl bg-white p-6 shadow-xl">
                    <h3 class="font-display text-lg" style="color:var(--ink)">Import Produk dari CSV</h3>
                    <p class="mt-1 text-sm" style="color:var(--ink-soft)">
                        Kolom yang dibutuhkan:
                        <code>nama, sku, kategori, supplier, deskripsi, harga_beli, harga_jual, stok_minimum</code>.
                        SKU yang sudah ada akan diperbarui, SKU baru akan dibuatkan produk baru.
                        Nama kategori/supplier harus sudah terdaftar di sistem.
                    </p>
                    <form method="POST" action="{{ route('products.import') }}" enctype="multipart/form-data" class="mt-4">
                        @csrf
                        <input type="file" name="file" accept=".csv,text/csv" required
                            class="block w-full cursor-pointer rounded-xl border text-sm file:mr-4 file:cursor-pointer file:rounded-full file:border-0 file:bg-[var(--rose-dark)] file:px-3 file:py-2 file:text-sm file:font-medium file:text-white"
                            style="border-color:var(--line)">
                        <div class="mt-4 flex justify-end gap-2">
                            <button type="button" id="btn-cancel-import" class="rounded-full px-4 py-2 text-sm"
                                style="background:var(--blush); color:var(--ink)">Batal</button>
                            <button type="submit" class="rounded-full px-4 py-2 text-sm font-medium text-white"
                                style="background:var(--rose-dark)">Upload &amp; Import</button>
                        </div>
                    </form>
                </div>
            </div>
        @endif

        <div class="overflow-hidden rounded-2xl border" style="border-color:var(--line)">
            <table class="min-w-full divide-y" style="border-color:var(--line)">
                <thead style="background:var(--blush)">
                    <tr>
                        <th class="px-4 py-3 text-left text-sm font-semibold" style="color:var(--ink)">Nama</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold" style="color:var(--ink)">SKU</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold" style="color:var(--ink)">Kategori</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold" style="color:var(--ink)">Supplier</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold" style="color:var(--ink)">Stok</th>
                        @if(auth()->user()->role === 'Admin')
                            <th class="px-4 py-3 text-left text-sm font-semibold" style="color:var(--ink)">Aksi</th>
                        @endif
                    </tr>
                </thead>
                <tbody class="divide-y bg-white/70" style="border-color:var(--line)">
                    @foreach($products as $product)
                        <tr>
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-3">
                                    @if($product->image)
                                        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}"
                                            class="h-12 w-12 rounded-xl object-cover">
                                    @else
                                        <div class="flex h-12 w-12 items-center justify-center rounded-xl text-xs"
                                            style="background:var(--blush); color:var(--ink-soft)">No Img</div>
                                    @endif
                                    <div>
                                        <span style="color:var(--ink)">{{ $product->name }}</span>
                                        @if($product->attributes->isNotEmpty())
                                            <div class="mt-1 flex flex-wrap gap-1">
                                                @foreach($product->attributes as $attribute)
                                                    <span class="rounded-full px-2 py-0.5 text-[11px]"
                                                        style="background:var(--blush); color:var(--rose-dark)">
                                                        {{ $attribute->name }}: {{ $attribute->value }}
                                                    </span>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3" style="color:var(--ink-soft)">{{ $product->sku }}</td>
                            <td class="px-4 py-3" style="color:var(--ink-soft)">{{ $product->category->name ?? '-' }}</td>
                            <td class="px-4 py-3" style="color:var(--ink-soft)">{{ $product->supplier->name ?? '-' }}</td>
                            <td class="px-4 py-3" style="color:var(--ink-soft)">{{ $product->current_stock }}</td>
                            @if(auth()->user()->role === 'Admin')
                                <td class="px-4 py-3">
                                    <div class="flex gap-2">
                                        <a href="{{ route('products.edit', $product) }}"
                                            class="rounded-full px-3 py-1 text-sm font-medium"
                                            style="background:var(--blush); color:var(--rose-dark)">Edit</a>
                                        <button type="button" onclick='openDeleteProduct({{ $product->id }}, @json($product->name))'
                                            class="rounded-full px-3 py-1 text-sm font-medium text-white"
                                            style="background:#111111">Hapus</button>
                                    </div>
                                </td>
                            @endif
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- Modal Hapus Produk --}}
    <div id="modal-delete-product" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/30 p-4">
        <div class="w-full max-w-sm rounded-3xl border border-white bg-white p-6 text-center shadow-soft">
            <h3 class="font-display text-lg" style="color:var(--ink)">Hapus Produk?</h3>
            <p class="mt-2 text-sm" style="color:var(--ink-soft)">
                Yakin mau hapus <span id="delete-product-name" class="font-medium"></span>?
                Produk akan disembunyikan dari daftar dan riwayat transaksinya tetap tersimpan.
            </p>
            <form id="form-delete-product" method="POST" class="mt-5 flex justify-center gap-3">
                @csrf
                @method('DELETE')
                <button type="button" id="btn-cancel-delete-product" class="rounded-full px-4 py-2 text-sm"
                    style="background:var(--blush); color:var(--ink)">Batal</button>
                <button class="rounded-full px-4 py-2 text-sm text-white" style="background:#111111">Hapus</button>
            </form>
        </div>
    </div>

    <script>
        function openModal(id) {
            document.getElementById(id).classList.remove('hidden');
        }
        function closeModal(id) {
            document.getElementById(id).classList.add('hidden');
        }

        @if(in_array(auth()->user()->role, ['Admin']))
            document.getElementById('btn-open-import').addEventListener('click', () => openModal('import-modal'));
            document.getElementById('btn-cancel-import').addEventListener('click', () => closeModal('import-modal'));
        @endif

        document.getElementById('btn-cancel-delete-product').addEventListener('click', () => closeModal('modal-delete-product'));

        function openDeleteProduct(id, name) {
            document.getElementById('form-delete-product').action = '{{ url('/products') }}/' + id;
            document.getElementById('delete-product-name').textContent = name;
            openModal('modal-delete-product');
        }
    </script>
@endsection