<?php

namespace Tests\Feature\Jobs;

use Tests\TestCase;
use App\Jobs\FetchUser;
use Tests\Concerns\CreatesUser;

class FetchUserTest extends TestCase
{
    use CreatesUser;

    /** @test */
    public function it_fetches_user_s_data() : void
    {
        $user = $this->factoryUser();

        $this->assertNull($user->data);

        FetchUser::dispatch($user);

        $this->assertNotNull($user->fresh()->data);
    }
}
