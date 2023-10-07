<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class IsUserBanned
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (auth()->check() && auth()->user()->banned_till != null) {
            if (auth()->user()->banned_till === '0') {
                $message = 'Ваш логин временно заблокирован. Обратитесь к администратору сайта.';
            }
            /*if (now()->lessThan(auth()->user()->banned_till)) {
                $banned_days = now()->diffInDays(auth()->user()->banned_till) + 1;
                $message = 'Your account has been suspended for ' . $banned_days . ' ' . Str::plural('day', $banned_days);
            }*/

            auth()->logout();
            return redirect()->route('login')->with('message', $message);
        }

        return $next($request);
    }
}
