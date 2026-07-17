<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $appSetting->app_name ?? 'Stockify' }} — @yield('title', 'Dashboard')</title>
    <link rel="icon" type="image/png"
        href="{{ ($appSetting && $appSetting->logo_path) ? asset('storage/' . $appSetting->logo_path) : asset('images/logo.png') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,400;9..144,500;9..144,600&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap"
        rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen">
    <div class="mx-auto flex max-w-[1400px] items-start gap-6 p-6">

        {{-- Sidebar --}}
        <aside
            class="sticky top-6 flex w-[240px] shrink-0 flex-col rounded-[28px] bg-white/80 p-5 shadow-soft border border-white">
            <a href="{{ route('dashboard') }}" class="mb-8 flex items-center gap-2 px-2">
                <img src="{{ ($appSetting && $appSetting->logo_path) ? asset('storage/' . $appSetting->logo_path) : asset('images/logo.png') }}"
                    alt="Logo {{ $appSetting->app_name ?? 'Stockify' }}" class="h-7 w-7"
                    onerror="this.style.display='none'">
                <span class="font-display text-xl font-semibold"
                    style="color:var(--rose-dark)">{{ $appSetting->app_name ?? 'Stockify' }}</span>
            </a>

            <nav class="flex flex-col gap-1.5">
                <a href="{{ route('dashboard') }}"
                    class="rail-icon {{ request()->routeIs('dashboard') ? 'active' : '' }}" title="Dashboard">
                    <svg width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="1.8">
                        <path d="M3 10.5 12 3l9 7.5" />
                        <path d="M5 9.5V21h14V9.5" />
                    </svg>
                    <span>Dashboard</span>
                </a>
                @if(in_array(auth()->user()->role, ['Admin', 'Manajer Gudang']))
                    <a href="{{ route('products.index') }}"
                        class="rail-icon {{ request()->routeIs('products.*') ? 'active' : '' }}" title="Produk">
                        <svg width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="1.8">
                            <path d="M21 8 12 3 3 8v8l9 5 9-5V8Z" />
                            <path d="M3 8l9 5 9-5" />
                            <path d="M12 13v8" />
                        </svg>
                        <span>Produk</span>
                    </a>
                @endif
                @if(auth()->user()->role === 'Admin')
                    <a href="{{ route('categories.index') }}"
                        class="rail-icon {{ request()->routeIs('categories.*') ? 'active' : '' }}" title="Kategori">
                        <svg width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="1.8">
                            <rect x="3" y="3" width="8" height="8" rx="2" />
                            <rect x="13" y="3" width="8" height="8" rx="2" />
                            <rect x="3" y="13" width="8" height="8" rx="2" />
                            <rect x="13" y="13" width="8" height="8" rx="2" />
                        </svg>
                        <span>Kategori</span>
                    </a>
                @endif
                @if(in_array(auth()->user()->role, ['Admin', 'Manajer Gudang']))
                    <a href="{{ route('suppliers.index') }}"
                        class="rail-icon {{ request()->routeIs('suppliers.*') ? 'active' : '' }}" title="Supplier">
                        <svg width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="1.8">
                            <path d="M3 7h13l3 5v5h-3" />
                            <circle cx="7.5" cy="17.5" r="1.8" />
                            <circle cx="17.5" cy="17.5" r="1.8" />
                            <path d="M3 7v10h4" />
                        </svg>
                        <span>Supplier</span>
                    </a>
                @endif
                <a href="{{ route('transactions.index') }}"
                    class="rail-icon {{ request()->routeIs('transactions.*') ? 'active' : '' }}" title="Transaksi">
                    <svg width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="1.8">
                        <path d="M7 7h13l-2 5H8" />
                        <path d="M17 17H4l2-5" />
                        <circle cx="8.5" cy="20" r="1.4" />
                        <circle cx="17.5" cy="20" r="1.4" />
                    </svg>
                    <span>Stok</span>
                </a>
                @if(in_array(auth()->user()->role, ['Admin', 'Manajer Gudang']))
                    <a href="{{ route('stock-opname.index') }}"
                        class="rail-icon {{ request()->routeIs('stock-opname.*') ? 'active' : '' }}" title="Stock Opname">
                        <svg width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="1.8">
                            <path d="M9 11l3 3L22 4" />
                            <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11" />
                        </svg>
                        <span>Stock Opname</span>
                    </a>
                @endif
                @if(auth()->check() && auth()->user()->role === 'Admin')
                    <a href="{{ route('users.index') }}"
                        class="rail-icon {{ request()->routeIs('users.*') ? 'active' : '' }}" title="Pengguna">
                        <svg width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="1.8">
                            <circle cx="12" cy="8" r="3.5" />
                            <path d="M5 20c0-4 3-6.5 7-6.5s7 2.5 7 6.5" />
                        </svg>
                        <span>Pengguna</span>
                    </a>
                @endif
                @if(in_array(auth()->user()->role, ['Admin', 'Manajer Gudang']))
                    <a href="{{ route('reports.index') }}"
                        class="rail-icon {{ request()->routeIs('reports.*') ? 'active' : '' }}" title="Laporan">
                        <svg width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="1.8">
                            <path d="M4 19V9M12 19V4M20 19v-6" />
                        </svg>
                        <span>Laporan</span>
                    </a>
                @endif
                <a href="{{ route('profile.edit') }}"
                    class="rail-icon {{ request()->routeIs('profile.*') ? 'active' : '' }}" title="Profil Saya">
                    <svg width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="1.8">
                        <circle cx="12" cy="8" r="3.5" />
                        <path d="M5 20c0-4 3-6.5 7-6.5s7 2.5 7 6.5" />
                        <circle cx="12" cy="12" r="10" stroke-dasharray="0" opacity="0" />
                    </svg>
                    <span>Profil Saya</span>
                </a>
                @if(auth()->check() && auth()->user()->role === 'Admin')
                    <a href="{{ route('settings.edit') }}"
                        class="rail-icon {{ request()->routeIs('settings.*') ? 'active' : '' }}" title="Pengaturan">
                        <svg width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="1.8">
                            <circle cx="12" cy="12" r="3.2" />
                            <path
                                d="M19.4 13a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 1 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V19a2 2 0 1 1-4 0v-.09A1.65 1.65 0 0 0 9 17.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 1 1-2.83-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 1 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 1 1 2.83-2.83l.06.06A1.65 1.65 0 0 0 9 4.6a1.65 1.65 0 0 0 1-1.51V3a2 2 0 1 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 1 1 2.83 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82v0a1.65 1.65 0 0 0 1.51 1H21a2 2 0 1 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z" />
                        </svg>
                        <span>Pengaturan</span>
                    </a>
                @endif
            </nav>

            <a href="{{ route('profile.edit') }}"
                class="mt-4 flex items-center gap-3 rounded-2xl px-3 py-3 transition hover:opacity-80"
                style="background:var(--blush)">
                <div class="flex h-10 w-10 shrink-0 items-center justify-center overflow-hidden rounded-full font-display text-sm text-white"
                    style="background:var(--rose-dark)">
                    @if(auth()->user()->photo)
                        <img src="{{ asset('storage/' . auth()->user()->photo) }}" alt="Foto profil"
                            class="h-full w-full object-cover">
                    @else
                        {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
                    @endif
                </div>
                <div class="min-w-0">
                    <p class="truncate text-sm font-semibold" style="color:var(--ink)">
                        {{ auth()->user()->name ?? 'Admin' }}
                    </p>
                    <p class="truncate text-xs" style="color:var(--ink-soft)">
                        {{ auth()->user()->role ?? 'Staff Gudang' }}
                    </p>
                </div>
            </a>

            <form method="POST" action="{{ route('logout') }}" class="mt-3 border-t pt-3"
                style="border-color:var(--line)">
                @csrf
                <button class="rail-icon w-full" title="Keluar">
                    <svg width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="1.8">
                        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4" />
                        <path d="M16 17l5-5-5-5" />
                        <path d="M21 12H9" />
                    </svg>
                    <span>Keluar</span>
                </button>
            </form>
        </aside>

        {{-- Main --}}
        <main class="min-w-0 flex-1">
            @if(session('success') || session('error') || $errors->any())
                <div id="toast-stack" class="fixed right-6 top-6 z-[1000] flex w-[360px] max-w-[calc(100vw-3rem)] flex-col gap-3">
                    @if(session('success'))
                        <div id="flash-success"
                            class="flash-alert flex items-start gap-3 rounded-2xl border border-white bg-white px-5 py-4 text-sm shadow-soft"
                            style="border-left:4px solid #34A853">
                            <span class="mt-0.5 flex h-8 w-8 shrink-0 items-center justify-center rounded-full"
                                style="background:#E6F4EA">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#34A853"
                                    stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M20 6 9 17l-5-5" />
                                </svg>
                            </span>
                            <div class="min-w-0 flex-1 pt-1">
                                <p class="font-semibold" style="color:var(--ink)">Berhasil</p>
                                <p class="mt-0.5" style="color:var(--ink-soft)">{{ session('success') }}</p>
                            </div>
                            <button type="button" data-dismiss-target="#flash-success" aria-label="Tutup"
                                class="mt-1 shrink-0 rounded-full p-1 transition hover:bg-black/5">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2" style="color:var(--ink-soft)">
                                    <path d="M18 6 6 18" />
                                    <path d="M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    @endif
                    @if(session('error'))
                        <div id="flash-error"
                            class="flash-alert flex items-start gap-3 rounded-2xl border border-white bg-white px-5 py-4 text-sm shadow-soft"
                            style="border-left:4px solid #E0455A">
                            <span class="mt-0.5 flex h-8 w-8 shrink-0 items-center justify-center rounded-full"
                                style="background:#FBE0E6">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#E0455A"
                                    stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M12 9v4" />
                                    <path d="M12 17h.01" />
                                    <path
                                        d="M10.29 3.86 1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0Z" />
                                </svg>
                            </span>
                            <div class="min-w-0 flex-1 pt-1">
                                <p class="font-semibold" style="color:var(--ink)">Terjadi kesalahan</p>
                                <p class="mt-0.5" style="color:var(--ink-soft)">{{ session('error') }}</p>
                            </div>
                            <button type="button" data-dismiss-target="#flash-error" aria-label="Tutup"
                                class="mt-1 shrink-0 rounded-full p-1 transition hover:bg-black/5">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2" style="color:var(--ink-soft)">
                                    <path d="M18 6 6 18" />
                                    <path d="M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    @endif
                    @if($errors->any())
                        <div id="flash-validation"
                            class="flash-alert flex items-start gap-3 rounded-2xl border border-white bg-white px-5 py-4 text-sm shadow-soft"
                            style="border-left:4px solid #E0455A">
                            <span class="mt-0.5 flex h-8 w-8 shrink-0 items-center justify-center rounded-full"
                                style="background:#FBE0E6">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#E0455A"
                                    stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M12 9v4" />
                                    <path d="M12 17h.01" />
                                    <path
                                        d="M10.29 3.86 1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0Z" />
                                </svg>
                            </span>
                            <div class="min-w-0 flex-1 pt-1">
                                <p class="font-semibold" style="color:var(--ink)">Ada isian yang belum sesuai</p>
                                <ul class="mt-1 list-inside list-disc space-y-0.5" style="color:var(--ink-soft)">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                            <button type="button" data-dismiss-target="#flash-validation" aria-label="Tutup"
                                class="mt-1 shrink-0 rounded-full p-1 transition hover:bg-black/5">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2" style="color:var(--ink-soft)">
                                    <path d="M18 6 6 18" />
                                    <path d="M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    @endif
                </div>
                <script>
                    // Tombol tutup toast pakai komponen Dismiss bawaan Flowbite
                    // (data-dismiss-target), sudah otomatis terpasang lewat initFlowbite().
                    // Di sini kita cuma trigger klik yang sama setelah beberapa detik
                    // supaya toast hilang otomatis, tanpa duplikasi logic show/hide.
                    document.querySelectorAll('.flash-alert').forEach((el) => {
                        setTimeout(() => {
                            const closeBtn = el.querySelector('[data-dismiss-target]');
                            if (closeBtn) closeBtn.click();
                        }, 4500);
                    });
                </script>
            @endif
            <script>
                // Pop-up kecil "sudah mencapai batas karakter" untuk semua input
                // yang punya atribut maxlength + data-limit-msg (dipakai di seluruh form).
                document.querySelectorAll('[maxlength][data-limit-msg]').forEach(function (el) {
                    const wrapper = el.closest('div');
                    if (!wrapper) return;
                    wrapper.style.position = 'relative';

                    const popup = document.createElement('div');
                    popup.className = 'limit-popup';
                    popup.textContent = el.dataset.limitMsg;
                    wrapper.appendChild(popup);

                    let hideTimer;
                    el.addEventListener('input', function () {
                        const max = parseInt(el.getAttribute('maxlength'), 10);
                        if (max && el.value.length >= max) {
                            popup.classList.add('show');
                            clearTimeout(hideTimer);
                            hideTimer = setTimeout(() => popup.classList.remove('show'), 2500);
                        } else {
                            popup.classList.remove('show');
                        }
                    });
                    el.addEventListener('blur', function () {
                        popup.classList.remove('show');
                    });
                });
            </script>
            @yield('content')
        </main>
    </div>

    {{-- Lockscreen otomatis setelah 5 menit tidak ada aktivitas --}}
    <div id="lockscreen"
        class="hidden fixed inset-0 z-[999] flex flex-col items-center justify-between overflow-hidden py-16"
        style="background: radial-gradient(circle at 30% 20%, #333333, #0A0A0A 75%);">

        <div class="flex flex-1 flex-col items-center justify-center text-center text-white">
            <p id="lock-date" class="text-lg font-medium opacity-80"></p>
            <p id="lock-clock" class="font-display text-8xl font-semibold tracking-tight"></p>

            <div class="mt-10 flex flex-col items-center">
                <div class="flex h-16 w-16 items-center justify-center overflow-hidden rounded-full font-display text-xl text-white"
                    style="background:var(--rose-dark)">
                    @if(auth()->user()->photo)
                        <img src="{{ asset('storage/' . auth()->user()->photo) }}" alt="Foto profil"
                            class="h-full w-full object-cover">
                    @else
                        {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
                    @endif
                </div>
                <p class="mt-3 text-base font-medium">{{ auth()->user()->name ?? '' }}</p>
                <p class="text-sm opacity-70">{{ auth()->user()->role ?? '' }}</p>
            </div>

            <form id="unlock-form" class="mt-8 flex flex-col items-center gap-2">
                <input type="password" id="unlock-password" placeholder="Masukkan password"
                    class="w-64 rounded-full border-none px-5 py-3 text-center text-sm text-[--ink] outline-none"
                    style="background:rgba(255,255,255,0.92)" autocomplete="current-password">
                <p id="unlock-error" class="hidden text-sm font-medium" style="color:#FFFFFF">Password salah, coba lagi.
                </p>
                <button type="submit" class="mt-2 rounded-full px-6 py-2 text-sm font-medium text-white"
                    style="background:rgba(255,255,255,0.18); border:1px solid rgba(255,255,255,0.4)">
                    Buka Kunci
                </button>
            </form>
        </div>

        <p class="text-xs text-white/60">Stockify terkunci otomatis setelah 5 menit tidak ada aktivitas</p>
    </div>

    <script>
        (function () {
            const IDLE_LIMIT = 5 * 60 * 1000; // 5 menit
            const lockscreen = document.getElementById('lockscreen');
            const unlockForm = document.getElementById('unlock-form');
            const unlockPassword = document.getElementById('unlock-password');
            const unlockError = document.getElementById('unlock-error');
            const lockClock = document.getElementById('lock-clock');
            const lockDate = document.getElementById('lock-date');
            let idleTimer = null;
            let clockInterval = null;

            function showLockscreen() {
                lockscreen.classList.remove('hidden');
                updateClock();
                clockInterval = setInterval(updateClock, 1000);
                unlockPassword.value = '';
                unlockError.classList.add('hidden');
                setTimeout(() => unlockPassword.focus(), 200);
            }

            function hideLockscreen() {
                lockscreen.classList.add('hidden');
                clearInterval(clockInterval);
                resetIdleTimer();
            }

            function updateClock() {
                const now = new Date();
                lockClock.textContent = now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
                lockDate.textContent = now.toLocaleDateString('id-ID', { weekday: 'long', day: 'numeric', month: 'long' });
            }

            function resetIdleTimer() {
                clearTimeout(idleTimer);
                if (lockscreen.classList.contains('hidden')) {
                    idleTimer = setTimeout(showLockscreen, IDLE_LIMIT);
                }
            }

            ['mousemove', 'mousedown', 'keydown', 'scroll', 'touchstart', 'click'].forEach(function (evt) {
                document.addEventListener(evt, resetIdleTimer, { passive: true });
            });

            resetIdleTimer();

            unlockForm.addEventListener('submit', function (e) {
                e.preventDefault();
                fetch('{{ route('unlock') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({ password: unlockPassword.value }),
                })
                    .then(res => res.json().then(data => ({ ok: res.ok, data })))
                    .then(({ ok, data }) => {
                        if (ok && data.success) {
                            hideLockscreen();
                        } else {
                            unlockError.classList.remove('hidden');
                            unlockPassword.value = '';
                            unlockPassword.focus();
                        }
                    })
                    .catch(() => {
                        unlockError.textContent = 'Terjadi kesalahan, coba lagi.';
                        unlockError.classList.remove('hidden');
                    });
            });
        })();
    </script>


</body>

</html>