<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use Illuminate\Http\Request;

class LikesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function __invoke(Request $request) : View
    {
        $query = $request->q
            ? $request->user()->favorites()->matching($request->q, $request->sort_by)
            : $request->user()->favorites()->orderBy('id', 'desc');

        return view('likes')
            ->withLikes($query->paginate(30))
            ->withQuery($request->q)
            ->withSortBy($request->sort_by);
    }
}
