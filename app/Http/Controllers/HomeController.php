<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class HomeController extends Controller
{
    public function __invoke(Request $request)
    {
        if (Auth::check()) {
            return Redirect::route('overview');
        }

        return view('home');
    }
}
