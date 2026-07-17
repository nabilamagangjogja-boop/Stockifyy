<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Ganti aturan ON DELETE dari CASCADE jadi RESTRICT untuk relasi-relasi yang
     * datanya "berharga" dan tidak boleh ikut kehapus otomatis:
     *
     * - products.category_id / products.supplier_id
     *   → Kategori/Supplier tidak bisa dihapus kalau masih ada produk yang memakainya.
     *     (Sebelumnya CASCADE: hapus kategori = semua produknya ikut lenyap. Bahaya.)
     *
     * - stock_transactions.product_id / stock_transactions.user_id
     *   → Produk/User tidak bisa dihapus kalau masih punya riwayat transaksi.
     *     (Sebelumnya CASCADE: hapus 1 user = semua riwayat transaksi yang pernah
     *     dia catat/konfirmasi ikut lenyap permanen — sama seperti membakar buku besar.)
     *
     * product_attributes.product_id SENGAJA dibiarkan CASCADE — atribut produk
     * memang tidak punya arti tanpa produk induknya, jadi wajar ikut terhapus.
     */
    public function up(): void
    {
        // --- products ---
        DB::statement('ALTER TABLE products DROP FOREIGN KEY products_category_id_foreign');
        DB::statement('ALTER TABLE products DROP FOREIGN KEY products_supplier_id_foreign');

        DB::statement('
            ALTER TABLE products
            ADD CONSTRAINT products_category_id_foreign
            FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE RESTRICT
        ');
        DB::statement('
            ALTER TABLE products
            ADD CONSTRAINT products_supplier_id_foreign
            FOREIGN KEY (supplier_id) REFERENCES suppliers(id) ON DELETE RESTRICT
        ');

        // --- stock_transactions ---
        DB::statement('ALTER TABLE stock_transactions DROP FOREIGN KEY stock_transactions_product_id_foreign');
        DB::statement('ALTER TABLE stock_transactions DROP FOREIGN KEY stock_transactions_user_id_foreign');

        DB::statement('
            ALTER TABLE stock_transactions
            ADD CONSTRAINT stock_transactions_product_id_foreign
            FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE RESTRICT
        ');
        DB::statement('
            ALTER TABLE stock_transactions
            ADD CONSTRAINT stock_transactions_user_id_foreign
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE RESTRICT
        ');
    }

    public function down(): void
    {
        DB::statement('ALTER TABLE products DROP FOREIGN KEY products_category_id_foreign');
        DB::statement('ALTER TABLE products DROP FOREIGN KEY products_supplier_id_foreign');
        DB::statement('
            ALTER TABLE products
            ADD CONSTRAINT products_category_id_foreign
            FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE
        ');
        DB::statement('
            ALTER TABLE products
            ADD CONSTRAINT products_supplier_id_foreign
            FOREIGN KEY (supplier_id) REFERENCES suppliers(id) ON DELETE CASCADE
        ');

        DB::statement('ALTER TABLE stock_transactions DROP FOREIGN KEY stock_transactions_product_id_foreign');
        DB::statement('ALTER TABLE stock_transactions DROP FOREIGN KEY stock_transactions_user_id_foreign');
        DB::statement('
            ALTER TABLE stock_transactions
            ADD CONSTRAINT stock_transactions_product_id_foreign
            FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
        ');
        DB::statement('
            ALTER TABLE stock_transactions
            ADD CONSTRAINT stock_transactions_user_id_foreign
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
        ');
    }
};
