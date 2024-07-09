<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Redirect;
use App\Http\Requests\RegisterRequest;
use App\Models\User;

class RegisterController extends Controller
{
    public function show()
    {
        return view('admin.pages.auth.register');
    }

    public function register(RegisterRequest $request)
    {
        $user = User::create($request->validated());

        // Redirigir a la página de inicio de sesión con un mensaje
        return Redirect::to('/login')->with('success', 'Cuenta creada correctamente');
    }
}
