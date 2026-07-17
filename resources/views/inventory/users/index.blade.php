@extends('layouts.app')

@section('content')
    <div class="rounded-3xl border border-white/70 bg-white/80 p-6 shadow-soft">
        <div class="mb-6 flex items-center justify-between">
            <div>
                <h2 class="font-display text-2xl" style="color:var(--ink)">Kelola Pengguna</h2>
                <p class="text-sm" style="color:var(--ink-soft)">Atur akun pengguna berdasarkan peran Admin, Manajer Gudang,
                    dan Staff Gudang.</p>
            </div>
            <a href="{{ route('users.create') }}" class="rounded-full px-4 py-2 text-sm font-medium text-white"
                style="background:var(--rose-dark)">Tambah Pengguna</a>
        </div>

        <div class="overflow-hidden rounded-2xl border" style="border-color:var(--line)">
            <table class="min-w-full divide-y" style="border-color:var(--line)">
                <thead style="background:var(--blush)">
                    <tr>
                        <th class="px-4 py-3 text-left text-sm font-semibold" style="color:var(--ink)">Nama</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold" style="color:var(--ink)">Email</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold" style="color:var(--ink)">Role</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold" style="color:var(--ink)">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y bg-white/70" style="border-color:var(--line)">
                    @foreach($users as $user)
                        <tr>
                            <td class="px-4 py-3" style="color:var(--ink)">{{ $user->name }}</td>
                            <td class="px-4 py-3" style="color:var(--ink-soft)">{{ $user->email }}</td>
                            <td class="px-4 py-3" style="color:var(--ink-soft)">{{ $user->role }}</td>
                            <td class="px-4 py-3">
                                <div class="flex gap-2">
                                    <a href="{{ route('users.edit', $user) }}"
                                        class="rounded-full px-3 py-1 text-sm font-medium"
                                        style="background:var(--blush); color:var(--rose-dark)">Edit</a>
                                    @if($user->id !== auth()->id())
                                        <button type="button" onclick='openDeleteUser({{ $user->id }}, @json($user->name))'
                                            class="rounded-full px-3 py-1 text-sm font-medium text-white"
                                            style="background:#111111">Hapus</button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- Modal Hapus Pengguna --}}
    <div id="modal-delete-user" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/30 p-4">
        <div class="w-full max-w-sm rounded-3xl border border-white bg-white p-6 text-center shadow-soft">
            <h3 class="font-display text-lg" style="color:var(--ink)">Hapus Pengguna?</h3>
            <p class="mt-2 text-sm" style="color:var(--ink-soft)">
                Yakin mau hapus akun <span id="delete-user-name" class="font-medium"></span>?
                Data ini tidak bisa dikembalikan.
            </p>
            <form id="form-delete-user" method="POST" class="mt-5 flex justify-center gap-3">
                @csrf
                @method('DELETE')
                <button type="button" onclick="document.getElementById('modal-delete-user').classList.add('hidden')"
                    class="rounded-full px-4 py-2 text-sm" style="background:var(--blush); color:var(--ink)">Batal</button>
                <button class="rounded-full px-4 py-2 text-sm text-white" style="background:#111111">Hapus</button>
            </form>
        </div>
    </div>

    <script>
        function openDeleteUser(id, name) {
            document.getElementById('form-delete-user').action = '{{ url('/users') }}/' + id;
            document.getElementById('delete-user-name').textContent = name;
            document.getElementById('modal-delete-user').classList.remove('hidden');
        }
    </script>
@endsection