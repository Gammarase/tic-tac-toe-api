<?php

namespace App\Services\Lobby;

use App\Enums\GameFigure;
use App\Enums\GameStatus;
use App\Http\Response;
use App\Models\Lobby;
use App\Models\User;
use App\Services\AbstractService;

class LobbyService extends AbstractService
{

    public function createLobby(GameFigure $figure, User $user)
    {
        $lobby = Lobby::create([
            'status' => GameStatus::INITIALIZED
        ]);
        $gameFigure = $figure ?? fake()->randomElement([GameFigure::nought, GameFigure::cross]);
        $lobby->players()->attach($user, ['figure' => $gameFigure]);
        return $lobby;
    }

    public function joinLobby(Lobby $lobby, User $user)
    {
        abort_if(
            $lobby->players->count() >= 2,
            Response::BAD_REQUEST,
            __('The lobby is full')
        );
        $figure = $lobby
            ->players->first()
            ->pivot->figure->oppositeFigure()
            ?? fake()->randomElement([GameFigure::nought, GameFigure::cross]);
        $lobby->players()->attach($user, ['figure' => $figure]);
        return $lobby;
    }
}
