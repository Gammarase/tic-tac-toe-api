<?php

namespace App\Http\Controllers\Api;

use App\Enums\GameFigure;
use App\Http\Controllers\Controller;
use App\Http\Requests\Lobby\CreateLobbyRequest;
use App\Http\Requests\Lobby\MakeMoveRequest;
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
     *
     *     @OA\RequestBody(
     *         required=true,
     *
     *         @OA\JsonContent(ref="#/components/schemas/CreateLobbyRequest")
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Lobby created successfully",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", ref="#/components/schemas/LobbyResource")
     *         )
     *     )
     * )
     *
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
     *
     *     @OA\Parameter(
     *         name="lobby",
     *         in="path",
     *         required=true,
     *         description="ID of the lobby to join",
     *
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Joined lobby successfully",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", ref="#/components/schemas/LobbyResource")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=404,
     *         description="Lobby not found",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="data", type="string", example="Not found")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=400,
     *         description="The lobby is full",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="data", type="string", example="The lobby is full")
     *         )
     *     )
     * )
     *
     * @return Response
     */
    public function joinLobby(Lobby $lobby, Request $request)
    {
        $joinedLobby = $this->lobbyService->joinLobby($lobby, $request->user());

        return Response::send(new LobbyResource($joinedLobby));
    }


    /**
     * Make a move in the lobby.
     *
     * @param Lobby $lobby The lobby instance.
     * @param MakeMoveRequest $request The request object.
     * @return \Illuminate\Http\Response The response object.
     *
     * @OA\Post(
     *     path="/api/lobby/{lobby}/move",
     *     summary="Make a move in the lobby",
     *     tags={"Lobby"},
     *     @OA\Parameter(
     *         name="lobby",
     *         in="path",
     *         description="The ID of the lobby",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *            ref="#/components/schemas/MakeMoveRequest"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="success",
     *                 type="boolean",
     *                 example=true
     *                 ),
     *             @OA\Property(
     *               property="data",
     *               type="object",
     *               example="Ok",
     *                 )
     *         )
     *     ),
     *
     *     @OA\Response(
     *       response=403,
     *       description="Forbidden",
     *       @OA\JsonContent(
     *         @OA\Property(
     *           property="success",
     *           type="boolean",
     *           example=false
     *           ),
     *           @OA\Property(
     *             property="data",
     *             type="string",
     *             example="You are not a player of this game"
     *             )
     *           )
     *         ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad Request",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="success",
     *                 type="boolean",
     *                 example=false
     *             ),
     *             @OA\Property(
     *               property="data",
     *               type="string",
     *               example="The game is already finished/Need two players to start the game/It is not your turn/The cell is already filled"
     *         )
     *     ),
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="success",
     *                 type="boolean",
     *                 example=false
     *             ),
     *             @OA\Property(
     *               property="data",
     *               type="string",
     *               example="Unauthenticated"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Lobby not Found",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="success",
     *                 type="boolean",
     *                 example=false
     *             ),
     *             @OA\Property(
     *               property="data",
     *               type="string",
     *               example="Lobby not found"
     *            )
     *         )
     *     )
     * )
     */
    public function makeMove(Lobby $lobby, MakeMoveRequest $request)
    {
        $result = $this->lobbyService->makeMove(
            $lobby,
            $request->user(),
            $request->input('x'),
            $request->input('y'),
        );

        return Response::send($result);
    }
}
