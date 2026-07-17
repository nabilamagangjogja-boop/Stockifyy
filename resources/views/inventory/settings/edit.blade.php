@extends('layouts.app')

@section('title', 'Pengaturan')

@section('content')
    <div class="mx-auto max-w-2xl">
        <h1 class="font-display text-3xl" style="color:var(--ink)">Pengaturan Aplikasi</h1>
        <p class="mt-1 text-sm" style="color:var(--ink-soft)">Atur identitas aplikasi dan ambang batas stok menipis
            default. Perubahan di sini berlaku untuk semua pengguna.</p>

        @if(session('success'))
            <div class="mt-6 rounded-2xl px-4 py-3 text-sm" style="background:#DCFCE7; color:#166534">
                {{ session('success') }}
            </div>
        @endif

        <div class="mt-6 rounded-3xl border border-white/70 bg-white/80 p-6 shadow-soft">
            <form action="{{ route('settings.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @method('PUT')

                <div>
                    <label class="mb-1 block text-sm font-medium" style="color:var(--ink)">Nama Aplikasi</label>
                    <input id="settings-app-name" name="app_name" value="{{ old('app_name', $setting->app_name) }}"
                        maxlength="50" data-limit-msg="Nama aplikasi maksimal 50 karakter."
                        class="w-full rounded-2xl border px-4 py-3"
                        style="border-color:var(--line); background:var(--cream); color:var(--ink)" required>
                    <div class="mt-1 flex items-center justify-between">
                        <p class="text-xs" style="color:var(--ink-soft)">Muncul di judul tab browser, navbar, dan
                            favicon.</p>
                        <span id="settings-app-name-count" class="ml-2 shrink-0 text-xs"
                            style="color:var(--ink-soft)"></span>
                    </div>
                    @error('app_name')
                        <p class="mt-1 text-xs" style="color:#B3455A">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="mb-1 block text-sm font-medium" style="color:var(--ink)">Logo Aplikasi</label>
                    <div class="flex items-center gap-4">
                        <div class="flex h-16 w-16 shrink-0 items-center justify-center overflow-hidden rounded-2xl border bg-white"
                            style="border-color:var(--line)">
                            <img src="{{ $setting->logo_path ? asset('storage/' . $setting->logo_path) : asset('images/logo.png') }}"
                                alt="Logo saat ini" class="h-full w-full object-contain">
                        </div>
                        <input type="file" name="logo" accept="image/png,image/jpeg,image/webp"
                            class="block w-full cursor-pointer rounded-2xl border text-sm file:mr-4 file:cursor-pointer file:rounded-full file:border-0 file:bg-[var(--rose-dark)] file:px-4 file:py-2.5 file:text-sm file:font-medium file:text-white"
                            style="border-color:var(--line); background:var(--cream); color:var(--ink)">
                    </div>
                    @error('logo')
                        <p class="mt-1 text-xs" style="color:#B3455A">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-xs" style="color:var(--ink-soft)">PNG/JPG/WEBP, maks. 2MB. Kosongkan kalau
                        tidak mau ganti logo.</p>
                </div>

                <div>
                    <label class="mb-1 block text-sm font-medium" style="color:var(--ink)">Ambang Batas Stok Menipis
                        Default</label>
                    <input type="number" min="0" name="default_minimum_stock"
                        value="{{ old('default_minimum_stock', $setting->default_minimum_stock) }}"
                        class="w-full max-w-[180px] rounded-2xl border px-4 py-3"
                        style="border-color:var(--line); background:var(--cream); color:var(--ink)" required>
                    @error('default_minimum_stock')
                        <p class="mt-1 text-xs" style="color:#B3455A">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-xs" style="color:var(--ink-soft)">Dipakai sebagai nilai awal "stok minimum"
                        saat menambah produk baru. Tiap produk tetap bisa diatur sendiri-sendiri di form produk.</p>
                </div>

                <div class="flex justify-end gap-3 border-t pt-5" style="border-color:var(--line)">
                    <button class="rounded-full px-5 py-2.5 text-sm font-medium text-white"
                        style="background:var(--rose-dark)">Simpan Pengaturan</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        (function () {
            const input = document.getElementById('settings-app-name');
            const count = document.getElementById('settings-app-name-count');
            function update() {
                const len = input.value.length;
                count.textContent = len + '/50';
                count.style.color = len >= 50 ? '#B3455A' : 'var(--ink-soft)';
            }
            update();
            input.addEventListener('input', update);
        })();
    </script>
@endsection