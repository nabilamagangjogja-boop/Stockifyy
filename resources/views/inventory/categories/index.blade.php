@extends('layouts.app')

@section('content')
    <div class="rounded-3xl border border-white/70 bg-white/80 p-6 shadow-soft">
        <div class="mb-6 flex items-center justify-between">
            <div>
                <h2 class="font-display text-2xl" style="color:var(--ink)">Kelola Kategori</h2>
                <p class="text-sm" style="color:var(--ink-soft)">Atur kategori produk dengan tampilan yang rapi dan nyaman.
                </p>
            </div>
            <button type="button" id="btn-open-create-category"
                class="rounded-full px-4 py-2 text-sm font-medium text-white" style="background:var(--rose-dark)">Tambah
                Kategori</button>
        </div>

        <div class="overflow-hidden rounded-2xl border" style="border-color:var(--line)">
            <table class="min-w-full divide-y" style="border-color:var(--line)">
                <thead style="background:var(--blush)">
                    <tr>
                        <th class="px-4 py-3 text-left text-sm font-semibold" style="color:var(--ink)">Nama</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold" style="color:var(--ink)">Deskripsi</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold" style="color:var(--ink)">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y bg-white/70" style="border-color:var(--line)">
                    @forelse($categories as $category)
                        <tr>
                            <td class="px-4 py-3" style="color:var(--ink)">{{ $category->name }}</td>
                            <td class="px-4 py-3" style="color:var(--ink-soft)">{{ $category->description ?? '-' }}</td>
                            <td class="px-4 py-3">
                                <div class="flex gap-2">
                                    <button type="button"
                                        onclick='openEditCategory({{ $category->id }}, @json($category->name), @json($category->description ?? ""))'
                                        class="rounded-full px-3 py-1 text-sm font-medium"
                                        style="background:var(--blush); color:var(--rose-dark)">Edit</button>
                                    <button type="button"
                                        onclick='openDeleteCategory({{ $category->id }}, @json($category->name))'
                                        class="rounded-full px-3 py-1 text-sm font-medium text-white"
                                        style="background:#111111">Hapus</button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-4 py-6 text-center text-sm" style="color:var(--ink-soft)">
                                Belum ada kategori. Klik "Tambah Kategori" untuk menambahkan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if(method_exists($categories, 'links'))
            <div class="mt-4">{{ $categories->links() }}</div>
        @endif
    </div>

    {{-- Modal Tambah Kategori --}}
    <div id="modal-create-category" tabindex="-1" aria-hidden="true"
        class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/30 p-4">
        <div class="w-full max-w-md rounded-3xl border border-white bg-white p-6 shadow-soft">
            <div class="mb-4 flex items-center justify-between">
                <h3 class="font-display text-xl" style="color:var(--ink)">Tambah Kategori</h3>
                <button type="button" id="btn-close-create-category" class="text-lg leading-none"
                    style="color:var(--ink-soft)">&times;</button>
            </div>
            <form id="form-create-category" action="{{ route('categories.store') }}" method="POST" class="space-y-4"
                novalidate>
                @csrf
                <div>
                    <label class="mb-1 block text-sm font-medium" style="color:var(--ink)">Nama Kategori</label>
                    <input id="create-category-name" name="name" value="{{ old('name') }}"
                        class="w-full rounded-2xl border px-4 py-3"
                        style="border-color:var(--line); background:var(--cream); color:var(--ink)">
                    <p id="create-category-name-error" class="mt-1 hidden text-xs" style="color:#B3455A">Nama kategori wajib
                        diisi</p>
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium" style="color:var(--ink)">Deskripsi</label>
                    <textarea name="description" class="w-full rounded-2xl border px-4 py-3"
                        style="border-color:var(--line); background:var(--cream); color:var(--ink)">{{ old('description') }}</textarea>
                </div>
                <div class="flex justify-end gap-3">
                    <button type="button" id="btn-cancel-create-category" class="rounded-full px-4 py-2"
                        style="background:var(--blush); color:var(--ink)">Batal</button>
                    <button class="rounded-full px-4 py-2 text-white" style="background:var(--rose-dark)">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Modal Edit Kategori --}}
    <div id="modal-edit-category" tabindex="-1" aria-hidden="true"
        class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/30 p-4">
        <div class="w-full max-w-md rounded-3xl border border-white bg-white p-6 shadow-soft">
            <div class="mb-4 flex items-center justify-between">
                <h3 class="font-display text-xl" style="color:var(--ink)">Edit Kategori</h3>
                <button type="button" id="btn-close-edit-category" class="text-lg leading-none"
                    style="color:var(--ink-soft)">&times;</button>
            </div>
            <form id="form-edit-category" method="POST" class="space-y-4" novalidate>
                @csrf
                @method('PUT')
                <input type="hidden" name="category_id" id="edit-category-id">
                <div>
                    <label class="mb-1 block text-sm font-medium" style="color:var(--ink)">Nama Kategori</label>
                    <input id="edit-category-name" name="name" class="w-full rounded-2xl border px-4 py-3"
                        style="border-color:var(--line); background:var(--cream); color:var(--ink)">
                    <p id="edit-category-name-error" class="mt-1 hidden text-xs" style="color:#B3455A">Nama kategori wajib
                        diisi</p>
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium" style="color:var(--ink)">Deskripsi</label>
                    <textarea id="edit-category-description" name="description" class="w-full rounded-2xl border px-4 py-3"
                        style="border-color:var(--line); background:var(--cream); color:var(--ink)"></textarea>
                </div>
                <div class="flex justify-end gap-3">
                    <button type="button" id="btn-cancel-edit-category" class="rounded-full px-4 py-2"
                        style="background:var(--blush); color:var(--ink)">Batal</button>
                    <button class="rounded-full px-4 py-2 text-white" style="background:var(--rose-dark)">Perbarui</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Modal Hapus Kategori --}}
    <div id="modal-delete-category" tabindex="-1" aria-hidden="true"
        class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/30 p-4">
        <div class="w-full max-w-sm rounded-3xl border border-white bg-white p-6 text-center shadow-soft">
            <h3 class="font-display text-lg" style="color:var(--ink)">Hapus Kategori?</h3>
            <p class="mt-2 text-sm" style="color:var(--ink-soft)">
                Yakin mau hapus <span id="delete-category-name" class="font-medium"></span>?
                Data ini tidak bisa dikembalikan.
            </p>
            <form id="form-delete-category" method="POST" class="mt-5 flex justify-center gap-3">
                @csrf
                @method('DELETE')
                <button type="button" id="btn-cancel-delete-category" class="rounded-full px-4 py-2 text-sm"
                    style="background:var(--blush); color:var(--ink)">Batal</button>
                <button class="rounded-full px-4 py-2 text-sm text-white" style="background:#111111">Hapus</button>
            </form>
        </div>
    </div>

    <script>
        // Dibungkus DOMContentLoaded supaya kode ini baru jalan SETELAH app.js
        // (dimuat via Vite sebagai <script type="module">, otomatis
        // ditunda/deferred oleh browser) selesai dieksekusi dan window.Modal
        // sudah tersedia. Tanpa ini, script di bawah bisa jalan duluan waktu
        // parsing HTML dan "Modal" masih undefined.
        document.addEventListener('DOMContentLoaded', function () {
            // Instance Flowbite Modal dibuat manual (bukan lewat data-modal-toggle) supaya kita bisa
            // matiin backdrop bawaan Flowbite (backdropClasses kosong). Backdrop gelap yang keliatan
            // tetap dari class "bg-black/30" di div modal itu sendiri, jadi tampilan tidak berubah.
            const modalNoBackdropOptions = { backdropClasses: '', closable: true };

            const createCategoryModal = new Modal(document.getElementById('modal-create-category'), modalNoBackdropOptions);
            const editCategoryModal = new Modal(document.getElementById('modal-edit-category'), modalNoBackdropOptions);
            const deleteCategoryModal = new Modal(document.getElementById('modal-delete-category'), modalNoBackdropOptions);

            document.getElementById('btn-open-create-category').addEventListener('click', () => createCategoryModal.show());
            document.getElementById('btn-close-create-category').addEventListener('click', () => createCategoryModal.hide());
            document.getElementById('btn-cancel-create-category').addEventListener('click', () => createCategoryModal.hide());

            document.getElementById('btn-close-edit-category').addEventListener('click', () => editCategoryModal.hide());
            document.getElementById('btn-cancel-edit-category').addEventListener('click', () => editCategoryModal.hide());

            document.getElementById('btn-cancel-delete-category').addEventListener('click', () => deleteCategoryModal.hide());

            function validateNameField(inputId, errorId) {
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

            function clearFieldError(inputId, errorId) {
                const input = document.getElementById(inputId);
                const error = document.getElementById(errorId);
                input.addEventListener('input', function () {
                    if (input.value.trim()) {
                        error.classList.add('hidden');
                        input.style.borderColor = '';
                    }
                });
            }

            clearFieldError('create-category-name', 'create-category-name-error');
            clearFieldError('edit-category-name', 'edit-category-name-error');

            document.getElementById('form-create-category').addEventListener('submit', function (e) {
                if (!validateNameField('create-category-name', 'create-category-name-error')) e.preventDefault();
            });

            document.getElementById('form-edit-category').addEventListener('submit', function (e) {
                if (!validateNameField('edit-category-name', 'edit-category-name-error')) e.preventDefault();
            });

            // Diekspos ke window karena dipanggil dari atribut onclick="" di HTML
            // (baris tabel), yang cuma bisa mencari fungsi di scope global.
            window.openEditCategory = function (id, name, description) {
                document.getElementById('form-edit-category').action = '{{ url('/categories') }}/' + id;
                document.getElementById('edit-category-id').value = id;
                document.getElementById('edit-category-name').value = name;
                document.getElementById('edit-category-description').value = description;
                editCategoryModal.show();
            };

            window.openDeleteCategory = function (id, name) {
                document.getElementById('form-delete-category').action = '{{ url('/categories') }}/' + id;
                document.getElementById('delete-category-name').textContent = name;
                deleteCategoryModal.show();
            };

            @if($errors->any())
                @if(old('category_id'))
                    window.openEditCategory('{{ old('category_id') }}', @json(old('name')), @json(old('description')));
                @else
                    createCategoryModal.show();
                @endif
            @endif
                });
    </script>
@endsection