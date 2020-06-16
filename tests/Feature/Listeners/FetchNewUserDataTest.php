<?php

namespace Tests\Feature\Listeners;

use Tests\TestCase;
use App\Jobs\FetchLikes;
use App\Jobs\FetchFollowers;
use App\Jobs\FetchFollowings;
use App\Jobs\FetchMutedUsers;
use App\Jobs\FetchBlockedUsers;
use Tests\Concerns\CreatesUser;
use Illuminate\Support\Facades\Bus;
use Illuminate\Auth\Events\Registered;

class FetchNewUserDataTest extends TestCase
{
    use CreatesUser;

    /** @test */
    public function jobs_are_dispatched_after_registration()
    {
        Bus::fake();

        event(new Registered($user = $this->createUser()));

        Bus::assertDispatched(
            fn (FetchBlockedUsers $j) => $j->user->id === $user->id
        );

        Bus::assertDispatched(
            fn (FetchFollowers $j) => $j->user->id === $user->id
        );

        Bus::assertDispatched(
            fn (FetchFollowings $j) => $j->user->id === $user->id
        );

        Bus::assertDispatched(
            fn (FetchLikes $j) => $j->user->id === $user->id
        );

        Bus::assertDispatched(
            fn (FetchMutedUsers $j) => $j->user->id === $user->id
        );
    }
}
