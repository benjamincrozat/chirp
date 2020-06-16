<?php

namespace Tests\Feature\Controllers;

use Tests\TestCase;
use Tests\Concerns\CreatesUser;

class OverviewControllerTest extends TestCase
{
    use CreatesUser;

    /** @test */
    public function guests_cannot_access_overview_page()
    {
        $this
            ->getJson(route('overview'))
            ->assertStatus(401)
        ;
    }

    /** @test */
    public function overview_page_displays_relevant_informations() : void
    {
        $this
            ->actingAs($user = $this->createUser())
            ->getJson(route('overview'))
            ->assertOk()
            ->assertView()
            ->contains($user->name)
            ->contains($user->data->description)
            ->contains($user->data->location)
            ->contains($user->presenter->date())
        ;
    }
}
