<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use Illuminate\Http\Request;

class BlockedController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function __invoke(Request $request) : View
    {
        return view('blocked')->with([
            'blocked_users' => $request->user()->blocked()->paginate(30),
        ]);
    }
}
