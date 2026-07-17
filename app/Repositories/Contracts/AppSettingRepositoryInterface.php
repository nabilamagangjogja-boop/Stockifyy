<?php

namespace App\Repositories\Contracts;

use App\Models\AppSetting;

interface AppSettingRepositoryInterface
{
    public function current(): AppSetting;

    public function update(array $data): AppSetting;
}
