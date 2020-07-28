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
//        dd( auth()->user()->friends()->pluck('id')->toArray());
//        dd(in_array(1, auth()->user()->friends()->pluck('id')->toArray()));
        return view('home');
    }
}
