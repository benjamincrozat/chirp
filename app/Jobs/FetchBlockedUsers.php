<?php

namespace App\Jobs;

use App\User;
use App\Blocked;
use App\Facades\Twitter;
use App\Jobs\Traits\CallsTwitter;
use Illuminate\Support\Collection;

class FetchBlockedUsers extends BaseJob
{
    use CallsTwitter;

    protected Collection $users;

    public function __construct(User $user)
    {
        parent::__construct($user);

        $this->users = new Collection;
    }

    public function fire() : void
    {
        $this
            ->fetchData()
            ->deleteUnecessaryUsers()
            ->insertNewUsers()
        ;
    }

    protected function fetchData() : self
    {
        do {
            $response = $this->guardAgainstTwitterErrors(
                Twitter::get('blocks/ids', [
                    'cursor' => $response->next_cursor ?? -1,
                ])
            );

            $this->users = $this->users->concat($response->ids);
        } while ($response->next_cursor);

        return $this;
    }

    protected function deleteUnecessaryUsers() : self
    {
        Blocked::whereUserId($this->user->id)
            ->whereNotIn('id', $this->users)
            ->delete();

        return $this;
    }

    protected function insertNewUsers() : self
    {
        $existing = Blocked::select('id')
            ->whereUserId($this->user->id)
            ->get()
            ->pluck('id');

        $newUsers = $this->getUsersDetailsForIds(
            $this->users->diff($existing)
        );

        Blocked::insert($newUsers->map(function (object $newUser) {
            return [
                'id'       => $newUser->id,
                'user_id'  => $this->user->id,
                'name'     => $newUser->name,
                'nickname' => $newUser->screen_name,
                'data'     => json_encode($newUser),
            ];
        })->toArray());

        return $this;
    }
}
