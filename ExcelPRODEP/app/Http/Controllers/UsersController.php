<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    public function form()
    {
        $data = User::with('level')->paginate(10);
    return view('admin.pages.usuarios.index', compact('data'));
    }
}
