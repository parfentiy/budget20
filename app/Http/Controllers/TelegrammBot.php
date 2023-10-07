<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TelegrammBot extends Controller
{
    //
    static public function sendMessage($message) {

        $userSetting = Setting::where('user_id', Auth::user()->id)->first();
        if ($userSetting->is_tbot_active) {
            //ID канала куда отправляем
            //$idChannel = '-1001588576570';
            $idChannel = $userSetting->tbot_channel_id;
            //токен бота которым отправляем сообщение
            //$botToken = '5649872138:AAEH1o1FSuJfjqwvbavQLOd8Bzpr3UICL3w';
            $botToken = $userSetting->tbot_token;
            //кодируем его, чтобы сохранить переносы строк
            $message = urlencode($message);
            //после этого отправляем
            try {
                file_get_contents("https://api.telegram.org/bot$botToken/sendMessage?chat_id=$idChannel&text=" . $message);
            } catch (\Exception $e) {

            }
        }
        return;
    }
}
