<?php

namespace App\Jobs\Traits;

use Exception;
use App\Facades\Twitter;
use Illuminate\Support\Collection;

trait CallsTwitter
{
    protected function getUsersDetailsForIds($ids) : Collection
    {
        $chunks = (new Collection($ids))->chunk(100);

        if ($chunks->isEmpty()) {
            return $chunks;
        }

        $users = $this->getUsersFromIds($chunks);

        $friendships = $this->getConnectionsToUserFromIds($chunks);

        return $users->map(function ($user) use ($friendships) {
            $user->connections = $friendships->where('id', $user->id)->first()->connections;

            return $user;
        });
    }

    public function getUsersFromIds(Collection $chunks) : Collection
    {
        return $chunks->map(function (Collection $ids) {
            return Twitter::get('users/lookup', [
                'user_id' => $ids->join(','),
            ]);
        })->collapse();
    }

    public function getConnectionsToUserFromIds(Collection $chunks) : Collection
    {
        return $chunks->map(function (Collection $ids) {
            return $this->guardAgainstTwitterErrors(
                Twitter::get('friendships/lookup', [
                    'user_id' => $ids->join(','),
                ])
            );
        })->collapse();
    }

    /**
     * @throws Exception
     */
    protected function guardAgainstTwitterErrors($response)
    {
        if (200 === Twitter::getLastHttpCode()) {
            return $response;
        }

        $error = $response->errors[0];

        // Tokens have been invalidated. We don't need to queue jobs nor use database space anymore.
        if (89 === $error->code) {
            $this->user->delete();
        }

        throw new Exception("Error code: {$error->code} for user #{$this->user->id}. Message: {$error->message}");
    }
}
