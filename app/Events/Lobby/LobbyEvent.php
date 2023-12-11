<?php

namespace App\Events\Lobby;

use App\Http\Resources\LobbyResource;
use App\Models\Lobby;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class LobbyEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(public Lobby $lobby)
    {
        //
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new Channel("lobby-{$this->lobby->id}"),
        ];
    }

    public function broadcastData(): array
    {
        return [
            'id' => $this->lobby->id,
            'winner' =>$this->lobby->winner ? $this->lobby->winner->only(['id', 'username']) : null,
            'status' => $this->lobby->status,
            'state' => $this->lobby->state,
            'finished_at' => $this->lobby->finished_at,
            'players' => $this->lobby->players->map(function ($player){
                return [
                    'id' => $player->id,
                    'username' => $player->username,
                    'figure' => $player->pivot->figure,
                ];
            }),
        ];
    }
}
