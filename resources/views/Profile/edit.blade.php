@extends('layouts.app')

@section('title', 'Profil Saya')

@section('content')
    <div class="mx-auto max-w-2xl space-y-6">
        <div>
            <h1 class="font-display text-3xl" style="color:var(--ink)">Profil Saya</h1>
            <p class="mt-1 text-sm" style="color:var(--ink-soft)">Kelola informasi akun dan password kamu sendiri.</p>
        </div>

        @if(session('success'))
            <div class="rounded-2xl px-4 py-3 text-sm" style="background:#DCFCE7; color:#166534">
                {{ session('success') }}
            </div>
        @endif

        {{-- Tab switcher --}}
        <div class="flex gap-2 rounded-full border border-white/70 bg-white/80 p-1.5 shadow-soft" role="tablist">
            <button type="button" id="tab-btn-akun" onclick="stockifyProfileTab('akun')"
                class="profile-tab-btn flex-1 rounded-full px-4 py-2.5 text-sm font-medium transition"
                style="background:var(--rose-dark); color:#fff">
                Informasi Akun
            </button>
            <button type="button" id="tab-btn-password" onclick="stockifyProfileTab('password')"
                class="profile-tab-btn flex-1 rounded-full px-4 py-2.5 text-sm font-medium transition"
                style="background:transparent; color:var(--ink)">
                Ganti Password
            </button>
        </div>

        {{-- Info profil --}}
        <div id="tab-panel-akun" class="rounded-3xl border border-white/70 bg-white/80 p-6 shadow-soft">
            <h2 class="font-display text-xl" style="color:var(--ink)">Informasi Akun</h2>
            <p class="mt-1 text-sm" style="color:var(--ink-soft)">Perbarui foto, nama, dan email akun kamu.</p>
            <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="mt-5 space-y-4">
                @csrf
                @method('PUT')

                <div class="flex items-center gap-4">
                    <div class="flex h-16 w-16 shrink-0 items-center justify-center overflow-hidden rounded-full border bg-white font-display text-xl text-white"
                        style="border-color:var(--line); background:var(--rose-dark)">
                        @if($user->photo)
                            <img src="{{ asset('storage/' . $user->photo) }}" alt="Foto profil"
                                class="h-full w-full object-cover">
                        @else
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        @endif
                    </div>
                    <div class="flex-1">
                        <label class="mb-1 block text-sm font-medium" style="color:var(--ink)">Foto Profil</label>
                        <input type="file" name="photo" accept="image/png,image/jpeg,image/webp"
                            class="block w-full cursor-pointer rounded-2xl border text-sm file:mr-4 file:cursor-pointer file:rounded-full file:border-0 file:bg-[var(--rose-dark)] file:px-4 file:py-2.5 file:text-sm file:font-medium file:text-white"
                            style="border-color:var(--line); background:var(--cream); color:var(--ink)">
                        @error('photo')
                            <p class="mt-1 text-xs" style="color:#B3455A">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label class="mb-1 block text-sm font-medium" style="color:var(--ink)">Nama</label>
                    <input name="name" value="{{ old('name', $user->name) }}" class="w-full rounded-2xl border px-4 py-3"
                        style="border-color:var(--line); background:var(--cream); color:var(--ink)" required>
                    @error('name')
                        <p class="mt-1 text-xs" style="color:#B3455A">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="mb-1 block text-sm font-medium" style="color:var(--ink)">Email</label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}"
                        class="w-full rounded-2xl border px-4 py-3"
                        style="border-color:var(--line); background:var(--cream); color:var(--ink)" required>
                    @error('email')
                        <p class="mt-1 text-xs" style="color:#B3455A">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="mb-1 block text-sm font-medium" style="color:var(--ink)">Role</label>
                    <input value="{{ $user->role }}" disabled class="w-full rounded-2xl border px-4 py-3 opacity-60"
                        style="border-color:var(--line); background:var(--blush); color:var(--ink)">
                    <p class="mt-1 text-xs" style="color:var(--ink-soft)">Role cuma bisa diubah Admin lewat menu
                        Pengguna.</p>
                </div>

                <div class="flex justify-end border-t pt-5" style="border-color:var(--line)">
                    <button class="rounded-full px-5 py-2.5 text-sm font-medium text-white"
                        style="background:var(--rose-dark)">Simpan Perubahan</button>
                </div>
            </form>
        </div>

        {{-- Ganti password --}}
        <div id="tab-panel-password" class="hidden rounded-3xl border border-white/70 bg-white/80 p-6 shadow-soft">
            <h2 class="font-display text-xl" style="color:var(--ink)">Ganti Password</h2>
            <p class="mt-1 text-sm" style="color:var(--ink-soft)">Lupa password lama? Masukkan password saat ini lalu
                atur password baru di bawah ini.</p>
            <form action="{{ route('profile.password') }}" method="POST" class="mt-5 space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <label class="mb-1 block text-sm font-medium" style="color:var(--ink)">Password Lama</label>
                    <input type="password" name="current_password" class="w-full rounded-2xl border px-4 py-3"
                        style="border-color:var(--line); background:var(--cream); color:var(--ink)" required>
                    @error('current_password')
                        <p class="mt-1 text-xs" style="color:#B3455A">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="mb-1 block text-sm font-medium" style="color:var(--ink)">Password Baru</label>
                    <input type="password" name="password" class="w-full rounded-2xl border px-4 py-3"
                        style="border-color:var(--line); background:var(--cream); color:var(--ink)" required>
                    @error('password')
                        <p class="mt-1 text-xs" style="color:#B3455A">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="mb-1 block text-sm font-medium" style="color:var(--ink)">Konfirmasi Password
                        Baru</label>
                    <input type="password" name="password_confirmation" class="w-full rounded-2xl border px-4 py-3"
                        style="border-color:var(--line); background:var(--cream); color:var(--ink)" required>
                </div>

                <div class="flex justify-end border-t pt-5" style="border-color:var(--line)">
                    <button class="rounded-full px-5 py-2.5 text-sm font-medium text-white"
                        style="background:var(--rose-dark)">Ubah Password</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function stockifyProfileTab(name) {
            const panels = { akun: document.getElementById('tab-panel-akun'), password: document.getElementById('tab-panel-password') };
            const btns = { akun: document.getElementById('tab-btn-akun'), password: document.getElementById('tab-btn-password') };

            Object.keys(panels).forEach((key) => {
                const isActive = key === name;
                panels[key].classList.toggle('hidden', !isActive);
                btns[key].style.background = isActive ? 'var(--rose-dark)' : 'transparent';
                btns[key].style.color = isActive ? '#fff' : 'var(--ink)';
            });

            if (window.history && window.history.replaceState) {
                const url = new URL(window.location.href);
                url.hash = name;
                window.history.replaceState(null, '', url);
            }
        }

        document.addEventListener('DOMContentLoaded', function () {
            const hasPasswordError = {{ ($errors->has('current_password') || $errors->has('password')) ? 'true' : 'false' }};
            const hash = window.location.hash.replace('#', '');
            if (hasPasswordError || hash === 'password') {
                stockifyProfileTab('password');
            } else {
                stockifyProfileTab('akun');
            }
        });
    </script>
@endsection