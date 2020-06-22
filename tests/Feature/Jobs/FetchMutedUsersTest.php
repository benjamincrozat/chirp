<?php

namespace Tests\Feature\Jobs;

use App\Muted;
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

        $affectedRows = $user->muted()->limit(10)->delete();

        $this->assertEquals(
            $countAfterRemoving = $initialCount - $affectedRows,
            $user->muted()->count()
        );

        FetchMutedUsers::dispatch($user);

        $this->assertEquals(
            $countAfterRemoving + $affectedRows,
            $user->muted()->count()
        );
    }

    /** @test */
    public function it_removes_non_existing_muted_users_from_database() : void
    {
        FetchMutedUsers::dispatch($user = $this->createUser());

        $initialCount = $user->muted()->count();

        Muted::create([
            'id'       => 666,
            'user_id'  => $user->id,
            'name'     => 'Homer Simpson',
            'nickname' => 'homersimpson',
            'data'     => ['foo' => 'bar'],
        ]);

        $this->assertEquals($initialCount + 1, $user->muted()->count());

        FetchMutedUsers::dispatch($user);

        $this->assertEquals($initialCount, $user->muted()->count());
    }
}
