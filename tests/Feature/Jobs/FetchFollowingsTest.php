<?php

namespace Tests\Feature\Jobs;

use Tests\TestCase;
use App\Models\Following;
use App\Jobs\FetchFollowings;
use Tests\Concerns\CreatesUser;

class FetchFollowingsTest extends TestCase
{
    use CreatesUser;

    /** @test */
    public function it_saves_followings_in_database() : void
    {
        FetchFollowings::dispatch(
            $user = $this->createUser()
        );

        $this->assertTrue($user->followings->isNotEmpty());
    }

    /** @test */
    public function it_saves_new_followings_in_database() : void
    {
        FetchFollowings::dispatch($user = $this->createUser());

        $initialCount = $user->followings()->count();

        $user->followings()->limit(5)->delete();

        $this->assertEquals(
            $countAfterRemoving = $initialCount - 5,
            $user->followings()->count()
        );

        FetchFollowings::dispatch($user);

        $this->assertEquals(
            $countAfterRemoving + 5,
            $user->followings()->count()
        );

        $diff = $user->diffs()->whereFor('followings')->latest()->first();

        $this->assertCount(5, $diff->additions);
        $this->assertCount(0, $diff->deletions);
    }

    /** @test */
    public function it_removes_non_existing_followings_from_database() : void
    {
        FetchFollowings::dispatch($user = $this->createUser());

        $initialCount = $user->followings()->count();

        Following::create([
            'id'       => 25073877, // This is Trump's ID, ha ha!
            'user_id'  => $user->id,
            'name'     => 'Homer Simpson',
            'nickname' => 'homersimpson',
            'data'     => ['foo' => 'bar'],
        ]);

        $this->assertEquals($initialCount + 1, $user->followings()->count());

        FetchFollowings::dispatch($user);

        $this->assertEquals($initialCount, $user->followings()->count());

        $diff = $user->diffs()->whereFor('followings')->latest()->first();

        $this->assertCount(0, $diff->additions);
        $this->assertCount(1, $diff->deletions);
    }
}
