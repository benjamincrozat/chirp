<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class SearchLikesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function __invoke(Request $request) : mixed
    {
        if (! $request->terms) {
            return redirect()->route('likes.index');
        }

        return view('search')->with([
            'likes'   => $request->user()->likes()->matching($request->terms, $request->sort_by)->paginate(30),
            'sort_by' => $request->sort_by,
            'terms'   => $request->terms,
        ]);
    }
}
