<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

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
            'players' => UserResource::collection($this->players)
        ];
    }
}
