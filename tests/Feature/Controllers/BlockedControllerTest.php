<?php

namespace Tests\Feature\Controllers;

use Tests\TestCase;
use App\Jobs\FetchBlockedUsers;
use Tests\Concerns\CreatesUser;

class BlockedControllerTest extends TestCase
{
    use CreatesUser;

    /** @test */
    public function guests_cannot_access_blocked_users_page()
    {
        $this
            ->getJson(route('blocked'))
            ->assertStatus(401)
        ;
    }

    /** @test */
    public function blocked_users_are_listed_and_paginated() : void
    {
        FetchBlockedUsers::dispatch(
            $user = $this->createUser()
        );

        $response = $this
            ->actingAs($user)
            ->getJson(route('blocked'))
            ->assertOk();

        $response
            ->assertView()
            ->contains(number_format($user->blocked_count) . ' blocked users')
        ;

        $this->assertGreaterThan($perPage = 30, $response->original->blockedUsers->total());
        $this->assertCount($perPage, $response->original->blockedUsers);
    }
}
