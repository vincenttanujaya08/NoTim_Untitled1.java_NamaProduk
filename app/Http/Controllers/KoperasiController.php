<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class KoperasiController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();
        // Jika belum login atau role salah, redirect ke login
        if (! $user || $user->role_id !== 2) {
            return redirect()->route('login');
        }

        return view('dashboards.koperasi');
    }
}
