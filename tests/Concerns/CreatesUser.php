<?php

namespace Tests\Concerns;

use App\User;
use App\Jobs\FetchUser;

trait CreatesUser
{
    public function createUser() : User
    {
        $user = $this->factoryUser();

        FetchUser::dispatch($user);

        return $user->fresh();
    }

    public function factoryUser() : User
    {
        return factory(User::class)->create([
            'id'           => config('services.twitter.test_user_id'),
            'name'         => 'Benjamin Crozat',
            'nickname'     => 'benjamincrozat',
            'token'        => config('services.twitter.test_user_token'),
            'token_secret' => config('services.twitter.test_user_token_secret'),
        ]);
    }
}
