<?php

namespace App\Services\User;

use App\Http\Resources\UserResource;
use App\Http\Response;
use App\Models\User;
use App\Services\AbstractService;

class UserService extends AbstractService
{
    public function updateUser(array $data, User $user)
    {
        $user->update($data);
        return new UserResource($user);
    }
}
