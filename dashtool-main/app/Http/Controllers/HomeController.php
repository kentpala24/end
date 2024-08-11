<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $data['title'] = 'Inicio';
        $data['tab'] = 'main';
        $data['url'] = '';
        return view('home', $data);
    }

    public function dashboard()
    {
        $data['title'] = 'Inicio';
        $data['tab'] = 'main';
        $data['url'] = '';
        return view('home', $data);
    }
}
