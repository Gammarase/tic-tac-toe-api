<?php

namespace App\Http\Requests\Lobby;

use App\Enums\GameFigure;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

/**
 * @OA\Schema(
 *     schema="CreateLobbyRequest",
 *     type="object",
 *     description="Create Lobby Request",
 *
 *     @OA\Property(
 *         property="figure",
 *         type="integer",
 *         description="The game figure to be used (0 for nought, 1 for cross)",
 *         enum={0, 1},
 *         example=0
 *     )
 * )
 */
class CreateLobbyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'figure' => ['nullable', new Enum(GameFigure::class)],
        ];
    }
}
