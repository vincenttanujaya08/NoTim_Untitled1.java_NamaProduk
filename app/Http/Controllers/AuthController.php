<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    /** Tampilkan form login */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /** Proses login manual */
    public function login(Request $request)
    {
        // 1. Validasi input
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        // 2. Cari user berdasar email
        $user = User::where('email', $credentials['email'])->first();

        // 3. Cek eksistensi & password
        if (! $user || ! Hash::check($credentials['password'], $user->password)) {
            return back()
                ->withErrors(['email' => 'Email atau password salah'])
                ->withInput(['email' => $credentials['email']]);
        }

        // 4. Login via Auth (set session)
        Auth::login($user, $request->filled('remember'));
        // Regenerate session ID untuk keamanan
        $request->session()->regenerate();

        // 5. Redirect berdasarkan role_id
        switch ($user->role_id) {
            case 1:
                return redirect()->route('super.dashboard');
            case 2:
                return redirect()->route('koperasi.dashboard');
            case 3:
                return redirect()->route('field.dashboard');
            case 4:
                return redirect()->route('farmer.dashboard');
            case 5:
                return redirect()->route('buyer.dashboard');
            default:
                Auth::logout();
                return redirect()->route('login')
                    ->withErrors(['email' => 'Role tidak dikenali']);
        }
    }

    /** Proses logout */
    public function logout(Request $request)
    {
        Auth::logout();

        // Invalidasi session & token CSRF
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
