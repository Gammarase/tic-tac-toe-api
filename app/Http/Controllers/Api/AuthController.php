<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Auth\{RegistrationRequest, LoginRequest};
use App\Http\Controllers\Controller;
use App\Http\Response;
use App\Services\Auth\AuthService;

class AuthController extends Controller
{

    public function __construct(
        private AuthService $authService
    ) {
    }
    /**
     * Display a listing of the resource.
     */
    public function registrate(RegistrationRequest $request)
    {
        $access_token = $this->authService->regisrateUser($request->all());
        return Response::send(['access_token' => $access_token]);
    }

    public function login(LoginRequest $request)
    {
        $access_token = $this->authService->loginUser($request->only(['email', 'password']));
        return Response::send(['access_token' => $access_token]);
    }
}
