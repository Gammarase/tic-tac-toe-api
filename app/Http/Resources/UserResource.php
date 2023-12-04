<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="UserResource",
 *     type="object",
 *
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="username", type="string", example="john_doe"),
 *     @OA\Property(property="email", type="string", example="john@example.com"),
 *     @OA\Property(property="created_at", type="string", format="date-time", example="2023-10-29T12:00:00Z"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", example="2023-10-30T14:30:00Z"),
 * ),
 *
 * @OA\Schema(
 *     schema="UserResourceWithFigure",
 *     type="object",
 *
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="username", type="string", example="john_doe"),
 *     @OA\Property(property="email", type="string", example="john@example.com"),
 *     @OA\Property(property="created_at", type="string", format="date-time", example="2023-10-29T12:00:00Z"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", example="2023-10-30T14:30:00Z"),
 *     @OA\Property(property="figure", type="integer", example=0, enum={0, 1}, description="0 - nought, 1 - cross"),
 * )
 */
class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'username' => $this->username,
            'email' => $this->email,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            $this->mergeWhen((bool)$this->pivot, [
                'figure' => $this->pivot->figure ?? null,
            ])
        ];
    }
}
