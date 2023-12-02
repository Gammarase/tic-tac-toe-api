<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\UpdateUserRequest;
use App\Http\Resources\LobbyResource;
use App\Http\Resources\UserResource;
use App\Http\Response;
use App\Models\User;
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
     *     path="/api/user",
     *     operationId="getUser",
     *     tags={"User"},
     *     security={{ "sanctum": {} }},
     *     summary="Get the current user",
     *     description="Returns information about the currently authenticated user.",
     *
     *     @OA\Header(
     *         header="Authorization",
     *         description="Bearer token",
     *         required=true,
     *
     *         @OA\Schema(
     *             type="string",
     *             format="string",
     *             example="Bearer 6|q48vC3M4yBHm7Vghf9OYkDIRkcmNlZFsO77YvTfN9efb48da"
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", ref="#/components/schemas/UserResource")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *
     *         @OA\JsonContent(
     *
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
     *     path="/api/user",
     *     operationId="updateUser",
     *     tags={"User"},
     *     security={{ "sanctum": {} }},
     *     summary="Update user information",
     *     description="Update the user's username and email.",
     *
     *     @OA\Header(
     *         header="Authorization",
     *         description="Bearer token",
     *         required=true,
     *
     *         @OA\Schema(
     *             type="string",
     *             format="string",
     *             example="Bearer 6|q48vC3M4yBHm7Vghf9OYkDIRkcmNlZFsO77YvTfN9efb48da"
     *         )
     *     ),
     *
     *     @OA\RequestBody(
     *         required=true,
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="username", type="string"),
     *             @OA\Property(property="email", type="string")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="User information updated successfully",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", ref="#/components/schemas/UserResource")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=422,
     *         description="Validation Error",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *
     *                 @OA\Items(
     *
     *                     @OA\Property(property="property", type="string", example="email"),
     *                     @OA\Property(
     *                         property="errors",
     *                         type="array",
     *
     *                         @OA\Items(type="string", example="The email field must be a valid email address.")
     *                     )
     *                 )
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *
     *         @OA\JsonContent(
     *
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

    /**
     * Get the history of the user.
     *
     * @OA\Get(
     *     path="/api/user/history",
     *     summary="Get user history",
     *     description="Retrieve the history of the user's gameplays.",
     *     tags={"User"},
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         description="Page number",
     *         required=false,
     *         @OA\Schema(
     *             type="integer",
     *             default=1
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="success",
     *                 type="boolean",
     *                 example=true
     *             ),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(
     *                     property="data",
     *                     type="object",
     *                     ref="#/components/schemas/LobbyResource"
     *                 ),
     *                 @OA\Property(
     *                     property="links",
     *                     type="object",
     *                     @OA\Property(
     *                         property="first",
     *                         type="string",
     *                         example="http://localhost/api/user/history?page=1"
     *                     ),
     *                     @OA\Property(
     *                         property="last",
     *                         type="string",
     *                         example="http://localhost/api/user/history?page=1"
     *                     ),
     *                     @OA\Property(
     *                         property="prev",
     *                         type="null",
     *                         nullable=true
     *                     ),
     *                     @OA\Property(
     *                         property="next",
     *                         type="null",
     *                         nullable=true
     *                     )
     *                 ),
     *                 @OA\Property(
     *                     property="meta",
     *                     type="object",
     *                     @OA\Property(
     *                         property="current_page",
     *                         type="integer",
     *                         example=1
     *                     ),
     *                     @OA\Property(
     *                         property="from",
     *                         type="integer",
     *                         example=1
     *                     ),
     *                     @OA\Property(
     *                         property="last_page",
     *                         type="integer",
     *                         example=1
     *                     ),
     *                     @OA\Property(
     *                         property="links",
     *                         type="array",
     *                         @OA\Items(
     *                             @OA\Property(
     *                                 property="url",
     *                                 type="null",
     *                                 nullable=true
     *                             ),
     *                             @OA\Property(
     *                                 property="label",
     *                                 type="string",
     *                                 example="&laquo; Previous"
     *                             ),
     *                             @OA\Property(
     *                                 property="active",
     *                                 type="boolean",
     *                                 example=false
     *                             )
     *                         )
     *                     ),
     *                     @OA\Property(
     *                         property="path",
     *                         type="string",
     *                         example="http://localhost/api/user/history"
     *                     ),
     *                     @OA\Property(
     *                         property="per_page",
     *                         type="integer",
     *                         example=7
     *                     ),
     *                     @OA\Property(
     *                         property="to",
     *                         type="integer",
     *                         example=1
     *                     ),
     *                     @OA\Property(
     *                         property="total",
     *                         type="integer",
     *                         example=1
     *                     )
     *                 )
     *             )
     *         )
     *     ),
     *      @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="data", type="string", example="Unauthorized")
     *         )
     *     )
     * )
     */
    public function getHistory(Request $request)
    {
        $history = $this->userService->getHistory($request->user());

        return Response::send(LobbyResource::collection($history)->response()->getData(true));
    }
}
