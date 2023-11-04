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


/**
 * @OA\Tag(
 *     name="Lobby",
 *     description="Operations related to lobbies"
 * )
 */

class LobbyController extends Controller
{

    public function __construct(private LobbyService $lobbyService)
    {
    }


    /**
     * @OA\Post(
     *     path="/api/lobby/create",
     *     tags={"Lobby"},
     *     summary="Create a new lobby",
     *     description="Creates a new lobby for a game figure.",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/CreateLobbyRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Lobby created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", ref="#/components/schemas/LobbyResource")
     *         )
     *     )
     * )
     *
     * @param CreateLobbyRequest $request
     * @return Response
     */
    public function createLobby(CreateLobbyRequest $request)
    {
        $lobby = $this->lobbyService->createLobby(
            GameFigure::from($request->figure),
            $request->user()
        );
        return Response::send(new LobbyResource($lobby));
    }

    /**
     * @OA\Get(
     *     path="/api/lobby/join/{lobby}",
     *     tags={"Lobby"},
     *     summary="Join an existing lobby",
     *     description="Joins an existing lobby using the lobby's ID.",
     *     @OA\Parameter(
     *         name="lobby",
     *         in="path",
     *         required=true,
     *         description="ID of the lobby to join",
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Joined lobby successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", ref="#/components/schemas/LobbyResource")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Lobby not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="data", type="string", example="Not found")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="The lobby is full",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="data", type="string", example="The lobby is full")
     *         )
     *     )
     * )
     *
     * @param Lobby $lobby
     * @param Request $request
     * @return Response
     */

    public function joinLobby(Lobby $lobby, Request $request)
    {
        $joinedLobby = $this->lobbyService->joinLobby($lobby, $request->user());
        return Response::send(new LobbyResource($joinedLobby));
    }
}
