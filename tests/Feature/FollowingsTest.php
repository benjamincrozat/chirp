<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Jobs\FetchFollowings;
use Tests\Concerns\CreatesUser;

class FollowingsTest extends TestCase
{
    use CreatesUser;

    /** @test */
    public function guests_cannot_access_followings_page()
    {
        $this
            ->getJson(route('followings'))
            ->assertStatus(401)
        ;
    }

    /** @test */
    public function followings_diffs_are_listed() : void
    {
        $user = $this->createUser();

        FetchFollowings::dispatch($user);

        $this
            ->actingAs($user)
            ->getJson(route('followings'))
            ->assertOk()
        ;
    }
}
