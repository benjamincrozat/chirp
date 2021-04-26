<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Jobs\FetchLikes;
use Illuminate\Console\Command;
use App\Console\Commands\Traits\DispatchesJobs;

class FetchLikesCommand extends Command
{
    use DispatchesJobs;

    protected $signature = 'fetch:likes {--user= : Targeted user\'s ID} {--sync}';

    protected $description = 'Fetch likes';

    public function handle() : void
    {
        if ($user = User::find($this->option('user'))) {
            $this->line("Fetching likes just for <info>{$user->name}</info> (#<info>{$user->id}</info>).");

            $this->dispatch(FetchLikes::class, $user);

            $this->line('Done!');
        } else {
            User::cursor()->each(function (User $user) {
                $this->dispatch(FetchLikes::class, $user);

                $this->line("Fetched likes for <info>{$user->name}</info> (#<info>{$user->id}</info>).");
            });
        }
    }
}
