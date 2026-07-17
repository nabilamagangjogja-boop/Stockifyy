<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct(
        protected UserService $userService
    ) {}

    public function index()
    {
        $users = $this->userService->paginate(10);
        return view('inventory.users.index', compact('users'));
    }

    public function create()
    {
        return view('inventory.users.create');
    }

    public function store(Request $request)
    {
        $this->userService->create($request);
        return redirect()->route('users.index')->with('success', 'Pengguna berhasil dibuat.');
    }

    public function edit(User $user)
    {
        return view('inventory.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $this->userService->update($request, $user);
        return redirect()->route('users.index')->with('success', 'Pengguna berhasil diperbarui.');
    }

    public function destroy(Request $request, User $user)
    {
        if ($request->user()->id === $user->id) {
            return redirect()->route('users.index')->with('error', 'Kamu tidak bisa menghapus akunmu sendiri.');
        }

        try {
            $this->userService->delete($user);
        } catch (\Illuminate\Database\QueryException $e) {
            return redirect()->route('users.index')
                ->with('error', 'Pengguna "' . $user->name . '" tidak bisa dihapus karena masih punya riwayat transaksi stok yang tercatat atas namanya.');
        }
        return redirect()->route('users.index')->with('success', 'Pengguna berhasil dihapus.');
    }
}
