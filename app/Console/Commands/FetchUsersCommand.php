<?php

namespace App\Console\Commands;

use App\User;
use App\Jobs\FetchUser;
use Illuminate\Console\Command;
use App\Console\Commands\Traits\DispatchesJobs;

class FetchUsersCommand extends Command
{
    use DispatchesJobs;

    protected $signature = 'fetch:users {--user= : Targeted user\'s ID} {--sync}';

    protected $description = 'Fetch users';

    public function handle() : void
    {
        if ($user = User::find($this->option('user'))) {
            $this->line("Fetching profile data just for <info>{$user->name}</info> (#<info>{$user->id}</info>).");

            $this->dispatch(FetchUser::class, $user);

            $this->line('Done!');
        } else {
            User::cursor()->each(function (User $user) {
                $this->dispatch(FetchUser::class, $user);

                $this->line("Fetched profile data for <info>{$user->name}</info> (#<info>{$user->id}</info>).");
            });
        }
    }
}
