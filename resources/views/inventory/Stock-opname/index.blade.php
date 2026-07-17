@extends('layouts.app')

@section('content')
    <div class="rounded-3xl border border-white/70 bg-white/80 p-6 shadow-soft">
        <div class="mb-6">
            <h2 class="font-display text-2xl" style="color:var(--ink)">Stock Opname</h2>
            <p class="text-sm" style="color:var(--ink-soft)">Cocokkan stok sistem dengan hasil hitung fisik di gudang.
                Selisih otomatis disesuaikan ke stok sistem.</p>
        </div>

        {{-- Form catat opname baru --}}
        <form id="form-opname" action="{{ route('stock-opname.store') }}" method="POST"
            class="mb-6 grid gap-4 rounded-2xl border p-5 md:grid-cols-2"
            style="border-color:var(--line); background:var(--cream)" novalidate>
            @csrf
            <div>
                <label class="mb-1 block text-sm font-medium" style="color:var(--ink)">Produk</label>
                <select id="opname-product" name="product_id" onchange="updateSystemStock()"
                    class="w-full rounded-2xl border px-4 py-3"
                    style="border-color:var(--line); background:white; color:var(--ink)" required>
                    <option value="">Pilih produk</option>
                    @foreach($products as $product)
                        <option value="{{ $product->id }}" data-stock="{{ $product->current_stock }}">{{ $product->name }}
                            ({{ $product->sku }})</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="mb-1 block text-sm font-medium" style="color:var(--ink)">Stok Sistem Saat Ini</label>
                <input id="opname-system-stock" type="text" disabled placeholder="Pilih produk dulu"
                    class="w-full rounded-2xl border px-4 py-3"
                    style="border-color:var(--line); background:var(--blush); color:var(--ink-soft)">
            </div>
            <div>
                <label class="mb-1 block text-sm font-medium" style="color:var(--ink)">Stok Fisik (hasil hitung)</label>
                <input id="opname-physical" name="physical_stock" type="number" min="0" oninput="updateSelisih()"
                    class="w-full rounded-2xl border px-4 py-3"
                    style="border-color:var(--line); background:white; color:var(--ink)" required>
                <p id="opname-physical-error" class="mt-1 hidden text-xs" style="color:#B3455A">Stok fisik wajib diisi</p>
            </div>
            <div>
                <label class="mb-1 block text-sm font-medium" style="color:var(--ink)">Tanggal Opname</label>
                <input type="date" name="opname_date" value="{{ now()->format('Y-m-d') }}"
                    class="w-full rounded-2xl border px-4 py-3"
                    style="border-color:var(--line); background:white; color:var(--ink)" required>
            </div>
            <div class="md:col-span-2">
                <p id="opname-selisih" class="text-sm font-medium" style="color:var(--ink-soft)"></p>
            </div>
            <div class="md:col-span-2">
                <label class="mb-1 block text-sm font-medium" style="color:var(--ink)">Catatan</label>
                <textarea name="notes" placeholder="Opsional, misal: penyebab selisih"
                    class="w-full rounded-2xl border px-4 py-3"
                    style="border-color:var(--line); background:white; color:var(--ink)"></textarea>
            </div>
            <div class="md:col-span-2 flex justify-end">
                <button class="rounded-full px-5 py-2 text-sm font-medium text-white"
                    style="background:var(--rose-dark)">Simpan Stock Opname</button>
            </div>
        </form>

        {{-- Riwayat opname --}}
        <h3 class="mb-3 font-display text-lg" style="color:var(--ink)">Riwayat Stock Opname</h3>
        <div class="overflow-hidden rounded-2xl border" style="border-color:var(--line)">
            <table class="min-w-full divide-y" style="border-color:var(--line)">
                <thead style="background:var(--blush)">
                    <tr>
                        <th class="px-4 py-3 text-left text-sm font-semibold" style="color:var(--ink)">Tanggal</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold" style="color:var(--ink)">Produk</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold" style="color:var(--ink)">Stok Sistem</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold" style="color:var(--ink)">Stok Fisik</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold" style="color:var(--ink)">Selisih</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold" style="color:var(--ink)">Dicatat Oleh</th>
                    </tr>
                </thead>
                <tbody class="divide-y bg-white/70" style="border-color:var(--line)">
                    @forelse($opnames as $opname)
                        <tr>
                            <td class="px-4 py-3" style="color:var(--ink-soft)">{{ $opname->opname_date->format('d M Y') }}</td>
                            <td class="px-4 py-3" style="color:var(--ink)">{{ $opname->product->name ?? '-' }}</td>
                            <td class="px-4 py-3" style="color:var(--ink-soft)">{{ $opname->system_stock }}</td>
                            <td class="px-4 py-3" style="color:var(--ink-soft)">{{ $opname->physical_stock }}</td>
                            <td class="px-4 py-3">
                                @if($opname->difference === 0)
                                    <span class="rounded-full px-3 py-1 text-xs font-semibold text-white"
                                        style="background:#5C7D63">Sesuai</span>
                                @elseif($opname->difference > 0)
                                    <span class="rounded-full px-3 py-1 text-xs font-semibold text-white"
                                        style="background:#5C7D63">+{{ $opname->difference }}</span>
                                @else
                                    <span class="rounded-full px-3 py-1 text-xs font-semibold text-white"
                                        style="background:#8F355C">{{ $opname->difference }}</span>
                                @endif
                            </td>
                            <td class="px-4 py-3" style="color:var(--ink-soft)">{{ $opname->user->name ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-6 text-center text-sm" style="color:var(--ink-soft)">Belum ada
                                riwayat stock opname.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if(method_exists($opnames, 'links'))
            <div class="mt-4">{{ $opnames->links() }}</div>
        @endif
    </div>

    <script>
        function updateSystemStock() {
            const select = document.getElementById('opname-product');
            const stockInput = document.getElementById('opname-system-stock');
            const selected = select.options[select.selectedIndex];
            const stock = selected.getAttribute('data-stock');
            stockInput.value = stock !== null && select.value !== '' ? stock + ' unit' : '';
            updateSelisih();
        }

        function updateSelisih() {
            const select = document.getElementById('opname-product');
            const selected = select.options[select.selectedIndex];
            const systemStock = parseInt(selected.getAttribute('data-stock'));
            const physical = parseInt(document.getElementById('opname-physical').value);
            const label = document.getElementById('opname-selisih');

            if (isNaN(systemStock) || isNaN(physical)) {
                label.textContent = '';
                return;
            }
            const diff = physical - systemStock;
            if (diff === 0) {
                label.textContent = '✅ Sesuai — tidak ada selisih.';
                label.style.color = '#5C7D63';
            } else if (diff > 0) {
                label.textContent = `Selisih: +${diff} (stok fisik lebih banyak, akan ditambahkan otomatis ke stok sistem)`;
                label.style.color = '#5C7D63';
            } else {
                label.textContent = `Selisih: ${diff} (stok fisik lebih sedikit, akan dikurangi otomatis dari stok sistem)`;
                label.style.color = '#8F355C';
            }
        }

        const physicalInput = document.getElementById('opname-physical');
        const physicalError = document.getElementById('opname-physical-error');
        physicalInput.addEventListener('input', function () {
            if (physicalInput.value.trim() !== '') {
                physicalError.classList.add('hidden');
                physicalInput.style.borderColor = '';
            }
        });

        document.getElementById('form-opname').addEventListener('submit', function (e) {
            if (physicalInput.value.trim() === '') {
                e.preventDefault();
                physicalError.classList.remove('hidden');
                physicalInput.style.borderColor = '#B3455A';
            }
        });
    </script>
@endsection