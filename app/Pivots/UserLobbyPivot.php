<?php

namespace App\Pivots;

use App\Enums\GameFigure;
use Illuminate\Database\Eloquent\Relations\Pivot;

class UserLobbyPivot extends Pivot
{
    protected $fillable = [
        'figure',
    ];

    protected $casts = [
        'figure' => GameFigure::class,
    ];
}
