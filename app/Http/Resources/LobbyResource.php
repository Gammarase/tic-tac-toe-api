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
 *         type="string",
 *         description="The username of the lobby's winner (null if no winner yet)"
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
 *             ref="#/components/schemas/UserResource"
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
            'winner' => $this->winner->username ?? null,
            'status' => $this->status,
            'finished_at' => $this->finished_at,
            'players' => UserResource::collection($this->whenLoaded('players')),
        ];
    }
}
