<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

class DeleteUserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function __invoke(Request $request) : RedirectResponse
    {
        $request->user()->delete();

        return redirect()->route('home');
    }
}
