<?php

namespace Tests\Feature\Controllers;

use Tests\TestCase;
use App\Jobs\FetchLikes;
use App\Jobs\FetchFollowers;
use App\Jobs\FetchFollowings;
use App\Jobs\FetchMutedUsers;
use App\Jobs\FetchBlockedUsers;
use Tests\Concerns\CreatesUser;

class DeleteUserControllerTest extends TestCase
{
    use CreatesUser;

    /** @test */
    public function guests_cannot_delete_their_account() : void
    {
        $this
            ->getJson(route('settings'))
            ->assertStatus(401)
        ;
    }

    /** @test */
    public function users_can_delete_their_account() : void
    {
        $user = $this->createUser();

        FetchBlockedUsers::dispatch($user);
        FetchFollowers::dispatch($user);
        FetchFollowings::dispatch($user);
        FetchLikes::dispatch($user);
        FetchMutedUsers::dispatch($user);

        $this
            ->actingAs($user)
            ->deleteJson(route('user.delete'))
            ->assertRedirect()
        ;

        $this->assertDatabaseCount('blocked', 0);
        $this->assertDatabaseCount('followers', 0);
        $this->assertDatabaseCount('followings', 0);
        $this->assertDatabaseCount('likes', 0);
        $this->assertDatabaseCount('muted', 0);
        $this->assertDatabaseCount('users', 0);
    }
}
