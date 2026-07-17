<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Rapikan panjang kolom string yang sebelumnya semua ikut default
     * VARCHAR(255) walau isinya jauh lebih pendek (contoh: nomor telepon,
     * kode SKU, role). Disesuaikan per kebutuhan data aslinya:
     *
     * - phone        -> 15   (nomor HP Indonesia maksimal ~13-14 digit + kode negara)
     * - email        -> 50   (aman buat sebagian besar alamat email nyata,
     *                         tanpa longgar sampai 255 seperti default)
     * - password     -> 100  (hash bcrypt = 60 karakter tetap, dilebihkan
     *                         dikit buat jaga-jaga kalau algoritma hash diganti)
     * - sku          -> 30   (kode SKU internal, tidak pernah sepanjang itu)
     * - name (produk/kategori/supplier/dll) -> 50-150 sesuai konteks
     * - kolom path file (image/photo/logo)  -> 150
     *
     * Pakai DB::statement (MODIFY COLUMN) alih-alih Schema::table()->change()
     * supaya tidak perlu dependency tambahan doctrine/dbal.
     */
    public function up(): void
    {
        // --- users ---
        DB::statement('ALTER TABLE users MODIFY name VARCHAR(100) NOT NULL');
        DB::statement('ALTER TABLE users MODIFY email VARCHAR(50) NOT NULL');
        DB::statement('ALTER TABLE users MODIFY password VARCHAR(100) NOT NULL');
        DB::statement('ALTER TABLE users MODIFY photo VARCHAR(150) NULL');

        // --- password_reset_tokens ---
        DB::statement('ALTER TABLE password_reset_tokens MODIFY email VARCHAR(50) NOT NULL');
        DB::statement('ALTER TABLE password_reset_tokens MODIFY token VARCHAR(100) NOT NULL');

        // --- categories ---
        DB::statement('ALTER TABLE categories MODIFY name VARCHAR(50) NOT NULL');

        // --- suppliers ---
        DB::statement('ALTER TABLE suppliers MODIFY name VARCHAR(100) NOT NULL');
        DB::statement('ALTER TABLE suppliers MODIFY phone VARCHAR(15) NULL');
        DB::statement('ALTER TABLE suppliers MODIFY email VARCHAR(50) NULL');

        // --- products ---
        DB::statement('ALTER TABLE products MODIFY name VARCHAR(150) NOT NULL');
        DB::statement('ALTER TABLE products MODIFY sku VARCHAR(30) NOT NULL');
        DB::statement('ALTER TABLE products MODIFY image VARCHAR(150) NULL');

        // --- product_attributes ---
        DB::statement('ALTER TABLE product_attributes MODIFY name VARCHAR(50) NOT NULL');
        DB::statement('ALTER TABLE product_attributes MODIFY value VARCHAR(100) NOT NULL');

        // --- activity_logs ---
        DB::statement('ALTER TABLE activity_logs MODIFY user_name VARCHAR(100) NULL');
        DB::statement('ALTER TABLE activity_logs MODIFY user_role VARCHAR(30) NULL');
        DB::statement('ALTER TABLE activity_logs MODIFY action VARCHAR(30) NOT NULL');
        DB::statement('ALTER TABLE activity_logs MODIFY module VARCHAR(30) NOT NULL');

        // --- app_settings ---
        DB::statement('ALTER TABLE app_settings MODIFY app_name VARCHAR(50) NOT NULL DEFAULT \'Stockify\'');
        DB::statement('ALTER TABLE app_settings MODIFY logo_path VARCHAR(150) NULL');
    }

    /**
     * Kembalikan ke VARCHAR(255) seperti semula kalau migration di-rollback.
     */
    public function down(): void
    {
        DB::statement('ALTER TABLE users MODIFY name VARCHAR(255) NOT NULL');
        DB::statement('ALTER TABLE users MODIFY email VARCHAR(255) NOT NULL');
        DB::statement('ALTER TABLE users MODIFY password VARCHAR(255) NOT NULL');
        DB::statement('ALTER TABLE users MODIFY photo VARCHAR(255) NULL');

        DB::statement('ALTER TABLE password_reset_tokens MODIFY email VARCHAR(255) NOT NULL');
        DB::statement('ALTER TABLE password_reset_tokens MODIFY token VARCHAR(255) NOT NULL');

        DB::statement('ALTER TABLE categories MODIFY name VARCHAR(255) NOT NULL');

        DB::statement('ALTER TABLE suppliers MODIFY name VARCHAR(255) NOT NULL');
        DB::statement('ALTER TABLE suppliers MODIFY phone VARCHAR(255) NULL');
        DB::statement('ALTER TABLE suppliers MODIFY email VARCHAR(255) NULL');

        DB::statement('ALTER TABLE products MODIFY name VARCHAR(255) NOT NULL');
        DB::statement('ALTER TABLE products MODIFY sku VARCHAR(255) NOT NULL');
        DB::statement('ALTER TABLE products MODIFY image VARCHAR(255) NULL');

        DB::statement('ALTER TABLE product_attributes MODIFY name VARCHAR(255) NOT NULL');
        DB::statement('ALTER TABLE product_attributes MODIFY value VARCHAR(255) NOT NULL');

        DB::statement('ALTER TABLE activity_logs MODIFY user_name VARCHAR(255) NULL');
        DB::statement('ALTER TABLE activity_logs MODIFY user_role VARCHAR(255) NULL');
        DB::statement('ALTER TABLE activity_logs MODIFY action VARCHAR(255) NOT NULL');
        DB::statement('ALTER TABLE activity_logs MODIFY module VARCHAR(255) NOT NULL');

        DB::statement('ALTER TABLE app_settings MODIFY app_name VARCHAR(255) NOT NULL DEFAULT \'Stockify\'');
        DB::statement('ALTER TABLE app_settings MODIFY logo_path VARCHAR(255) NULL');
    }
};
