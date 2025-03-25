<?php

namespace App\Events;

use App\Models\Room;
use App\Models\Staff;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class RoomAlertStatusChanged implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;


    /**
     * The order instance.
     *
     * @var \App\Models\Room
     */
    public $data;
    private $user;

    public $room;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($data,Staff $user,Room $room)
    {
        $this->data = $data;
        $this->user = $user;
        $this->room = $room;
     }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        // return new Channel('room-alert');
        return new PrivateChannel('roomalert.' .  $this->user->id);
    }
    public function broadcastWith()
{
    return ['data' => $this->data];
}
}
