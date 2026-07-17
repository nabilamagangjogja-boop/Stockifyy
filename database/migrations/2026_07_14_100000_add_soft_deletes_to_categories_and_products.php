<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Kategori & Produk sekarang pakai SOFT DELETE (ditandai nonaktif lewat
     * kolom deleted_at), bukan dihapus permanen dari database.
     *
     * Kenapa: sebelumnya kalau kategori masih dipakai produk, atau produk
     * masih punya riwayat transaksi stok, DB akan menolak hapus (RESTRICT)
     * dan menampilkan error. Dengan soft delete:
     * - Kategori/produk yang "dihapus" langsung hilang dari daftar aktif,
     *   tapi baris datanya tetap ada di DB (bisa dipulihkan kalau perlu).
     * - Riwayat transaksi & laporan lama tetap utuh karena produk yang
     *   direferensikan tidak benar-benar hilang dari tabel products.
     */
    public function up(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('products', function (Blueprint $table) {
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('products', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
};
