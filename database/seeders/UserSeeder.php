<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Bikin akun Admin pertama secara otomatis.
     * Ini solusi buat masalah "ayam-telur": karena form register publik sekarang
     * cuma bisa bikin akun Staff Gudang (demi keamanan), harus ada satu Admin
     * yang sudah ada dari awal supaya bisa login dan membuat akun lain lewat
     * halaman Kelola Pengguna.
     */
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'admin@stockify.test'],
            [
                'name' => 'Admin Utama',
                'password' => Hash::make('password'),
                'role' => 'Admin',
            ]
        );
    }
}
