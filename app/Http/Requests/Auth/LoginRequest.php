<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *     schema="LoginRequest",
 *     type="object",
 *     title="Login Request",
 *     required={"email", "password"},
 * )
 */
class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @OA\Property(
     *     property="email",
     *     type="string",
     *     description="The user's email address",
     *     example="user@example.com"
     * )
     * @OA\Property(
     *     property="password",
     *     type="string",
     *     description="The user's password",
     *     example="password123"
     * )
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string']
        ];
    }
}
