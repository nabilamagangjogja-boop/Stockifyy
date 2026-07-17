<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            // unsignedInteger (bukan foreignId/bigint) karena users.id di project ini
            // pakai $table->increments('id') = INT UNSIGNED, bukan BIGINT UNSIGNED.
            $table->unsignedInteger('user_id')->nullable();
            $table->string('user_name')->nullable(); // snapshot nama, tetap kebaca meski user dihapus
            $table->string('user_role')->nullable();
            $table->string('action'); // login, logout, create, update, delete, confirm, reject
            $table->string('module'); // Produk, Kategori, Supplier, Pengguna, Transaksi, Stock Opname, Auth
            $table->text('description');
            $table->timestamp('created_at')->useCurrent();

            // nullOnDelete: log tetap ada walau user penyebabnya dihapus,
            // supaya riwayat aktivitas tidak ikut hilang.
            $table->foreign('user_id')
                ->references('id')->on('users')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};
