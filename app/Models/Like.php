<?php

namespace App\Models;

use App\Traits\Unguarded;
use App\Presenters\TweetPresenter;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Like extends Model
{
    use Unguarded;

    protected $casts = [
        'data' => 'object',
    ];

    public $timestamps = false;

    public function scopeMatching(Builder $query, string $terms, ?string $sort_by) : Builder
    {
        return $query
            ->selectRaw('*, MATCH(author_name, author_screen_name, full_text) AGAINST (? IN BOOLEAN MODE) AS score', [$terms])
            ->whereRaw('MATCH(author_name, author_screen_name, full_text) AGAINST(? IN BOOLEAN MODE)', [$terms])
            ->orderBy('date' === $sort_by ? 'id' : 'score', 'DESC');
    }

    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getPresenterAttribute() : TweetPresenter
    {
        return new TweetPresenter($this->data);
    }
}
