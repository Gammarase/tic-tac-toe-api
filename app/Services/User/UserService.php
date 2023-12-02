<?php

namespace App\Services\User;

use App\Enums\GameStatus;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\AbstractService;

class UserService extends AbstractService
{
    public function updateUser(array $data, User $user)
    {
        $user->update($data);

        return new UserResource($user);
    }

    public function getHistory(User $user)
    {
        return $user
            ->lobbies()
            ->where('status', GameStatus::FINISHED)
            ->with('players')
            ->orderBy('finished_at', 'DESC')
            ->paginate(7);
    }
}
