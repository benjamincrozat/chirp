<?php

namespace App\Http\Controllers;

use App\Diff;
use Illuminate\View\View;
use Illuminate\Http\Request;

class FollowersController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function __invoke(Request $request) : View
    {
        return view('followers')->withDiffs(Diff::diffsHistory($user->id, 'followers'));
    }
}
