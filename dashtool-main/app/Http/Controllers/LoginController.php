<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function index()
    {
        $data['title'] = 'Inicio de sesión';
        if (auth()) {
            return redirect()->route('home');
        }else{
            return view('auth.login', $data);
        }
    }
}
