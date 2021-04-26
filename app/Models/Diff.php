<?php

namespace App\Models;

use App\Traits\Unguarded;
use App\Presenters\DiffPresenter;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Diff extends Model
{
    use Unguarded;

    protected $casts = [
        'additions' => 'collection',
        'deletions' => 'collection',
    ];

    public static function diffsHistory(int $user_id, string $for) : Collection
    {
        return self::selectRaw('
                DATE(created_at) AS date,
                user_id,
                JSON_ARRAYAGG(additions) AS additions,
                JSON_ARRAYAGG(deletions) AS deletions
            ')
            ->where('user_id', $user_id)
            ->where('for', $for)
            ->groupBy('date')
            ->groupBy('user_id')
            ->orderBy('date', 'DESC')
            ->get()
            ->map(fn ($d) => new DiffPresenter($d));
    }

    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
