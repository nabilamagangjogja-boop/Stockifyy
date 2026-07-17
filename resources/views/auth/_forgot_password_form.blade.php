@php
    $emailFailedMessage = filled(old('email')) && $errors->has('email') ? $errors->first('email') : null;
@endphp

@if (session('status'))
    <div class="mb-6 rounded-xl bg-mauve/10 px-4 py-3 text-sm text-mauve">
        {{ session('status') }}
    </div>
@endif

@if ($emailFailedMessage)
    <div class="mb-6 rounded-xl bg-rose/10 px-4 py-3 text-sm text-rose">
        {{ $emailFailedMessage }}
    </div>
@endif

<form id="forgot-password-form" method="POST" action="{{ route('password.email') }}" class="space-y-6" novalidate>
    @csrf

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
            <input type="email" name="email" id="forgot-email" placeholder="Email" value="{{ old('email') }}"
                data-server-error="{{ $emailFailedMessage ? '1' : '' }}"
                class="w-full bg-transparent py-1 text-sm text-ink placeholder:text-ink/40 focus:outline-none"
                autofocus>
        </div>
        @unless ($emailFailedMessage)
            <p id="forgot-email-error" class="mt-1 text-xs text-rose {{ $errors->has('email') ? '' : 'hidden' }}">
                {{ $errors->first('email') }}
            </p>
        @else
            <p id="forgot-email-error" class="mt-1 hidden text-xs text-rose"></p>
        @endunless
    </div>

    <button type="submit"
        class="w-full rounded-full bg-ink px-9 py-3 text-sm font-semibold tracking-wide text-white shadow-md transition hover:opacity-90">
        KIRIM LINK RESET
    </button>
</form>

<script>
    (function () {
        const form = document.getElementById('forgot-password-form');
        if (!form) return;

        const emailInput = document.getElementById('forgot-email');
        const emailError = document.getElementById('forgot-email-error');

        let serverErrorActive = emailInput.dataset.serverError === '1';
        emailInput.addEventListener('input', function () {
            serverErrorActive = false;
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
            if (serverErrorActive) return false;
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

        emailInput.addEventListener('blur', validateEmail);

        form.addEventListener('submit', function (e) {
            if (serverErrorActive) {
                e.preventDefault();
                emailInput.focus();
                return;
            }
            if (!validateEmail()) {
                e.preventDefault();
            }
        });
    })();
</script>