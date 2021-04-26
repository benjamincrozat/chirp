<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Jobs\FetchMutedUsers;
use Illuminate\Console\Command;
use App\Console\Commands\Traits\DispatchesJobs;

class FetchMutedCommand extends Command
{
    use DispatchesJobs;

    protected $signature = 'fetch:muted {--user= : Targeted user\'s ID} {--sync}';

    protected $description = 'Fetch muted users';

    public function handle() : void
    {
        if ($user = User::find($this->option('user'))) {
            $this->line("Fetching muted users just for <info>{$user->name}</info> (#<info>{$user->id}</info>).");

            $this->dispatch(FetchMutedUsers::class, $user);

            $this->line('Done!');
        } else {
            User::cursor()->each(function (User $user) {
                $this->dispatch(FetchMutedUsers::class, $user);

                $this->line("Fetched muted users for <info>{$user->name}</info> (#<info>{$user->id}</info>).");
            });
        }
    }
}
