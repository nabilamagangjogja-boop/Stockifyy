@extends('layouts.app')

@section('content')
    <div class="rounded-3xl border border-white/70 bg-white/80 p-6 shadow-soft">
        <div class="mb-6 flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-semibold">Kelola Pengguna</h2>
                <p class="text-sm text-ink/70">Atur akun pengguna berdasarkan peran Admin, Manajer Gudang, dan Staff Gudang.
                </p>
            </div>
            <a href="{{ route('users.create') }}"
                class="rounded-full bg-ink px-4 py-2 text-sm font-medium text-white">Tambah Pengguna</a>
        </div>

        <div class="overflow-hidden rounded-2xl border border-blush/60">
            <table class="min-w-full divide-y divide-blush/60">
                <thead class="bg-blush/40">
                    <tr>
                        <th class="px-4 py-3 text-left text-sm font-semibold">Nama</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold">Email</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold">Role</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-blush/40 bg-white/70">
                    @foreach($users as $user)
                        <tr>
                            <td class="px-4 py-3">{{ $user->name }}</td>
                            <td class="px-4 py-3">{{ $user->email }}</td>
                            <td class="px-4 py-3">{{ $user->role }}</td>
                            <td class="px-4 py-3">
                                <a href="{{ route('users.edit', $user) }}"
                                    class="rounded-full bg-rose px-3 py-1 text-sm">Edit</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection