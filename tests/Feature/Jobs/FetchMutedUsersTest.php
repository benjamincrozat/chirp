<?php

namespace Tests\Feature\Jobs;

use Tests\TestCase;
use App\Jobs\FetchMutedUsers;
use Tests\Concerns\CreatesUser;

class FetchMutedUsersTest extends TestCase
{
    use CreatesUser;

    /** @test */
    public function it_saves_muted_users_in_database() : void
    {
        FetchMutedUsers::dispatch(
            $user = $this->createUser()
        );

        $this->assertTrue($user->muted->isNotEmpty());
    }

    /** @test */
    public function it_saves_new_muted_users_in_database() : void
    {
        FetchMutedUsers::dispatch(
            $user = $this->createUser()
        );

        $initialCount = $user->muted()->count();

        $user->muted()->limit(10)->delete();

        $this->assertEquals(
            $countAfterRemoving = $initialCount - 10,
            $user->muted()->count()
        );

        FetchMutedUsers::dispatch($user);

        $this->assertEquals(
            $countAfterRemoving + 10,
            $user->muted()->count()
        );
    }
}
