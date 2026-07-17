@extends('layouts.app')

@section('content')
    <div class="mx-auto max-w-2xl rounded-3xl border border-white/70 bg-white/80 p-6 shadow-soft">
        <h2 class="font-display text-2xl" style="color:var(--ink)">Edit Pengguna</h2>
        <form action="{{ route('users.update', $user) }}" method="POST" class="mt-6 space-y-4">
            @csrf
            @method('PUT')
            <div>
                <label class="mb-1 block text-sm font-medium" style="color:var(--ink)">Nama</label>
                <input id="user-edit-name" name="name" value="{{ old('name', $user->name) }}" maxlength="100"
                    data-limit-msg="Nama maksimal 100 karakter." class="w-full rounded-2xl border px-4 py-3"
                    style="border-color:var(--line); background:var(--cream); color:var(--ink)" required>
                <div class="mt-1 flex justify-end">
                    <span id="user-edit-name-count" class="text-xs" style="color:var(--ink-soft)"></span>
                </div>
            </div>
            <div>
                <label class="mb-1 block text-sm font-medium" style="color:var(--ink)">Email</label>
                <input id="user-edit-email" type="email" name="email" value="{{ old('email', $user->email) }}"
                    maxlength="50" data-limit-msg="Email maksimal 50 karakter." class="w-full rounded-2xl border px-4 py-3"
                    style="border-color:var(--line); background:var(--cream); color:var(--ink)" required>
                <div class="mt-1 flex justify-end">
                    <span id="user-edit-email-count" class="text-xs" style="color:var(--ink-soft)"></span>
                </div>
            </div>
            <div>
                <label class="mb-1 block text-sm font-medium" style="color:var(--ink)">Password Baru</label>
                <input type="password" name="password" class="w-full rounded-2xl border px-4 py-3"
                    style="border-color:var(--line); background:var(--cream); color:var(--ink)"
                    placeholder="Kosongkan kalau tidak ingin diganti">
            </div>
            <div>
                <label class="mb-1 block text-sm font-medium" style="color:var(--ink)">Role</label>
                <select name="role" class="w-full rounded-2xl border px-4 py-3"
                    style="border-color:var(--line); background:var(--cream); color:var(--ink)" required>
                    <option value="Admin" @selected(old('role', $user->role) === 'Admin')>Admin</option>
                    <option value="Manajer Gudang" @selected(old('role', $user->role) === 'Manajer Gudang')>Manajer Gudang
                    </option>
                    <option value="Staff Gudang" @selected(old('role', $user->role) === 'Staff Gudang')>Staff Gudang</option>
                </select>
            </div>
            @if($errors->any())
                <div class="rounded-2xl px-4 py-3 text-sm" style="background:#FBE0E6; color:#B3455A">
                    <ul class="list-inside list-disc space-y-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <div class="flex justify-end gap-3">
                <a href="{{ route('users.index') }}" class="rounded-full px-4 py-2"
                    style="background:var(--blush); color:var(--ink)">Batal</a>
                <button class="rounded-full px-4 py-2 text-white" style="background:var(--rose-dark)">Perbarui</button>
            </div>
        </form>
    </div>

    <script>
        function bindCounter(inputId, countId, max) {
            const input = document.getElementById(inputId);
            const count = document.getElementById(countId);
            function update() {
                const len = input.value.length;
                count.textContent = len + '/' + max;
                count.style.color = len >= max ? '#B3455A' : 'var(--ink-soft)';
            }
            update();
            input.addEventListener('input', update);
        }
        bindCounter('user-edit-name', 'user-edit-name-count', 100);
        bindCounter('user-edit-email', 'user-edit-email-count', 50);
    </script>
@endsection