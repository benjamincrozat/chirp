<?php

namespace Tests\Feature\Controllers;

use Tests\TestCase;
use App\Models\User;
use App\Jobs\FetchLikes;
use Tests\Concerns\CreatesUser;

class ListLikesControllerTest extends TestCase
{
    use CreatesUser;

    /** @test */
    public function guests_cannot_access_likes_page()
    {
        $this
            ->getJson(route('likes.index'))
            ->assertStatus(401)
        ;
    }

    /** @test */
    public function likes_are_listed() : void
    {
        FetchLikes::dispatch($this->createUser());

        $this
            ->actingAs($user = User::firstOrFail())
            ->getJson(route('likes.index'))
            ->assertOk()
            ->assertView()
            ->contains(
                trans_choice(
                    ':formatted like| :formatted likes',
                    $user->likes_count,
                    ['formatted' => number_format($user->likes_count)]
                )
            )
        ;
    }
}
