<form id="modal-register-form" action="{{ route('register.store') }}" method="POST" class="space-y-4">
    @csrf
    <div>
        <label class="mb-1 block text-sm font-medium">Nama</label>
        <input name="name" class="w-full rounded-2xl border border-blush bg-cream px-4 py-3" required>
    </div>
    <div>
        <label class="mb-1 block text-sm font-medium">Email</label>
        <input type="email" name="email" class="w-full rounded-2xl border border-blush bg-cream px-4 py-3" required>
    </div>
    <div>
        <label class="mb-1 block text-sm font-medium">Password</label>
        <input type="password" name="password" class="w-full rounded-2xl border border-blush bg-cream px-4 py-3"
            required>
    </div>
    <div>
        <label class="mb-1 block text-sm font-medium">Konfirmasi Password</label>
        <input type="password" name="password_confirmation"
            class="w-full rounded-2xl border border-blush bg-cream px-4 py-3" required>
    </div>
    <div>
        <label class="mb-1 block text-sm font-medium">Role</label>
        <select name="role" class="w-full rounded-2xl border border-blush bg-cream px-4 py-3" required>
            <option value="Admin">Admin</option>
            <option value="Manajer Gudang">Manajer Gudang</option>
            <option value="Staff Gudang">Staff Gudang</option>
        </select>
    </div>
    <button type="submit" class="w-full rounded-full bg-ink px-4 py-3 font-medium text-white">Daftar</button>
</form>