<?php

namespace App\Http\Controllers;

use App\Services\AppSettingService;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function __construct(
        protected AppSettingService $appSettingService
    ) {}

    public function edit()
    {
        $setting = $this->appSettingService->current();
        return view('inventory.settings.edit', compact('setting'));
    }

    public function update(Request $request)
    {
        $this->appSettingService->update($request);
        return redirect()->route('settings.edit')->with('success', 'Pengaturan aplikasi berhasil disimpan.');
    }
}
