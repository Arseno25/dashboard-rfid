<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateSettingRequest;
use App\Models\Setting;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    public function edit()
    {
        $settings = Setting::query()->pluck('value', 'key')->toArray();

        return view('settings.edit', [
            'settings' => $settings,
        ]);
    }

    public function update(UpdateSettingRequest $request)
    {
        $validated = $request->validated();

        $uploaded = $this->handleUploads($request);
        $payload = array_merge($validated, $uploaded);

        foreach ($payload as $key => $value) {
            Setting::setValue($key, $value);
        }

        return redirect()->route('settings.edit')->with('status', 'Pengaturan berhasil diperbarui.');
    }

    protected function handleUploads(UpdateSettingRequest $request): array
    {
        $uploaded = [];

        if ($request->hasFile('brand_logo')) {
            $uploaded['brand_logo_path'] = $request->file('brand_logo')->storePublicly('settings', ['disk' => 'public']);
        }

        if ($request->hasFile('brand_favicon')) {
            $uploaded['brand_favicon_path'] = $request->file('brand_favicon')->storePublicly('settings', ['disk' => 'public']);
        }

        return $uploaded;
    }
}
