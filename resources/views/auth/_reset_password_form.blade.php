@php
    // Controller AuthController@showResetPasswordForm mengirim $token dan $email langsung.
    $resetToken = old('token', $token ?? '');
    $resetEmail = old('email', $email ?? '');
@endphp

<form id="reset-password-form" method="POST" action="{{ route('password.update') }}" class="space-y-6" novalidate>
    @csrf

    <input type="hidden" name="token" value="{{ $resetToken }}">

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
            <input type="email" name="email" id="reset-email" placeholder="Email" value="{{ $resetEmail }}"
                class="w-full bg-transparent py-1 text-sm text-ink placeholder:text-ink/40 focus:outline-none"
                autofocus>
        </div>
        <p id="reset-email-error" class="mt-1 text-xs text-rose {{ $errors->has('email') ? '' : 'hidden' }}">
            {{ $errors->first('email') }}
        </p>
    </div>

    {{-- Password baru --}}
    <div>
        <div
            class="group flex items-center gap-3 border-b-2 pb-2 transition focus-within:border-mauve @error('password') border-rose @else border-blush @enderror">
            <svg class="h-5 w-5 shrink-0 text-ink/35 transition group-focus-within:text-mauve" fill="none"
                viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a1.5 1.5 0 0 0 1.5-1.5v-8.25a1.5 1.5 0 0 0-1.5-1.5H6.75a1.5 1.5 0 0 0-1.5 1.5v8.25a1.5 1.5 0 0 0 1.5 1.5Z" />
            </svg>
            <input type="password" name="password" id="reset-password" placeholder="Password Baru"
                class="w-full bg-transparent py-1 text-sm text-ink placeholder:text-ink/40 focus:outline-none">
        </div>
        <p id="reset-password-error" class="mt-1 text-xs text-rose {{ $errors->has('password') ? '' : 'hidden' }}">
            {{ $errors->first('password') }}
        </p>
    </div>

    {{-- Konfirmasi password --}}
    <div>
        <div class="group flex items-center gap-3 border-b-2 border-blush pb-2 transition focus-within:border-mauve">
            <svg class="h-5 w-5 shrink-0 text-ink/35 transition group-focus-within:text-mauve" fill="none"
                viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
            </svg>
            <input type="password" name="password_confirmation" id="reset-password-confirmation"
                placeholder="Konfirmasi Password Baru"
                class="w-full bg-transparent py-1 text-sm text-ink placeholder:text-ink/40 focus:outline-none">
        </div>
        <p id="reset-password-confirmation-error" class="mt-1 hidden text-xs text-rose"></p>
    </div>

    <button type="submit"
        class="w-full rounded-full bg-ink px-9 py-3 text-sm font-semibold tracking-wide text-white shadow-md transition hover:opacity-90">
        SIMPAN PASSWORD BARU
    </button>
</form>

<script>
    (function () {
        const form = document.getElementById('reset-password-form');
        if (!form) return;

        const passwordInput = document.getElementById('reset-password');
        const passwordError = document.getElementById('reset-password-error');
        const confirmInput = document.getElementById('reset-password-confirmation');
        const confirmError = document.getElementById('reset-password-confirmation-error');

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

        function validatePassword() {
            if (!passwordInput.value) {
                showError(passwordInput, passwordError, 'Password wajib diisi.');
                return false;
            }
            if (passwordInput.value.length < 8) {
                showError(passwordInput, passwordError, 'Password minimal 8 karakter.');
                return false;
            }
            clearError(passwordInput, passwordError);
            return true;
        }

        function validateConfirm() {
            if (!confirmInput.value) {
                showError(confirmInput, confirmError, 'Konfirmasi password wajib diisi.');
                return false;
            }
            if (confirmInput.value !== passwordInput.value) {
                showError(confirmInput, confirmError, 'Konfirmasi password tidak sama.');
                return false;
            }
            clearError(confirmInput, confirmError);
            return true;
        }

        passwordInput.addEventListener('blur', validatePassword);
        confirmInput.addEventListener('blur', validateConfirm);

        form.addEventListener('submit', function (e) {
            const okPassword = validatePassword();
            const okConfirm = validateConfirm();
            if (!okPassword || !okConfirm) {
                e.preventDefault();
            }
        });
    })();
</script>