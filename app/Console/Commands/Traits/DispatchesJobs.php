<?php

namespace App\Console\Commands\Traits;

trait DispatchesJobs
{
    protected function dispatch(string $job, ...$arguments) : void
    {
        $this->option('sync')
            ? $job::dispatchNow(...$arguments)
            : $job::dispatch(...$arguments);
    }
}
