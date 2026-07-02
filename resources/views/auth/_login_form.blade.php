<form id="modal-login-form" action="{{ route('login.store') }}" method="POST" class="space-y-4">
    @csrf
    <div>
        <label class="mb-1 block text-sm font-medium">Email</label>
        <input type="email" name="email" class="w-full rounded-2xl border border-blush bg-cream px-4 py-3" required>
    </div>
    <div>
        <label class="mb-1 block text-sm font-medium">Password</label>
        <input type="password" name="password" class="w-full rounded-2xl border border-blush bg-cream px-4 py-3"
            required>
    </div>
    <button type="submit" class="w-full rounded-full bg-ink px-4 py-3 font-medium text-white">Masuk</button>
</form>