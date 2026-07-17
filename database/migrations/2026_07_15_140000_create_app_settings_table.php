<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tabel single-row: cuma bakal ada 1 baris (id = 1) buat nyimpen
        // pengaturan umum aplikasi. Dibuat tabel tersendiri (bukan .env atau
        // config file) supaya bisa diubah Admin langsung lewat halaman web.
        Schema::create('app_settings', function (Blueprint $table) {
            $table->id();
            $table->string('app_name')->default('Stockify');
            $table->string('logo_path')->nullable();
            $table->unsignedInteger('default_minimum_stock')->default(5);
            $table->timestamps();
        });

        // Seed baris default supaya service tinggal update, nggak perlu cek create-or-update tiap saat.
        \DB::table('app_settings')->insert([
            'app_name' => 'Stockify',
            'logo_path' => null,
            'default_minimum_stock' => 5,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('app_settings');
    }
};
