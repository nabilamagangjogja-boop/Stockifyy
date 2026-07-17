<?php

namespace App\Repositories;

use App\Models\AppSetting;
use App\Repositories\Contracts\AppSettingRepositoryInterface;

class AppSettingRepository implements AppSettingRepositoryInterface
{
    /**
     * Selalu ambil/pastikan baris id=1. Tabel ini didesain cuma punya 1 baris.
     */
    public function current(): AppSetting
    {
        return AppSetting::firstOrCreate(['id' => 1], [
            'app_name' => 'Stockify',
            'default_minimum_stock' => 5,
        ]);
    }

    public function update(array $data): AppSetting
    {
        $setting = $this->current();
        $setting->update($data);
        return $setting->fresh();
    }
}
