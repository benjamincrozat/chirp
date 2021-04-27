<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use Illuminate\Http\Request;

class MutedController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function __invoke(Request $request) : View
    {
        return view('muted')->with([
            'mutedUsers' => $request->user()->muted()->paginate(30),
        ]);
    }
}
