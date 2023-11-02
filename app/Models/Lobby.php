<?php

namespace App\Models;

use App\Enums\GameStatus;
use App\Pivots\UserLobbyPivot;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Lobby extends Model
{
    use HasFactory;

    protected $fillable = [
        'winner_id',
        'status',
        'state',
        'finished_at'
    ];

    protected $casts = [
        'status' => GameStatus::class,
    ];

    public function winner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'winner_id');
    }

    public function players(): BelongsToMany
    {
        return $this->belongsToMany(User::class)
            ->withPivot('figure')
            ->using(UserLobbyPivot::class);
    }
}
