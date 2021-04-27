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
    public function guests_cannot_access_search_likes_page()
    {
        $this
            ->getJson(route('likes.search'))
            ->assertStatus(401)
        ;
    }

    /** @test */
    public function likes_are_searchable() : void
    {
        FetchLikes::dispatch($this->createUser());

        $this
            ->actingAs($user = User::firstOrFail())
            ->get(route('likes.search', ['terms' => $terms = 'foo']))
            ->assertOk()
            ->assertView()
            ->contains("Search results for \"$terms\"")
        ;
    }
}
