<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;
use App\Facades\Twitter;
use App\Jobs\FetchMutedUsers;

class FetchMutedUsersTest extends TestCase
{
    /** @test */
    public function it_fetches_muted_users() : void
    {
        Twitter::shouldReceive('setOauthToken')
            ->shouldReceive('get')
            ->with('mutes/users/ids', ['cursor' => -1])
            ->andReturn(
                $data = json_decode(file_get_contents(database_path('twitter/json/muted-ids.json')))
            )
            ->shouldReceive('getLastHttpCode')
            ->andReturn(200)
            ->shouldReceive('get')
            ->with('users/lookup', ['user_id' => implode(',', (array) $data->ids)])
            ->andReturn(
                json_decode(file_get_contents(database_path('twitter/json/users-lookup.json')))
            )
            ->shouldReceive('getLastHttpCode')
            ->andReturn(200)
            ->shouldReceive('get')
            ->with('friendships/lookup', ['user_id' => implode(',', (array) $data->ids)])
            ->andReturn(
                json_decode(file_get_contents(database_path('twitter/json/friendships-lookup.json')))
            )
            ->shouldReceive('getLastHttpCode')
            ->andReturn(200);

        $user = factory(User::class)->create();

        $this->assertCount(0, $user->muted);

        FetchMutedUsers::dispatchNow($user);

        $this->assertCount(5, $user->muted);

        foreach ($user->muted as $mutedUser) {
            $this->assertArrayHasKey('connections', $mutedUser);
        }
    }
}