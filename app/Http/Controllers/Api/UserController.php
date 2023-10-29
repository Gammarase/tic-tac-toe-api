<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Http\Response;
use App\Services\User\UserService;
use Illuminate\Http\Request;


/**
 * @OA\Tag(
 *     name="User",
 *     description="Operations related to user management"
 * )
 */
class UserController extends Controller
{

    public function __construct(private UserService $userService)
    {
    }

    /**
     * @OA\Get(
     *     path="/user",
     *     operationId="getUser",
     *     tags={"User"},
     *     security={{ "sanctum": {} }},
     *     summary="Get the current user",
     *     description="Returns information about the currently authenticated user.",
     *     @OA\Header(
     *         header="Authorization",
     *         description="Bearer token",
     *         required=true,
     *         @OA\Schema(
     *             type="string",
     *             format="string",
     *             example="Bearer 6|q48vC3M4yBHm7Vghf9OYkDIRkcmNlZFsO77YvTfN9efb48da"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", ref="#/components/schemas/UserResource")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="data", type="string", example="Unauthorized")
     *         )
     *     )
     * )
     */
    public function getUser(Request $request)
    {
        return Response::send(new UserResource($request->user()));
    }


    /**
     * @OA\Post(
     *     path="/user",
     *     operationId="updateUser",
     *     tags={"User"},
     *     security={{ "sanctum": {} }},
     *     summary="Update user information",
     *     description="Update the user's username and email.",
     *     @OA\Header(
     *         header="Authorization",
     *         description="Bearer token",
     *         required=true,
     *         @OA\Schema(
     *             type="string",
     *             format="string",
     *             example="Bearer 6|q48vC3M4yBHm7Vghf9OYkDIRkcmNlZFsO77YvTfN9efb48da"
     *         )
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="username", type="string"),
     *             @OA\Property(property="email", type="string")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="User information updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", ref="#/components/schemas/UserResource")
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
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="data", type="string", example="Unauthorized")
     *         )
     *     )
     * )
     */
    public function updateUser(UpdateUserRequest $request)
    {
        return Response::send(
            $this->userService->updateUser(
                $request->only('username', 'email'),
                $request->user()
            )
        );
    }
}
