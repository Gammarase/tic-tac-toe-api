<?php

namespace App\Http\Requests\Lobby;

use Illuminate\Foundation\Http\FormRequest;


/**
 * @OA\Schema(
 *     title="MakeMoveRequest",
 *     description="Request body for making a move in the tic-tac-toe game.",
 *     required={"x", "y"},
 *     @OA\Property(
 *         property="x",
 *         type="integer",
 *         description="The x-coordinate of the move.",
 *         example=0,
 *         minimum = 0,
 *         maximum=2
 *     ),
 *     @OA\Property(
 *         property="y",
 *         type="integer",
 *         description="The y-coordinate of the move.",
 *         example=0,
 *         minimum = 0,
 *         maximum=2
 *     )
 * )
 */
class MakeMoveRequest extends FormRequest
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
            'x' => ['required', 'integer', 'min:0', 'max:2'],
            'y' => ['required', 'integer', 'min:0', 'max:2']
        ];
    }
}
