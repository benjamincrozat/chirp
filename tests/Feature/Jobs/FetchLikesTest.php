<?php

namespace Tests\Feature\Jobs;

use Tests\TestCase;
use App\Models\Like;
use App\Jobs\FetchLikes;
use Tests\Concerns\CreatesUser;

class FetchLikesTest extends TestCase
{
    use CreatesUser;

    /** @test */
    public function it_saves_likes_in_database() : void
    {
        FetchLikes::dispatch(
            $user = $this->createUser()
        );

        $this->assertTrue($user->likes->isNotEmpty());
    }

    /** @test */
    public function it_saves_new_likes_in_database() : void
    {
        FetchLikes::dispatch($user = $this->createUser());

        $initialCount = $user->likes()->count();

        $user->likes()->limit(10)->delete();

        $this->assertEquals(
            $countAfterRemoving = $initialCount - 10,
            $user->likes()->count()
        );

        FetchLikes::dispatch($user);

        $this->assertEquals(
            $countAfterRemoving + 10,
            $user->likes()->count()
        );
    }

    /** @test */
    public function it_removes_non_existing_likes_from_database() : void
    {
        FetchLikes::dispatch($user = $this->createUser());

        $initialCount = $user->likes()->count();

        Like::create([
            'id'      => 666,
            'user_id' => $user->id,
            'data'    => ['foo' => 'bar'],
        ]);

        $this->assertEquals($initialCount + 1, $user->likes()->count());

        FetchLikes::dispatch($user);

        $this->assertEquals($initialCount, $user->likes()->count());
    }
}
