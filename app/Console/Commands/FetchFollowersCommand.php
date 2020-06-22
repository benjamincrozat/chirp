<?php

namespace App\Console\Commands;

use App\User;
use App\Jobs\FetchFollowers;
use Illuminate\Console\Command;
use App\Console\Commands\Traits\DispatchesJobs;

class FetchFollowersCommand extends Command
{
    use DispatchesJobs;

    protected $signature = 'fetch:followers {--user= : Targeted user\'s ID} {--sync}';

    protected $description = 'Fetch followers';

    public function handle() : void
    {
        if ($user = User::find($this->option('user'))) {
            $this->line("Fetching followers just for <info>{$user->name}</info> (#<info>{$user->id}</info>).");

            $this->dispatch(FetchFollowers::class, $user);

            $this->line('Done!');
        } else {
            User::cursor()->each(function (User $user) {
                $this->dispatch(FetchFollowers::class, $user);

                $this->line("Fetched followers for <info>{$user->name}</info> (#<info>{$user->id}</info>).");
            });
        }
    }
}
