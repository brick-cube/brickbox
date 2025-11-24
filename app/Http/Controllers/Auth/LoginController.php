<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function show()
    {
        $tenant = session('tenant');

        return view('auth.login', [ 
            'tenant' => $tenant,
        ]);
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);

        if (!Auth::attempt($credentials)) {
            return back()->withErrors(['email' => 'Invalid credentials']);
        }

        $user = Auth::user();

        if ($user->hasRole('super_admin')) {
            return redirect('/admin');
        }

        if ($user->hasRole('company_admin')) {
            return redirect('/company');
        }

        return redirect('/');
    }

    public function logout()
    {
        Auth::logout();
        return redirect('/login');
    }
}
