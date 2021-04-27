<?php

namespace App\Http\Controllers;

use App\Models\Diff;
use Illuminate\View\View;
use Illuminate\Http\Request;

class FollowingsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function __invoke(Request $request) : View
    {
        return view('followings')->with([
            'diffs' => Diff::diffsHistory($request->user()->id, 'followings'),
        ]);
    }
}
