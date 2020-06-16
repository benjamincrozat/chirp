<?php

namespace Tests\Feature\Controllers;

use Tests\TestCase;
use App\Jobs\FetchFollowers;
use Tests\Concerns\CreatesUser;

class FollowersControllerTest extends TestCase
{
    use CreatesUser;

    /** @test */
    public function guests_cannot_access_followers_page()
    {
        $this
            ->getJson(route('followers'))
            ->assertStatus(401)
        ;
    }

    /** @test */
    public function followers_diffs_are_listed() : void
    {
        FetchFollowers::dispatch($user = $this->createUser());

        $this
            ->actingAs($user)
            ->getJson(route('followers'))
            ->assertOk()
        ;
    }
}
