<?php

namespace App\Models;

use App\Enums\GameState;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
        'status' => GameState::class,
    ];

    public function winner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'winner_id');
    }
}
