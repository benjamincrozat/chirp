<?php

namespace App\Models;

use App\Traits\Unguarded;
use App\Presenters\UserPresenter;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasFactory, Unguarded;

    protected $casts = [
        'data' => 'object',
    ];

    public $incrementing = false;

    protected $withCount = [
        'blocked',
        'followers',
        'followings',
        'likes',
        'muted',
    ];

    public function blocked() : HasMany
    {
        return $this->hasMany(Blocked::class);
    }

    public function diffs() : HasMany
    {
        return $this->hasMany(Diff::class);
    }

    public function followers() : HasMany
    {
        return $this->hasMany(Follower::class);
    }

    public function followings() : HasMany
    {
        return $this->hasMany(Following::class);
    }

    public function likes() : HasMany
    {
        return $this->hasMany(Like::class);
    }

    public function muted() : HasMany
    {
        return $this->hasMany(Muted::class);
    }

    public function getPresenterAttribute() : UserPresenter
    {
        return new UserPresenter($this->data);
    }
}
