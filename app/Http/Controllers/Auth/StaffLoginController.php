<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Teacher;
use Illuminate\Support\Facades\Hash;

class StaffLoginController extends Controller
{
    public function showLoginForm()
    {
        if (Auth::check()) {
            return redirect('/dashboard');
        }
        return view('staff-login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'user' => 'required|string',
            'password' => 'required|string',
        ]);

        $staff = Teacher::where('user', $credentials['user'])->first();

        if ($staff && Hash::check($credentials['password'], $staff->password)) {
            Auth::guard('web')->login($staff);
            $request->session()->regenerate();
            
            // Check if using default password
            if (Hash::check('GPC@26', $staff->password)) {
                return redirect('/change-password');
            }
            
            return redirect()->intended('/dashboard');
        }

        return back()->withErrors([
            'user' => 'The provided credentials do not match our records.',
        ])->onlyInput('user');
    }

    public function logout(Request $request)
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/staff-login');
    }
}
