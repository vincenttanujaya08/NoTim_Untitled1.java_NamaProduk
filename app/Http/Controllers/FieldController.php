<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class FieldController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();
        if (! $user || $user->role_id !== 3) {
            return redirect()->route('login');
        }

        return view('dashboards.field');
    }
}
