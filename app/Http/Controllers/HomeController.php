<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

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
     * Show the application introduction.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function home(Request $request)
    {
        $request->session()->put('break', false); // Put in session that user has not taken a break yet
        return view('home');
    }

    /**
     * Show the application instructions
     *

     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function instructions()
    {
        return view('instructions');
    }
}
