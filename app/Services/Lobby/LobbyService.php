<?php

namespace App\Services\Lobby;

use App\Enums\GameFigure;
use App\Enums\GameStatus;
use App\Events\Lobby\GameFinishedEvent;
use App\Events\Lobby\GameUpdateEvent;
use App\Events\Lobby\UserJoinedEvent;
use App\Http\Response;
use App\Models\Lobby;
use App\Models\User;
use App\Services\AbstractService;

class LobbyService extends AbstractService
{
    public function createLobby(GameFigure $figure, User $user)
    {
        $lobby = Lobby::create([
            'status' => GameStatus::INITIALIZED,
        ]);
        $gameFigure = $figure ?? fake()->randomElement([GameFigure::nought, GameFigure::cross]);
        $lobby->players()->attach($user, ['figure' => $gameFigure]);

        return $lobby;
    }

    public function joinLobby(Lobby $lobby, User $user)
    {
        if ($user->isInList($lobby->players)) {
            return $lobby;
        }
        abort_if(
            $lobby->players->count() >= 2,
            Response::BAD_REQUEST,
            __('The lobby is full')
        );
        abort_if(
            $lobby->status !== GameStatus::INITIALIZED,
            Response::BAD_REQUEST,
            __('The lobby is already started')
        );
        $figure = $lobby
            ->players->first()
            ->pivot->figure->oppositeFigure()
            ?? fake()->randomElement([GameFigure::nought, GameFigure::cross]);
        $lobby->players()->attach($user, ['figure' => $figure]);
        UserJoinedEvent::dispatch($lobby);
        return $lobby;
    }

    public function makeMove(Lobby $lobby, User $user, int $x, int $y)
    {
        $player = $lobby->players->firstWhere('id', $user->id);
        abort_if(
            !$player,
            Response::FORBIDDEN,
            __('You are not a player of this game')
        );
        abort_if(
            $lobby->status === GameStatus::FINISHED,
            Response::BAD_REQUEST,
            __('The game is already finished')
        );
        abort_if(
            $lobby->players->count() < 2,
            Response::BAD_REQUEST,
            __('Need two players to start the game')
        );
        abort_if(
            $player->pivot->figure !== $lobby->currentFigure(),
            Response::BAD_REQUEST,
            __('It is not your turn')
        );
        abort_if(
            $lobby->isFilled($x, $y),
            Response::BAD_REQUEST,
            __('This cell is already filled')
        );

        if($lobby->status === GameStatus::INITIALIZED) {
            $lobby->status = GameStatus::IN_PROCESS;
        }

        $lobby->fillCell($x, $y, $player->pivot->figure);
        $winner = $lobby->getWinner();

        if ($winner) {
            $lobby->status = GameStatus::FINISHED;
            $lobby->winner()->associate($winner);
            $lobby->finished_at = now();
        }elseif ($lobby->isFull()) {
            $lobby->status = GameStatus::FINISHED;
            $lobby->finished_at = now();
        }

        $lobby->save();

        match ($lobby->status) {
            GameStatus::FINISHED => GameFinishedEvent::dispatch($lobby),
            default => GameUpdateEvent::dispatch($lobby),
        };

        return __('Ok');
    }
}
