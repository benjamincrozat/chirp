<?php

namespace Tests\Feature\Jobs;

use Tests\TestCase;
use App\Jobs\FetchBlockedUsers;
use Tests\Concerns\CreatesUser;

class FetchBlockedUsersTest extends TestCase
{
    use CreatesUser;

    /** @test */
    public function it_saves_blocked_users_in_database() : void
    {
        FetchBlockedUsers::dispatch(
            $user = $this->createUser()
        );

        $this->assertTrue($user->blocked->isNotEmpty());
    }

    /** @test */
    public function it_saves_new_blocked_users_in_database() : void
    {
        FetchBlockedUsers::dispatch(
            $user = $this->createUser()
        );

        $initialCount = $user->blocked()->count();

        $user->blocked()->limit(10)->delete();

        $this->assertEquals(
            $countAfterRemoving = $initialCount - 10,
            $user->blocked()->count()
        );

        FetchBlockedUsers::dispatch($user);

        $this->assertEquals(
            $countAfterRemoving + 10,
            $user->blocked()->count()
        );
    }
}
