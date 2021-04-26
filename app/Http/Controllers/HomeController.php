<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function __construct()
    {
        // $this->middleware('guest');
    }

    public function __invoke(Request $request)
    {
        if ($request->user()) {
            return redirect()->route('overview');
        }

        return view('home');
    }
}
