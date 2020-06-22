<?php

namespace Tests\Feature\Jobs;

use App\Blocked;
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

        $affectedRows = $user->blocked()->limit(10)->delete();

        $this->assertEquals(
            $countAfterRemoving = $initialCount - $affectedRows,
            $user->blocked()->count()
        );

        FetchBlockedUsers::dispatch($user);

        $this->assertEquals(
            $countAfterRemoving + $affectedRows,
            $user->blocked()->count()
        );
    }

    /** @test */
    public function it_removes_non_existing_blocked_users_from_database() : void
    {
        FetchBlockedUsers::dispatch($user = $this->createUser());

        $initialCount = $user->blocked()->count();

        Blocked::create([
            'id'       => 666,
            'user_id'  => $user->id,
            'name'     => 'Homer Simpson',
            'nickname' => 'homersimpson',
            'data'     => ['foo' => 'bar'],
        ]);

        $this->assertEquals($initialCount + 1, $user->blocked()->count());

        FetchBlockedUsers::dispatch($user);

        $this->assertEquals($initialCount, $user->blocked()->count());
    }
}
