<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SettingController extends Controller
{
    //
    public function save() {
        $currentSetting = Setting::where('user_id', Auth::user()->id)->first();
        $user = (int)Auth::user()->id;
        if (is_null($currentSetting)) {
            Setting::create([
                'user_id' => $user,
                'is_tbot_active' => request()->isTBotActive === 'on' ? true : false,
                'tbot_channel_id' => request()->tBotChannel,
                'menu_position' => 'root',
            ]);
        } else {
            $currentSetting->user_id = $user;
            $currentSetting->is_tbot_active = request()->isTBotActive === 'on' ? true : false;
            $currentSetting->tbot_channel_id = request()->tBotChannel;
            $currentSetting->menu_position = 'root';

            $currentSetting->save();
        }

        return view('settings', [
           'settings' => Setting::where('user_id', Auth::user()->id)->first(),
        ]);
    }
}
