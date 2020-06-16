<?php

namespace Tests\Feature\Jobs;

use App\Follower;
use Tests\TestCase;
use App\Jobs\FetchFollowers;
use Tests\Concerns\CreatesUser;

class FetchFollowersTest extends TestCase
{
    use CreatesUser;

    /** @test */
    public function it_saves_followers_in_database() : void
    {
        FetchFollowers::dispatch(
            $user = $this->createUser()
        );

        $this->assertTrue($user->followers->isNotEmpty());
    }

    /** @test */
    public function it_saves_new_followers_in_database() : void
    {
        FetchFollowers::dispatch($user = $this->createUser());

        $initialCount = $user->followers()->count();

        $user->followers()->limit(5)->delete();

        $this->assertEquals(
            $countAfterRemoving = $initialCount - 5,
            $user->followers()->count()
        );

        FetchFollowers::dispatch($user);

        $this->assertEquals(
            $countAfterRemoving + 5,
            $user->followers()->count()
        );
    }

    /** @test */
    public function it_removes_non_existing_followers_from_database() : void
    {
        FetchFollowers::dispatch($user = $this->createUser());

        $initialCount = $user->followers()->count();

        Follower::create([
            'id'       => 25073877, // This is Trump's ID, ha ha!
            'user_id'  => $user->id,
            'name'     => 'Homer Simpson',
            'nickname' => 'homersimpson',
            'data'     => ['foo' => 'bar'],
        ]);

        $this->assertEquals($initialCount + 1, $user->followers()->count());

        FetchFollowers::dispatch($user);

        $this->assertEquals($initialCount, $user->followers()->count());
    }
}
