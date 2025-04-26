<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class FarmerController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();
        // Jika belum login atau role salah, redirect ke login
        if (! $user || $user->role_id !== 4) {
            return redirect()->route('login');
        }

        return view('dashboards.farmer');
    }
}
