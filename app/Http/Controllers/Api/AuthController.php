<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Auth\{RegistrationRequest, LoginRequest};
use App\Http\Controllers\Controller;
use App\Http\Response;
use App\Services\Auth\AuthService;


/**
 * @OA\Tag(
 *     name="Authentication",
 *     description="Endpoints for user registration and login"
 * )
 */

class AuthController extends Controller
{

    public function __construct(
        private AuthService $authService
    ) {
    }


    /**
     * @OA\Post(
     *     path="/auth/register",
     *     summary="Register a new user",
     *     tags={"Authentication"},
     *     @OA\RequestBody(
     *         required=true,
     *         description="Registration data",
     *         @OA\JsonContent(ref="#/components/schemas/RegistrationRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="User registration successful",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="access_token", type="string", example="YOUR_ACCESS_TOKEN")
     *             )
     *         )
     *     ),
     *      @OA\Response(
     *         response=422,
     *         description="Validation Error",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="property", type="string", example="email"),
     *                     @OA\Property(
     *                         property="errors",
     *                         type="array",
     *                         @OA\Items(type="string", example="The email field must be a valid email address.")
     *                     )
     *                 )
     *             )
     *         )
     *     ),
     * )
     */
    public function registrate(RegistrationRequest $request)
    {
        $access_token = $this->authService->regisrateUser($request->all());
        return Response::send(['access_token' => $access_token]);
    }
    /**
     * @OA\Post(
     *     path="/auth/login",
     *     summary="Login as an existing user",
     *     tags={"Authentication"},
     *     @OA\RequestBody(
     *         required=true,
     *         description="Login data",
     *         @OA\JsonContent(ref="#/components/schemas/LoginRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="User login successful",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="access_token", type="string", example="YOUR_ACCESS_TOKEN")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation Error",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="property", type="string", example="email"),
     *                     @OA\Property(
     *                         property="errors",
     *                         type="array",
     *                         @OA\Items(type="string", example="The email field must be a valid email address.")
     *                     )
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad Request",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="data", type="string", example="No user with this email/Wrong password")
     *         )
     *     ),
     * ),
     */

    public function login(LoginRequest $request)
    {
        $access_token = $this->authService->loginUser($request->only(['email', 'password']));
        return Response::send(['access_token' => $access_token]);
    }
}
