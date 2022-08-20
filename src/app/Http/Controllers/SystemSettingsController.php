<?php

namespace App\Http\Controllers;

use App\Currency;
use App\SystemSetting;
use Illuminate\Http\Request;

class SystemSettingsController extends Controller
{
    public function index()
    {
        $settings = SystemSetting::all();
        return view("settings.index", compact("settings"));
    }

    public function update(Request $request, SystemSetting $setting)
    {
        $this->validate($request, [
            'value'         => 'required',
            'description'   => 'nullable|string'
        ]);

        $setting->update([
            "value"         => $request->value,
            "description"   => $request->description
        ]);

        return redirect()->route("settings");
    }
}
