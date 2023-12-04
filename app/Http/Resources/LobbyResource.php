<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="LobbyResource",
 *     type="object",
 *     description="Lobby resource representation",
 *
 *     @OA\Property(
 *         property="id",
 *         type="integer",
 *         format="int64",
 *         description="The ID of the lobby"
 *     ),
 *     @OA\Property(
 *         property="winner",
 *         type="object",
 *         description="The winner of the lobby",
 *         ref="#/components/schemas/UserResource",
 *     ),
 *     @OA\Property(
 *         property="status",
 *         type="integer",
 *         description="The status of the lobby, 0 - initialized, 1 - in progress, 2 - finished",
 *         enum={0, 1, 2},
 *         example=0,
 *         default=0
 *     ),
 *     @OA\Property(
 *       property="state",
 *       type="array",
 *       description="The state of the game, -1 - empty, 0 - nought, 1 - cross",
 *       example={{-1, 0, -1}, {0, 1, 1}, {-1, 0, 1}},
 *       @OA\Items(
 *         type="array",
 *         example={-1, 0, -1},
 *         @OA\Items(
 *           type="integer",
 *           description="The state of the cell, -1 - empty, 0 - nought, 1 - cross",
 *           enum={-1, 0, 1},
 *           example=0,
 *           default=-1
 *           )
 *         )
 *       ),
 *     @OA\Property(
 *         property="finished_at",
 *         type="string",
 *         format="date-time",
 *         description="The timestamp when the lobby finished"
 *     ),
 *     @OA\Property(
 *         property="players",
 *         type="array",
 *         description="Array of players in the lobby",
 *
 *         @OA\Items(
 *             ref="#/components/schemas/UserResourceWithFigure"
 *         )
 *     )
 * )
 */
class LobbyResource extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'winner' => new UserResource($this->winner) ?? null,
            'status' => $this->status,
            'state' => $this->state,
            'finished_at' => $this->finished_at,
            'players' => UserResource::collection($this->whenLoaded('players')),
        ];
    }
}
