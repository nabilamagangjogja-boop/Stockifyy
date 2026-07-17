@extends('layouts.app')

@section('content')
    <div class="rounded-3xl border border-white/70 bg-white/80 p-6 shadow-soft">
        <div class="mb-6 flex items-center justify-between">
            <div>
                <h2 class="font-display text-2xl" style="color:var(--ink)">Daftar Supplier</h2>
                <p class="text-sm" style="color:var(--ink-soft)">Kelola data supplier untuk mendukung proses pembelian dan
                    stok.</p>
            </div>
            <button type="button" onclick="document.getElementById('modal-create-supplier').classList.remove('hidden')"
                class="rounded-full px-4 py-2 text-sm font-medium text-white" style="background:var(--rose-dark)">Tambah
                Supplier</button>
        </div>

        <div class="overflow-hidden rounded-2xl border" style="border-color:var(--line)">
            <table class="min-w-full divide-y" style="border-color:var(--line)">
                <thead style="background:var(--blush)">
                    <tr>
                        <th class="px-4 py-3 text-left text-sm font-semibold" style="color:var(--ink)">Nama</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold" style="color:var(--ink)">Alamat</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold" style="color:var(--ink)">Telepon</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold" style="color:var(--ink)">Email</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold" style="color:var(--ink)">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y bg-white/70" style="border-color:var(--line)">
                    @forelse($suppliers as $supplier)
                        <tr>
                            <td class="px-4 py-3" style="color:var(--ink)">{{ $supplier->name }}</td>
                            <td class="px-4 py-3" style="color:var(--ink-soft)">{{ $supplier->address ?? '-' }}</td>
                            <td class="px-4 py-3" style="color:var(--ink-soft)">{{ $supplier->phone ?? '-' }}</td>
                            <td class="px-4 py-3" style="color:var(--ink-soft)">{{ $supplier->email ?? '-' }}</td>
                            <td class="px-4 py-3">
                                <div class="flex gap-2">
                                    <button type="button"
                                        onclick='openEditSupplier({{ $supplier->id }}, @json($supplier->name), @json($supplier->address ?? ""), @json($supplier->phone ?? ""), @json($supplier->email ?? ""))'
                                        class="rounded-full px-3 py-1 text-sm font-medium"
                                        style="background:var(--blush); color:var(--rose-dark)">Edit</button>
                                    <button type="button"
                                        onclick='openDeleteSupplier({{ $supplier->id }}, @json($supplier->name))'
                                        class="rounded-full px-3 py-1 text-sm font-medium text-white"
                                        style="background:#111111">Hapus</button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-6 text-center text-sm" style="color:var(--ink-soft)">
                                Belum ada supplier. Klik "Tambah Supplier" untuk menambahkan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if(method_exists($suppliers, 'links'))
            <div class="mt-4">{{ $suppliers->links() }}</div>
        @endif
    </div>

    {{-- Modal Tambah Supplier --}}
    <div id="modal-create-supplier" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/30 p-4">
        <div class="w-full max-w-md rounded-3xl border border-white bg-white p-6 shadow-soft">
            <div class="mb-4 flex items-center justify-between">
                <h3 class="font-display text-xl" style="color:var(--ink)">Tambah Supplier</h3>
                <button type="button" onclick="document.getElementById('modal-create-supplier').classList.add('hidden')"
                    class="text-lg leading-none" style="color:var(--ink-soft)">&times;</button>
            </div>
            <form id="form-create-supplier" action="{{ route('suppliers.store') }}" method="POST" class="space-y-4"
                novalidate>
                @csrf
                <div>
                    <label class="mb-1 block text-sm font-medium" style="color:var(--ink)">Nama Supplier</label>
                    <input id="create-supplier-name" name="name" value="{{ old('name') }}"
                        class="w-full rounded-2xl border px-4 py-3"
                        style="border-color:var(--line); background:var(--cream); color:var(--ink)">
                    <p id="create-supplier-name-error" class="mt-1 hidden text-xs" style="color:#B3455A">Nama supplier wajib
                        diisi</p>
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium" style="color:var(--ink)">Alamat</label>
                    <textarea name="address" class="w-full rounded-2xl border px-4 py-3"
                        style="border-color:var(--line); background:var(--cream); color:var(--ink)">{{ old('address') }}</textarea>
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium" style="color:var(--ink)">Telepon</label>
                    <input name="phone" value="{{ old('phone') }}" type="tel" inputmode="tel"
                        oninput="this.value = this.value.replace(/[^0-9+\-\s()]/g, '')"
                        class="w-full rounded-2xl border px-4 py-3"
                        style="border-color:var(--line); background:var(--cream); color:var(--ink)">
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium" style="color:var(--ink)">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" class="w-full rounded-2xl border px-4 py-3"
                        style="border-color:var(--line); background:var(--cream); color:var(--ink)">
                </div>
                <div class="flex justify-end gap-3">
                    <button type="button" onclick="document.getElementById('modal-create-supplier').classList.add('hidden')"
                        class="rounded-full px-4 py-2" style="background:var(--blush); color:var(--ink)">Batal</button>
                    <button class="rounded-full px-4 py-2 text-white" style="background:var(--rose-dark)">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Modal Edit Supplier --}}
    <div id="modal-edit-supplier" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/30 p-4">
        <div class="w-full max-w-md rounded-3xl border border-white bg-white p-6 shadow-soft">
            <div class="mb-4 flex items-center justify-between">
                <h3 class="font-display text-xl" style="color:var(--ink)">Edit Supplier</h3>
                <button type="button" onclick="document.getElementById('modal-edit-supplier').classList.add('hidden')"
                    class="text-lg leading-none" style="color:var(--ink-soft)">&times;</button>
            </div>
            <form id="form-edit-supplier" method="POST" class="space-y-4" novalidate>
                @csrf
                @method('PUT')
                <input type="hidden" name="supplier_id" id="edit-supplier-id">
                <div>
                    <label class="mb-1 block text-sm font-medium" style="color:var(--ink)">Nama Supplier</label>
                    <input id="edit-supplier-name" name="name" class="w-full rounded-2xl border px-4 py-3"
                        style="border-color:var(--line); background:var(--cream); color:var(--ink)">
                    <p id="edit-supplier-name-error" class="mt-1 hidden text-xs" style="color:#B3455A">Nama supplier wajib
                        diisi</p>
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium" style="color:var(--ink)">Alamat</label>
                    <textarea id="edit-supplier-address" name="address" class="w-full rounded-2xl border px-4 py-3"
                        style="border-color:var(--line); background:var(--cream); color:var(--ink)"></textarea>
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium" style="color:var(--ink)">Telepon</label>
                    <input id="edit-supplier-phone" name="phone" type="tel" inputmode="tel"
                        oninput="this.value = this.value.replace(/[^0-9+\-\s()]/g, '')"
                        class="w-full rounded-2xl border px-4 py-3"
                        style="border-color:var(--line); background:var(--cream); color:var(--ink)">
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium" style="color:var(--ink)">Email</label>
                    <input type="email" id="edit-supplier-email" name="email" class="w-full rounded-2xl border px-4 py-3"
                        style="border-color:var(--line); background:var(--cream); color:var(--ink)">
                </div>
                <div class="flex justify-end gap-3">
                    <button type="button" onclick="document.getElementById('modal-edit-supplier').classList.add('hidden')"
                        class="rounded-full px-4 py-2" style="background:var(--blush); color:var(--ink)">Batal</button>
                    <button class="rounded-full px-4 py-2 text-white" style="background:var(--rose-dark)">Perbarui</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Modal Hapus Supplier --}}
    <div id="modal-delete-supplier" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/30 p-4">
        <div class="w-full max-w-sm rounded-3xl border border-white bg-white p-6 text-center shadow-soft">
            <h3 class="font-display text-lg" style="color:var(--ink)">Hapus Supplier?</h3>
            <p class="mt-2 text-sm" style="color:var(--ink-soft)">
                Yakin mau hapus <span id="delete-supplier-name" class="font-medium"></span>?
                Data ini tidak bisa dikembalikan.
            </p>
            <form id="form-delete-supplier" method="POST" class="mt-5 flex justify-center gap-3">
                @csrf
                @method('DELETE')
                <button type="button" onclick="document.getElementById('modal-delete-supplier').classList.add('hidden')"
                    class="rounded-full px-4 py-2 text-sm" style="background:var(--blush); color:var(--ink)">Batal</button>
                <button class="rounded-full px-4 py-2 text-sm text-white" style="background:#111111">Hapus</button>
            </form>
        </div>
    </div>

    <script>
        function validateSupplierField(inputId, errorId) {
            const input = document.getElementById(inputId);
            const error = document.getElementById(errorId);
            if (!input.value.trim()) {
                error.classList.remove('hidden');
                input.style.borderColor = '#B3455A';
                input.focus();
                return false;
            }
            error.classList.add('hidden');
            input.style.borderColor = '';
            return true;
        }

        function clearSupplierFieldError(inputId, errorId) {
            const input = document.getElementById(inputId);
            const error = document.getElementById(errorId);
            input.addEventListener('input', function () {
                if (input.value.trim()) {
                    error.classList.add('hidden');
                    input.style.borderColor = '';
                }
            });
        }

        clearSupplierFieldError('create-supplier-name', 'create-supplier-name-error');
        clearSupplierFieldError('edit-supplier-name', 'edit-supplier-name-error');

        document.getElementById('form-create-supplier').addEventListener('submit', function (e) {
            if (!validateSupplierField('create-supplier-name', 'create-supplier-name-error')) e.preventDefault();
        });

        document.getElementById('form-edit-supplier').addEventListener('submit', function (e) {
            if (!validateSupplierField('edit-supplier-name', 'edit-supplier-name-error')) e.preventDefault();
        });

        function openEditSupplier(id, name, address, phone, email) {
            document.getElementById('form-edit-supplier').action = '{{ url('/suppliers') }}/' + id;
            document.getElementById('edit-supplier-id').value = id;
            document.getElementById('edit-supplier-name').value = name;
            document.getElementById('edit-supplier-address').value = address;
            document.getElementById('edit-supplier-phone').value = phone;
            document.getElementById('edit-supplier-email').value = email;
            document.getElementById('modal-edit-supplier').classList.remove('hidden');
        }

        function openDeleteSupplier(id, name) {
            document.getElementById('form-delete-supplier').action = '{{ url('/suppliers') }}/' + id;
            document.getElementById('delete-supplier-name').textContent = name;
            document.getElementById('modal-delete-supplier').classList.remove('hidden');
        }

        @if($errors->any())
            @if(old('supplier_id'))
                openEditSupplier('{{ old('supplier_id') }}', @json(old('name')), @json(old('address')), @json(old('phone')), @json(old('email')));
            @else
                document.getElementById('modal-create-supplier').classList.remove('hidden');
            @endif
        @endif
    </script>
@endsection