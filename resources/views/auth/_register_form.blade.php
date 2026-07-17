<form id="modal-register-form" action="{{ route('register.store') }}" method="POST" class="space-y-6" novalidate>
    @csrf

    {{-- Nama --}}
    <div>
        <div
            class="group flex items-center gap-3 border-b-2 pb-2 transition focus-within:border-mauve @error('name') border-rose @else border-blush @enderror">
            <svg class="h-5 w-5 shrink-0 text-ink/35 transition group-focus-within:text-mauve" fill="none"
                viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M17.25 21v-1.5a4.5 4.5 0 0 0-4.5-4.5h-3a4.5 4.5 0 0 0-4.5 4.5V21" />
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M12 12a4.5 4.5 0 1 0 0-9 4.5 4.5 0 0 0 0 9Z" />
            </svg>
            <input name="name" id="register-name" placeholder="Nama" value="{{ old('name') }}"
                class="w-full bg-transparent py-1 text-sm text-ink placeholder:text-ink/40 focus:outline-none"
                autofocus>
        </div>
        <p id="register-name-error" class="mt-1 text-xs text-rose {{ $errors->has('name') ? '' : 'hidden' }}">
            {{ $errors->first('name') }}
        </p>
    </div>

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
            <input type="email" name="email" id="register-email" placeholder="Email" value="{{ old('email') }}"
                data-server-error="{{ $errors->has('email') && filled(old('email')) ? '1' : '' }}"
                class="w-full bg-transparent py-1 text-sm text-ink placeholder:text-ink/40 focus:outline-none">
        </div>
        <p id="register-email-error" class="mt-1 text-xs text-rose {{ $errors->has('email') ? '' : 'hidden' }}">
            {{ $errors->first('email') }}
        </p>
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
            <input type="password" name="password" id="register-password" placeholder="Password"
                class="w-full bg-transparent py-1 text-sm text-ink placeholder:text-ink/40 focus:outline-none">
        </div>
        <p id="register-password-error" class="mt-1 text-xs text-rose {{ $errors->has('password') ? '' : 'hidden' }}">
            {{ $errors->first('password') }}
        </p>
    </div>

    {{-- Konfirmasi Password --}}
    <div>
        <div
            class="group flex items-center gap-3 border-b-2 border-blush pb-2 transition focus-within:border-mauve">
            <svg class="h-5 w-5 shrink-0 text-ink/35 transition group-focus-within:text-mauve" fill="none"
                viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
            </svg>
            <input type="password" name="password_confirmation" id="register-password-confirmation"
                placeholder="Konfirmasi Password"
                class="w-full bg-transparent py-1 text-sm text-ink placeholder:text-ink/40 focus:outline-none">
        </div>
        <p id="register-password-confirmation-error" class="mt-1 hidden text-xs text-rose"></p>
    </div>

    {{-- Role dihapus dari form register — role baru selalu "Staff Gudang" secara otomatis.
         Kalau butuh akses lebih tinggi (Admin/Manajer Gudang), harus dinaikkan oleh Admin
         lewat halaman Kelola Pengguna. Ini mencegah siapa pun bisa daftar sendiri jadi Admin. --}}

    <button type="submit"
        class="w-full rounded-full bg-ink px-9 py-3 text-sm font-semibold tracking-wide text-white shadow-md transition hover:opacity-90">
        DAFTAR
    </button>
</form>

<script>
    (function () {
        // ---- Validasi form ----
        const form = document.getElementById('modal-register-form');
        if (!form) return;

        const nameInput = document.getElementById('register-name');
        const nameError = document.getElementById('register-name-error');
        const emailInput = document.getElementById('register-email');
        const emailError = document.getElementById('register-email-error');
        const passwordInput = document.getElementById('register-password');
        const passwordError = document.getElementById('register-password-error');
        const confirmInput = document.getElementById('register-password-confirmation');
        const confirmError = document.getElementById('register-password-confirmation-error');

        // Kalau ada pesan error dari server (mis. "email sudah terdaftar"), jangan biarkan
        // validasi format pas blur langsung menghapusnya sebelum usernya benar-benar mengedit.
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

        function validateName() {
            if (!nameInput.value.trim()) {
                showError(nameInput, nameError, 'Nama wajib diisi.');
                return false;
            }
            clearError(nameInput, nameError);
            return true;
        }

        function validateEmail() {
            if (emailServerErrorActive) {
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

        nameInput.addEventListener('blur', validateName);
        emailInput.addEventListener('blur', validateEmail);
        passwordInput.addEventListener('blur', validatePassword);
        confirmInput.addEventListener('blur', validateConfirm);

        form.addEventListener('submit', function (e) {
            if (emailServerErrorActive) {
                e.preventDefault();
                emailInput.focus();
                return;
            }
            const okName = validateName();
            const okEmail = validateEmail();
            const okPassword = validatePassword();
            const okConfirm = validateConfirm();
            if (!okName || !okEmail || !okPassword || !okConfirm) {
                e.preventDefault();
            }
        });
    })();
</script>