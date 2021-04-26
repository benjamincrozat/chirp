<?php

namespace App\Jobs;

use App\Models\User;
use App\Facades\Twitter;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

abstract class BaseJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public User $user;

    public bool $deleteWhenMissingModels = true;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function handle() : void
    {
        Twitter::setOauthToken(
            $this->user->token,
            $this->user->token_secret
        );

        $this->fire();
    }

    abstract public function fire() : void;
}
