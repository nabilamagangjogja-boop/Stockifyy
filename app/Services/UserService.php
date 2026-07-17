<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserService
{
    public function __construct(
        protected UserRepositoryInterface $users,
        protected ActivityLogService $activityLog
    ) {}

    public function paginate(int $perPage = 10): LengthAwarePaginator
    {
        return $this->users->paginate($perPage);
    }

    protected function messages(): array
    {
        return [
            'name.max' => 'Nama maksimal 100 karakter.',
            'email.max' => 'Email maksimal 50 karakter.',
        ];
    }

    public function create(Request $request): User
    {
        $validated = Validator::make($request->all(), [
            'name' => 'required|string|max:100',
            'email' => 'required|email|max:50|unique:users,email',
            'password' => 'required|min:6',
            'role' => 'required|in:Admin,Manajer Gudang,Staff Gudang',
        ], $this->messages())->validate();

        $validated['password'] = Hash::make($validated['password']);

        $user = $this->users->create($validated);
        $this->activityLog->log('create', 'Pengguna', "Membuat akun pengguna \"{$user->name}\" ({$user->role}).");
        return $user;
    }

    public function update(Request $request, User $user): User
    {
        $validated = Validator::make($request->all(), [
            'name' => 'required|string|max:100',
            'email' => 'required|email|max:50|unique:users,email,' . $user->id,
            'role' => 'required|in:Admin,Manajer Gudang,Staff Gudang',
            'password' => 'nullable|min:6',
        ], $this->messages())->validate();

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user = $this->users->update($user, $validated);
        $this->activityLog->log('update', 'Pengguna', "Memperbarui akun pengguna \"{$user->name}\" ({$user->role}).");
        return $user;
    }

    public function delete(User $user): void
    {
        $this->users->delete($user);
        $this->activityLog->log('delete', 'Pengguna', "Menghapus akun pengguna \"{$user->name}\".");
    }

    /**
     * Update profil milik user yang sedang login (nama, email, foto).
     * Beda dari update() di atas: ini dipakai user buat diri sendiri, bukan
     * Admin mengelola user lain, jadi tidak ada field 'role' di sini.
     */
    public function updateOwnProfile(Request $request, User $user): User
    {
        $validated = Validator::make($request->all(), [
            'name' => 'required|string|max:100',
            'email' => 'required|email|max:50|unique:users,email,' . $user->id,
            'photo' => 'nullable|image|mimes:png,jpg,jpeg,webp|max:2048',
        ], $this->messages())->validate();

        $data = [
            'name' => $validated['name'],
            'email' => $validated['email'],
        ];

        if ($request->hasFile('photo')) {
            if ($user->photo && \Illuminate\Support\Facades\Storage::disk('public')->exists($user->photo)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($user->photo);
            }
            $data['photo'] = $request->file('photo')->store('avatars', 'public');
        }

        $user = $this->users->update($user, $data);
        $this->activityLog->log('update', 'Profil', "Memperbarui profil sendiri (\"{$user->name}\").");
        return $user;
    }

    /**
     * Ganti password milik user yang sedang login. Wajib konfirmasi password lama
     * dulu — beda dari update() milik Admin yang bisa reset password user lain
     * tanpa tahu password lamanya.
     */
    public function updateOwnPassword(Request $request, User $user): void
    {
        $validated = Validator::make($request->all(), [
            'current_password' => 'required',
            'password' => 'required|min:6|confirmed',
        ])->validate();

        if (!Hash::check($validated['current_password'], $user->password)) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'current_password' => 'Password lama yang kamu masukkan salah.',
            ]);
        }

        $this->users->update($user, ['password' => Hash::make($validated['password'])]);
        $this->activityLog->log('update', 'Profil', "Mengubah password akun sendiri (\"{$user->name}\").");
    }
}
