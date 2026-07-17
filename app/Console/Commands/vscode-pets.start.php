<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class VscodePetsStart extends Command
{
    /**
     * Command yang dijalankan di terminal.
     */
    protected $signature = 'pets:start';

    /**
     * Deskripsi command.
     */
    protected $description = 'Menampilkan pesan bahwa VS Code Pets telah dimulai.';

    /**
     * Jalankan command.
     */
    public function handle(): int
    {
        $this->info('🐱 VS Code Pets berhasil dimulai!');
        $this->line('Catatan: hewan VS Code Pets muncul dari extension VS Code, bukan dari Laravel.');

        return Command::SUCCESS;
    }
}
