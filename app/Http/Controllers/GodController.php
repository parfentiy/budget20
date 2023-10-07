<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use \App\Models\Account;
use App\Models\User;

class GodController extends Controller
{
    public function showUsers() {

        return view('admin.users', [
            'users' => User::all(),
        ]);
    }

    public function changeBanStatus() {
        $this->isBanned(request()->userId) ? $this->unban(request()->userId) : $this->ban(request()->userId);

        return view('admin.users', [
            'users' => User::all(),
        ]);
    }

    public function deleteUser() {
        User::find(request()->userId)->delete();

        return view('admin.users', [
            'users' => User::all(),
        ]);
    }

    private function ban($userId) // Заблокировать пользователя
    {
        $ban_permanently = 0;
        $user = User::whereId((int)$userId)->first();
        $user->banned_till = $ban_permanently;
        $user->save();
    }

    private function unban($userId)
    {
        $user = User::whereId((int)$userId)->first();
        $user->banned_till = null;
        $user->save();
    }

    private function isBanned($userId)
    {
        $user = User::whereId($userId)->first();
        if ($user->banned_till != null) {
            if ($user->banned_till == 0) {
                $message = "Banned Permanently";
            }
            if (now()->lessThan($user->banned_till)) {
                $banned_days = now()->diffInDays($user->banned_till) + 1;
                //$message = "Suspended for " . $banned_days . ' ' . Str::plural('day', $banned_days);
            }

            return true;
        } else {

            return false;
        }
    }


}
