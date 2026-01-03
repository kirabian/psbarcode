<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CheckLogin
{
    public function handle(Request $request, Closure $next)
    {
        // 1. Cek Apakah Session Login Ada
        if (!Session::has('is_logged_in')) {
            return redirect()->to('/login');
        }

        // 2. Cek Timeout 30 Menit (1800 detik)
        if (time() - Session::get('last_activity') > 1800) {
            Session::flush();
            return redirect()->to('/login')->with('error', 'Sesi berakhir karena tidak ada aktivitas.');
        }

        // Update waktu aktivitas terakhir
        Session::put('last_activity', time());

        return $next($request);
    }
}