<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class SuperController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();
        // Jika belum login atau role salah, redirect ke login
        if (! $user || $user->role_id !== 1) {
            return redirect()->route('login');
        }

        return view('dashboards.super');
    }
}
