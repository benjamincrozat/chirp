<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Following extends Model
{
    protected $casts = [
        'data' => 'object',
    ];

    public $timestamps = false;

    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
