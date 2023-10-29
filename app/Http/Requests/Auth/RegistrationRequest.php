<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules;

/**
 * @OA\Schema(
 *     type="object",
 *     required={"username", "email", "password"},
 * )
 */
class RegistrationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */

    /**
     * @OA\Property(
     *     property="username",
     *     type="string",
     *     maxLength=50,
     * )
     * @OA\Property(
     *     property="email",
     *     type="string",
     *     format="email",
     * )
     * @OA\Property(
     *     property="password",
     *     type="string",
     *     format="password",
     * )
     */
    public function rules(): array
    {
        return [
            'username' => ['required', 'string', 'max:50'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'string', Rules\Password::defaults()]
        ];
    }
}
