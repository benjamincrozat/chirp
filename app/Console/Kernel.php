<?php

namespace App\Console;

use App\Models\User;
use App\Jobs\FetchUser;
use App\Jobs\FetchLikes;
use App\Jobs\FetchFollowers;
use App\Jobs\FetchFollowings;
use App\Jobs\FetchMutedUsers;
use App\Jobs\FetchBlockedUsers;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected function schedule(Schedule $schedule) : void
    {
        // SQS doesn't support delayed jobs. We have to use the scheduler.

        $schedule->call(function () {
            User::cursor()->each(function (User $user) {
                FetchBlockedUsers::dispatch($user);
                FetchFollowers::dispatch($user);
                FetchFollowings::dispatch($user);
                FetchLikes::dispatch($user);
                FetchMutedUsers::dispatch($user);
                FetchUser::dispatch($user);
            });
        })->hourly();
    }

    protected function commands() : void
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
