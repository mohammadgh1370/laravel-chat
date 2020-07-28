<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function __construct()
    {
        return $this->middleware('auth');
    }

    public function index()
    {
//        dd(auth()->user()->friends->pluck('id')->toArray());
        return view('chat.index');
    }
}
