<?php

namespace App\Http\Controllers;

class HomeController extends Controller
{
    public function __invoke() : mixed
    {
        if (auth()->check()) {
            return redirect()->route('overview');
        }

        return view('home');
    }
}
