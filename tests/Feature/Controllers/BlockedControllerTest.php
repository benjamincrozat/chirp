<?php

namespace Tests\Feature\Controllers;

use Tests\TestCase;
use App\Models\User;
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
        FetchBlockedUsers::dispatch($this->createUser());

        $response = $this
            ->actingAs($user = User::firstOrFail())
            ->getJson(route('blocked'))
            ->assertOk()
        ;

        $response
            ->assertView()
            ->contains(
                trans_choice(
                    ':formatted blocked user| :formatted blocked users',
                    $user->blocked_count,
                    ['formatted' => number_format($user->blocked_count)]
                )
            )
        ;

        $this->assertEquals(30, $response->original->blockedUsers->perPage());
        $this->assertEquals($user->blocked_count, $response->original->blockedUsers->total());
    }
}
