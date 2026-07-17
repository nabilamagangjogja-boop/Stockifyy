<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Stock Opname: pencatatan hasil hitung stok fisik dibandingkan stok sistem.
     * system_stock & physical_stock disimpan sebagai "snapshot" pada saat opname
     * dilakukan (bukan dihitung ulang tiap kali ditampilkan), supaya riwayatnya
     * akurat meski stok berubah lagi setelahnya.
     */
    public function up(): void
    {
        Schema::create('stock_opnames', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('product_id');
            $table->unsignedInteger('user_id');
            $table->integer('system_stock');
            $table->integer('physical_stock');
            $table->integer('difference'); // physical_stock - system_stock
            $table->date('opname_date');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->foreign('product_id')->references('id')->on('products')->onDelete('restrict');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('restrict');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_opnames');
    }
};
