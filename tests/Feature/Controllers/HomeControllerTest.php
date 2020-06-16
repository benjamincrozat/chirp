<?php

namespace Tests\Feature\Controllers;

use Tests\TestCase;
use Tests\Concerns\CreatesUser;

class HomeControllerTest extends TestCase
{
    use CreatesUser;

    /** @test */
    public function guests_can_access_the_home_page() : void
    {
        $this
            ->getJson(route('home'))
            ->assertOk()
            ->assertView()
            ->contains('Sign in with Twitter')
            ->hasLink(route('login'))
        ;
    }

    /** @test */
    public function users_cannot_access_the_home_page() : void
    {
        $this
            ->actingAs($this->createUser())
            ->getJson(route('home'))
            ->assertRedirect(route('overview'))
        ;
    }
}
