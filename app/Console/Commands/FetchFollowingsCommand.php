<?php

namespace App\Console\Commands;

use App\User;
use App\Jobs\FetchFollowings;
use Illuminate\Console\Command;
use App\Console\Commands\Traits\DispatchesJobs;

class FetchFollowingsCommand extends Command
{
    use DispatchesJobs;

    protected $signature = 'fetch:followings {--user= : Targeted user\'s ID} {--sync}';

    protected $description = 'Fetch followings';

    public function handle() : void
    {
        if ($user = User::find($this->option('user'))) {
            $this->line("Fetching followings just for <info>{$user->name}</info> (#<info>{$user->id}</info>).");

            $this->dispatch(FetchFollowings::class, $user);

            $this->line('Done!');
        } else {
            User::cursor()->each(function (User $user) {
                $this->dispatch(FetchFollowings::class, $user);

                $this->line("Fetched followings for <info>{$user->name}</info> (#<info>{$user->id}</info>).");
            });
        }
    }
}
