<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use Illuminate\Http\Request;

class ListLikesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function __invoke(Request $request) : View
    {
        return view('likes')->with([
            'likes' => $request->user()->likes()->orderBy('id', 'desc')->paginate(30),
        ]);
    }
}
