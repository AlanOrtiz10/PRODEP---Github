<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class LoginController extends Controller
{
    public function show()
    {
        return view('admin.pages.auth.login');
    }

    public function login(LoginRequest $request)
{
    $credentials = $request->getCredentials();

    if (!Auth::attempt($credentials)) {
        Log::info('Authentication failed for user: ' . $request->input('correo_curp'));
        return redirect()->route('login')->withErrors(['auth.failed']);
    }

    Log::info('User authenticated: ' . Auth::user()->email);
    return redirect()->route('admin.pages.dashboard.index');
}
}
