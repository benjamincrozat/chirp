<?php

namespace Tests\Feature;

use Tests\TestCase;
use Tests\Concerns\CreatesUser;

class SettingsTest extends TestCase
{
    use CreatesUser;

    /** @test */
    public function guests_cannot_access_settings_page()
    {
        $this
            ->getJson(route('settings'))
            ->assertStatus(401)
        ;
    }

    /** @test */
    public function settings_page_works() : void
    {
        $this
            ->actingAs($this->createUser())
            ->getJson(route('settings'))
            ->assertOk()
        ;
    }
}
