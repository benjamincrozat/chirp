<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Jobs\FetchBlockedUsers;
use Illuminate\Console\Command;
use App\Console\Commands\Traits\DispatchesJobs;

class FetchBlockedCommand extends Command
{
    use DispatchesJobs;

    protected $signature = 'fetch:blocked {--user= : Targeted user\'s ID} {--sync}';

    protected $description = 'Fetch blocked users';

    public function handle() : void
    {
        if ($user = User::find($this->option('user'))) {
            $this->line("Fetching blocked users just for <info>{$user->name}</info> (#<info>{$user->id}</info>).");

            $this->dispatch(FetchBlockedUsers::class, $user);

            $this->line('Done!');
        } else {
            User::cursor()->each(function (User $user) {
                $this->dispatch(FetchBlockedUsers::class, $user);

                $this->line("Fetched blocked users for <info>{$user->name}</info> (#<info>{$user->id}</info>).");
            });
        }
    }
}
