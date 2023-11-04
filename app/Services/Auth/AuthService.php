<?php

namespace App\Services\Auth;

use App\Http\Response;
use App\Models\User;
use App\Services\AbstractService;
use Illuminate\Support\Facades\Hash;

class AuthService extends AbstractService
{
    public function regisrateUser(array $userData)
    {
        $user = User::create([
            'username' => $userData['username'],
            'email' => $userData['email'],
            'password' => Hash::make($userData['password']),
        ]);

        return $user->createToken('default')->plainTextToken;
    }

    public function loginUser(array $credentials)
    {
        $user = User::where('email', $credentials['email'])->first();
        if ($user === null) {
            abort(Response::BAD_REQUEST, __('No user with this email'));
        }

        if (! Hash::check($credentials['password'], $user->password)) {
            abort(Response::BAD_REQUEST, __('Wrong password'));
        }

        return $user->createToken('default')->plainTextToken;
    }
}
