<?php

namespace Tests\Feature\Controllers;

use Tests\TestCase;
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
        FetchLikes::dispatch($user = $this->createUser());

        $this
            ->actingAs($user)
            ->getJson(route('likes.index'))
            ->assertOk()
            ->assertView()
            ->contains(number_format($user->likes_count) . ' likes')
        ;
    }
}
