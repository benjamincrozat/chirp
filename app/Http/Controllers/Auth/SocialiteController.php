<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Http\Controllers\Controller;
use Abraham\TwitterOAuth\TwitterOAuth;
use Illuminate\Auth\Events\Registered;
use Laravel\Socialite\Facades\Socialite;

class SocialiteController extends Controller
{
    protected TwitterOAuth $twitter;

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function redirectToProvider() : \Symfony\Component\HttpFoundation\RedirectResponse
    {
        return Socialite::driver('twitter')->redirect();
    }

    public function handleProviderCallback() : \Symfony\Component\HttpFoundation\RedirectResponse
    {
        $abstractUser = Socialite::driver('twitter')->user();

        $user = User::firstOrCreate(['id' => $abstractUser->id], [
            'id'           => $abstractUser->id,
            'name'         => $abstractUser->name,
            'nickname'     => $abstractUser->nickname,
            'token'        => $abstractUser->token,
            'token_secret' => $abstractUser->tokenSecret,
            'data'         => $abstractUser->user,
        ]);

        if ($user->wasRecentlyCreated) {
            event(new Registered($user));
        }

        auth()->login($user, true);

        return redirect()->route('overview');
    }

    public function logout() : \Illuminate\Http\RedirectResponse
    {
        auth()->logout();

        return redirect()->route('home');
    }
}
