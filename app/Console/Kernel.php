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
    /**
     * The Artisan commands provided by your application.
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule) : void
    {
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

    /**
     * Register the commands for the application.
     */
    protected function commands() : void
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
