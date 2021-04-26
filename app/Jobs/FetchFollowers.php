<?php

namespace App\Jobs;

use App\Models\Diff;
use App\Models\User;
use App\Facades\Twitter;
use App\Models\Follower;
use App\Jobs\Traits\CallsTwitter;
use Illuminate\Support\Collection;

class FetchFollowers extends BaseJob
{
    use CallsTwitter;

    protected Collection $followers;

    protected Collection $deletions;

    protected Collection $additions;

    public function __construct(User $user)
    {
        parent::__construct($user);

        $this->followers  = new Collection;
        $this->deletions  = new Collection;
        $this->additions  = new Collection;
    }

    public function fire() : void
    {
        $this
            ->fetch()
            ->deleteUnecessaryFollowers()
            ->insertNewFollowers()
            ->createDiff()
        ;
    }

    protected function fetch() : self
    {
        do {
            $response = $this->guardAgainstTwitterErrors(
                Twitter::get('followers/ids', [
                    'cursor' => $response->next_cursor ?? -1,
                ])
            );

            $this->followers = $this->followers->concat($response->ids);
        } while ($response->next_cursor);

        if (app()->runningUnitTests()) {
            $this->followers = $this->followers->take(10);
        }

        return $this;
    }

    protected function deleteUnecessaryFollowers() : self
    {
        $this->deletions = $this->getUsersDetailsForIds(
            Follower::select('id')
                ->whereUserId($this->user->id)
                ->whereNotIn('id', $this->followers)
                ->get()
                ->pluck('id')
        );

        if ($this->deletions->isNotEmpty()) {
            Follower::whereUserId($this->user->id)
                ->whereIn('id', $this->deletions->pluck('id'))
                ->delete();
        }

        return $this;
    }

    protected function insertNewFollowers() : self
    {
        $existing = Follower::select('id')
            ->whereUserId($this->user->id)
            ->get()
            ->pluck('id');

        $inserts = $existing->isEmpty()
            ? $this->getUsersDetailsForIds($this->followers)
            : $this->additions = $this->getUsersDetailsForIds(
                $this->followers->filter(
                    fn ($id) => ! $existing->contains($id)
                )
            );

        Follower::insert(
            $inserts
                ->map(function ($insert) {
                    return [
                        'id'       => $insert->id,
                        'user_id'  => $this->user->id,
                        'name'     => $insert->name,
                        'nickname' => $insert->screen_name,
                        'data'     => json_encode($insert),
                    ];
                })
                ->toArray()
        );

        return $this;
    }

    protected function createDiff() : self
    {
        if ($this->additions->isEmpty() && $this->deletions->isEmpty()) {
            return $this;
        }

        $this->user->diffs()->save(
            new Diff($attributes = [
                'for'       => 'followers',
                'additions' => $this->additions,
                'deletions' => $this->deletions,
            ])
        );

        return $this;
    }
}
