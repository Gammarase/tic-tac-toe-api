<?php

namespace App\Models;

use App\Enums\GameFigure;
use App\Enums\GameStatus;
use App\Pivots\UserLobbyPivot;
use Exception;
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
        'finished_at',
    ];

    protected $casts = [
        'status' => GameStatus::class,
        'state' => 'array'
    ];

    protected $attributes = [
        'state' =>  '[[-1,-1,-1],[-1,-1,-1],[-1,-1,-1]]',
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


    public function currentFigure() : GameFigure
    {
        $state = $this->state;
        $noughtCount = 0;
        $crossCount = 0;

        foreach ($state as $row) {
            foreach ($row as $cell) {
                if ($cell === GameFigure::nought->value) {
                    $noughtCount++;
                } elseif ($cell === GameFigure::cross->value) {
                    $crossCount++;
                }
            }
        }

        if ($noughtCount === $crossCount) {
            return GameFigure::cross;
        } elseif ($crossCount === $noughtCount + 1) {
            return GameFigure::nought;
        } else {
            throw new Exception(__('Wrong state of lobby'));
        }
    }

    public function isFilled(int $x, int $y): bool
    {
        return $this->state[$x][$y] !== -1;
    }

    public function fillCell(int $x, int $y, GameFigure $figure): void
    {
        $state = $this->state;
        $state[$x][$y] = $figure->value;
        $this->state = $state;
    }
    public function getWinner(): ?User
    {
        $state = $this->state;
        $winner = null;

        for ($i = 0; $i < 3; $i++) {
            if ($state[$i][0] !== -1 && $state[$i][0] === $state[$i][1] && $state[$i][1] === $state[$i][2]) {
                $winner = $this->players->firstWhere('pivot.figure', GameFigure::from($state[$i][0]));
            }
            if ($state[0][$i] !== -1 && $state[0][$i] === $state[1][$i] && $state[1][$i] === $state[2][$i]) {
                $winner = $this->players->firstWhere('pivot.figure', GameFigure::from($state[0][$i]));
            }
        }

        if ($state[0][0] !== -1 && $state[0][0] === $state[1][1] && $state[1][1] === $state[2][2]) {
            $winner = $this->players->firstWhere('pivot.figure', GameFigure::from($state[0][0]));
        }
        if ($state[0][2] !== -1 && $state[0][2] === $state[1][1] && $state[1][1] === $state[2][0]) {
            $winner = $this->players->firstWhere('pivot.figure', GameFigure::from($state[0][2]));
        }

        return $winner;
    }
    public function isFull(): bool
    {
        $state = $this->state;
        $count = 0;

        foreach ($state as $row) {
            foreach ($row as $cell) {
                if ($cell !== -1) {
                    $count++;
                }
            }
        }

        return $count === 9;
    }
}
