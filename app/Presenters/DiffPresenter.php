<?php

namespace App\Presenters;

use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;

class DiffPresenter
{
    public object $data;

    public function __construct(object $data)
    {
        $this->data = $data;
    }

    public function date() : string
    {
        return Carbon::parse(
            $this->data->date
        )->isoFormat('LL');
    }

    public function additions() : array
    {
        return Arr::collapse(
            json_decode($this->data->additions, true)
        );
    }

    public function deletions() : array
    {
        return Arr::collapse(
            json_decode($this->data->deletions, true)
        );
    }
}
