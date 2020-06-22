<?php

namespace Tests\Feature\Controllers;

use Tests\TestCase;
use App\Jobs\FetchMutedUsers;
use Tests\Concerns\CreatesUser;

class MutedControllerTest extends TestCase
{
    use CreatesUser;

    /** @test */
    public function guests_cannot_access_muted_users_page()
    {
        $this
            ->getJson(route('muted'))
            ->assertStatus(401)
        ;
    }

    /** @test */
    public function muted_users_are_listed() : void
    {
        FetchMutedUsers::dispatch($user = $this->createUser());

        $response = $this
            ->actingAs($user = $user->fresh())
            ->getJson(route('muted'))
            ->assertOk()
        ;

        $response
            ->assertView()
            ->contains(number_format($user->muted_count) . ' muted users')
        ;

        $this->assertEquals(30, $response->original->mutedUsers->perPage());
        $this->assertEquals($user->muted_count, $response->original->mutedUsers->total());
    }
}
