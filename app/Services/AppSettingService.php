<?php

namespace App\Services;

use App\Models\AppSetting;
use App\Repositories\Contracts\AppSettingRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class AppSettingService
{
    public function __construct(
        protected AppSettingRepositoryInterface $settings,
        protected ActivityLogService $activityLog
    ) {}

    public function current(): AppSetting
    {
        return $this->settings->current();
    }

    public function update(Request $request): AppSetting
    {
        $validated = Validator::make($request->all(), [
            'app_name' => 'required|string|max:50',
            'default_minimum_stock' => 'required|integer|min:0',
            'logo' => 'nullable|image|mimes:png,jpg,jpeg,webp|max:2048',
        ], [
            'app_name.max' => 'Nama aplikasi maksimal 50 karakter.',
        ])->validate();

        $data = [
            'app_name' => $validated['app_name'],
            'default_minimum_stock' => $validated['default_minimum_stock'],
        ];

        if ($request->hasFile('logo')) {
            $old = $this->settings->current()->logo_path;
            if ($old && Storage::disk('public')->exists($old)) {
                Storage::disk('public')->delete($old);
            }
            $data['logo_path'] = $request->file('logo')->store('branding', 'public');
        }

        $setting = $this->settings->update($data);
        $this->activityLog->log('update', 'Pengaturan', 'Memperbarui pengaturan umum aplikasi (nama/logo/ambang stok minimum).');

        return $setting;
    }
}
