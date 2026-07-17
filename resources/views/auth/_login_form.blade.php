@php
    // Kalau email sudah pernah diisi (bukan kosong) tapi masih ada error 'email',
    // itu bukan error "wajib diisi" — itu hasil gagal login (email/password salah, atau throttle).
    // Tampilkan pesan itu SATU kali saja di banner atas, jangan dobel di bawah field.
    $loginFailedMessage = filled(old('email')) && $errors->has('email') ? $errors->first('email') : null;
@endphp

<form id="modal-login-form" action="{{ route('login.store') }}" method="POST" class="space-y-6" novalidate>
    @csrf

    @if ($loginFailedMessage)
        <div class="rounded-xl bg-rose/10 px-4 py-3 text-sm text-rose">
            {{ $loginFailedMessage }}
        </div>
    @endif

    {{-- Email --}}
    <div>
        <div
            class="group flex items-center gap-3 border-b-2 pb-2 transition focus-within:border-mauve @error('email') border-rose @else border-blush @enderror">
            <svg class="h-5 w-5 shrink-0 text-ink/35 transition group-focus-within:text-mauve" fill="none"
                viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M2.25 6.75c0-.83.67-1.5 1.5-1.5h16.5c.83 0 1.5.67 1.5 1.5v10.5a1.5 1.5 0 0 1-1.5 1.5H3.75a1.5 1.5 0 0 1-1.5-1.5V6.75Z" />
                <path stroke-linecap="round" stroke-linejoin="round" d="m3 7 8.15 6.11a1.5 1.5 0 0 0 1.8 0L21 7" />
            </svg>
            <input type="email" name="email" id="login-email" placeholder="Email" value="{{ old('email') }}"
                data-server-error="{{ $loginFailedMessage ? '1' : '' }}"
                class="w-full bg-transparent py-1 text-sm text-ink placeholder:text-ink/40 focus:outline-none"
                autofocus>
        </div>
        @unless ($loginFailedMessage)
            <p id="login-email-error" class="mt-1 text-xs text-rose {{ $errors->has('email') ? '' : 'hidden' }}">
                {{ $errors->first('email') }}
            </p>
        @else
            <p id="login-email-error" class="mt-1 hidden text-xs text-rose"></p>
        @endunless
    </div>

    {{-- Password --}}
    <div>
        <div
            class="group flex items-center gap-3 border-b-2 pb-2 transition focus-within:border-mauve @error('password') border-rose @else border-blush @enderror">
            <svg class="h-5 w-5 shrink-0 text-ink/35 transition group-focus-within:text-mauve" fill="none"
                viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a1.5 1.5 0 0 0 1.5-1.5v-8.25a1.5 1.5 0 0 0-1.5-1.5H6.75a1.5 1.5 0 0 0-1.5 1.5v8.25a1.5 1.5 0 0 0 1.5 1.5Z" />
            </svg>
            <input type="password" name="password" id="login-password" placeholder="Password"
                class="w-full bg-transparent py-1 text-sm text-ink placeholder:text-ink/40 focus:outline-none">
        </div>
        <p id="login-password-error" class="mt-1 text-xs text-rose {{ $errors->has('password') ? '' : 'hidden' }}">
            {{ $errors->first('password') }}
        </p>
    </div>

    {{-- Forgot password + submit --}}
    <div class="flex items-center justify-between pt-2">
        @if (Route::has('password.request'))
            <a href="{{ route('password.request') }}" class="text-xs font-medium text-mauve hover:underline">
                Lupa Password?
            </a>
        @else
            <span></span>
        @endif

        <button type="submit"
            class="rounded-full bg-ink px-9 py-2.5 text-sm font-semibold tracking-wide text-white shadow-md transition hover:opacity-90">
            MASUK
        </button>
    </div>
</form>

<script>
    (function () {
        const form = document.getElementById('modal-login-form');
        if (!form) return;

        const emailInput = document.getElementById('login-email');
        const emailError = document.getElementById('login-email-error');
        const passwordInput = document.getElementById('login-password');
        const passwordError = document.getElementById('login-password-error');

        // Kalau field ini sedang menampilkan pesan error dari server (mis. "Email atau
        // password salah"), jangan biarkan validasi format pas blur langsung menghapusnya.
        // Baru dianggap "sudah diperbaiki" kalau usernya benar-benar mengetik ulang.
        let emailServerErrorActive = emailInput.dataset.serverError === '1';
        emailInput.addEventListener('input', function () {
            emailServerErrorActive = false;
        });

        function showError(input, errorEl, message) {
            const box = input.closest('.group');
            box.classList.remove('border-blush');
            box.classList.add('border-rose');
            errorEl.textContent = message;
            errorEl.classList.remove('hidden');
        }

        function clearError(input, errorEl) {
            const box = input.closest('.group');
            box.classList.remove('border-rose');
            box.classList.add('border-blush');
            errorEl.classList.add('hidden');
        }

        function validateEmail() {
            if (emailServerErrorActive) {
                // Pesan server (banner di atas) yang berlaku, biarkan tetap terlihat.
                return false;
            }
            const val = emailInput.value.trim();
            if (!val) {
                showError(emailInput, emailError, 'Email wajib diisi.');
                return false;
            }
            const pattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!pattern.test(val)) {
                showError(emailInput, emailError, 'Format email tidak valid.');
                return false;
            }
            clearError(emailInput, emailError);
            return true;
        }

        function validatePassword() {
            if (!passwordInput.value) {
                showError(passwordInput, passwordError, 'Password wajib diisi.');
                return false;
            }
            clearError(passwordInput, passwordError);
            return true;
        }

        emailInput.addEventListener('blur', validateEmail);
        passwordInput.addEventListener('blur', validatePassword);

        form.addEventListener('submit', function (e) {
            // Kalau masih ada error server yang belum "disentuh", biarkan user membetulkan
            // dulu (jangan submit ulang membawa nilai lama yang sama).
            if (emailServerErrorActive) {
                e.preventDefault();
                emailInput.focus();
                return;
            }
            const okEmail = validateEmail();
            const okPassword = validatePassword();
            if (!okEmail || !okPassword) {
                e.preventDefault();
            }
        });
    })();
</script>