<?php

namespace Tests\Concerns;

use App\Models\User;
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
        return User::factory()->create([
            'id'           => config('services.twitter.test_user_id'),
            'name'         => 'Benjamin Crozat',
            'nickname'     => 'benjamincrozat',
            'token'        => config('services.twitter.test_user_token'),
            'token_secret' => config('services.twitter.test_user_token_secret'),
        ]);
    }
}
