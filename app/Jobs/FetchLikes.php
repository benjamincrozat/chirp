<?php

namespace App\Jobs;

use App\Like;
use App\User;
use App\Facades\Twitter;
use Illuminate\Support\Arr;
use App\Jobs\Traits\CallsTwitter;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class FetchLikes extends BaseJob
{
    use CallsTwitter;

    protected Collection $likes;

    public function __construct(User $user)
    {
        parent::__construct($user);

        $this->likes = new Collection;
    }

    public function fire() : void
    {
        $this
            ->fetchLikes()
            ->deleteUnecessaryLikes()
            ->insertNewLikes()
        ;
    }

    protected function fetchLikes() : self
    {
        do {
            $parameters = [
                'count'      => 200,
                'tweet_mode' => 'extended',
            ];

            if (! empty($response)) {
                $parameters['max_id'] = Arr::last($response)->id;
            }

            $response = $this->guardAgainstTwitterErrors(
                Twitter::get('favorites/list', $parameters)
            );

            if (($parameters['max_id'] ?? 0) === Arr::last($response)->id) {
                break;
            }

            $this->likes = $this->likes->concat($response);
        } while (true);

        return $this;
    }

    protected function deleteUnecessaryLikes() : self
    {
        DB::table('likes')
            ->whereUserId($this->user->id)
            ->whereNotIn('id', $this->likes->pluck('id'))
            ->delete();

        return $this;
    }

    protected function insertNewLikes() : self
    {
        $existing = DB::table('likes')
            ->select('id')
            ->whereUserId($this->user->id)
            ->get()
            ->pluck('id');

        $new = $this->likes->whereNotIn('id', $existing);

        Like::insert($new->map(function (object $like) {
            return [
                'id'      => $like->id,
                'user_id' => $this->user->id,
                'data'    => json_encode($like),
            ];
        })->toArray());

        return $this;
    }
}
