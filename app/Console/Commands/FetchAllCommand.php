<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Jobs\FetchUser;
use App\Jobs\FetchLikes;
use App\Jobs\FetchFollowers;
use App\Jobs\FetchFollowings;
use App\Jobs\FetchMutedUsers;
use App\Jobs\FetchBlockedUsers;
use Illuminate\Console\Command;
use App\Console\Commands\Traits\DispatchesJobs;

class FetchAllCommand extends Command
{
    use DispatchesJobs;

    protected $signature = 'fetch:all {--user= : Targeted user\'s ID} {--sync}';

    protected $description = 'Fetch everything';

    public function handle() : void
    {
        // Let's use a Lazy Collection to stay memory efficient.
        // https://laravel.com/docs/collections#lazy-collections

        if ($user = User::find($this->option('user'))) {
            $this->line("Fetching data just for <info>{$user->name}</info> (#<info>{$user->id}</info>).");

            $this->dispatch(FetchBlockedUsers::class, $user);
            $this->dispatch(FetchFollowers::class, $user);
            $this->dispatch(FetchFollowings::class, $user);
            $this->dispatch(FetchLikes::class, $user);
            $this->dispatch(FetchMutedUsers::class, $user);
            $this->dispatch(FetchUser::class, $user);

            $this->line('Done!');
        } else {
            User::cursor()->each(function (User $user) {
                $this->dispatch(FetchBlockedUsers::class, $user);
                $this->dispatch(FetchFollowers::class, $user);
                $this->dispatch(FetchFollowings::class, $user);
                $this->dispatch(FetchLikes::class, $user);
                $this->dispatch(FetchMutedUsers::class, $user);
                $this->dispatch(FetchUser::class, $user);

                $this->line("Fetched data for <info>{$user->name}</info> (#<info>{$user->id}</info>).");
            });
        }
    }
}
