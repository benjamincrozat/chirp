<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class OverviewController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function __invoke() : View
    {
        return view('overview');
    }
}
