<?php

namespace App\Http\Controllers\Api;

use App\Enums\GameFigure;
use App\Http\Controllers\Controller;
use App\Http\Requests\Lobby\CreateLobbyRequest;
use App\Http\Resources\LobbyResource;
use App\Http\Response;
use App\Models\Lobby;
use App\Services\Lobby\LobbyService;
use Illuminate\Http\Request;

class LobbyController extends Controller
{

    public function __construct(private LobbyService $lobbyServ)
    {
    }

    public function createLobby(CreateLobbyRequest $request)
    {
        $lobby = $this->lobbyServ->createLobby(
            GameFigure::from($request->figure),
            $request->user()
        );
        return Response::send(new LobbyResource($lobby));
    }

    public function joinLobby(Lobby $lobby, Request $request)
    {
        $joinedLobby = $this->lobbyServ->joinLobby($lobby, $request->user());
        return Response::send(new LobbyResource($joinedLobby));
    }
}
